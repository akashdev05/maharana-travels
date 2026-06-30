<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        return response()->json(['data' => $this->services()]);
    }

    public function show(string $slug)
    {
        $service = collect($this->services())->firstWhere('slug', $slug);

        if (!$service) {
            return response()->json(['message' => 'Service not found'], 404);
        }

        $service['route_prices'] = $this->getRoutePrices($service['from'], $service['to']);

        return response()->json(['data' => $service]);
    }

    private function services(): array
    {
        return config('travel_site.services', []);
    }

    private function getRoutePrices(string $from, string $to): ?array
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
}
