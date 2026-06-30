<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'site' => config('travel_site.site'),
                'routes' => $this->popularRoutes(),
                'destinations' => config('travel_site.destinations'),
                'advantages' => config('travel_site.advantages'),
                'faqs' => config('travel_site.faqs'),
                'cabs' => config('travel_site.cabs'),
                'cities' => config('travel_site.cities'),
            ],
        ]);
    }

    private function popularRoutes(): array
    {
        return collect(config('travel_site.routes', []))
            ->map(function (array $route) {
                $route['price'] = $this->startingFare($route['from'], $route['to']) ?? $route['price'];

                return $route;
            })
            ->all();
    }

    private function startingFare(string $from, string $to): ?int
    {
        $routePrices = $this->routePrices($from, $to);

        if (!$routePrices) {
            return null;
        }

        $availableCars = collect(config('travel_site.cabs', []))
            ->map(fn (array $cab) => $this->normalizeCar($cab['name']))
            ->all();

        $fares = collect($routePrices)
            ->filter(fn (array $price) => in_array($this->normalizeCar($price['car_name']), $availableCars, true))
            ->pluck('oneway')
            ->filter(fn ($fare) => is_numeric($fare))
            ->map(fn ($fare) => (int) $fare);

        return $fares->isEmpty() ? null : $fares->min();
    }

    private function routePrices(string $from, string $to): ?array
    {
        $fromKey = $this->normalizeCity($from);
        $toKey = $this->normalizeCity($to);

        foreach (config('travel_site.route_prices', []) as $route) {
            $routeFrom = $this->normalizeCity($route['from']);
            $routeTo = $this->normalizeCity($route['to']);

            if (
                ($routeFrom === $fromKey && $routeTo === $toKey) ||
                ($routeFrom === $toKey && $routeTo === $fromKey)
            ) {
                return $route['prices'];
            }
        }

        return null;
    }

    private function normalizeCity(string $city): string
    {
        $key = strtolower(preg_replace('/[^a-z]/i', '', $city));

        return [
            'jamu' => 'jammu',
        ][$key] ?? $key;
    }

    private function normalizeCar(string $car): string
    {
        $key = strtolower(preg_replace('/[^a-z0-9]/i', '', $car));

        return [
            'marutisuzukidzire' => 'dzire',
            'marutidzire' => 'dzire',
            'marutisuzukiertiga' => 'ertiga',
            'toyotainnovacrysta' => 'crysta',
            'innovacrysta' => 'crysta',
            'toyotahycross' => 'hycross',
        ][$key] ?? $key;
    }
}
