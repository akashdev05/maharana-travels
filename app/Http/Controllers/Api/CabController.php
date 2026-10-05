<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CabController extends Controller
{
    /**
     * All cabs (used on homepage & Our Cabs page).
     */
    public function index()
    {
        return response()->json([
            'data' => $this->cabs(),
        ]);
    }

    /**
     * Search cabs for a specific route.
     * Returns cabs with calculated pricing based on distance.
     *
     * Query params: from, to, date, time, trip_type (oneway|round-trip)
     */
    public function search(Request $request)
    {
        $request->validate([
            'from'      => 'required|string',
            'to'        => 'required|string',
            'date'      => 'required|date|after_or_equal:today',
            'time'      => 'required',
            'trip_type' => 'required|in:oneway,round-trip',
        ]);

        $from      = $request->from;
        $to        = $request->to;
        $tripType  = $request->trip_type;
        $routePrices = $this->getRoutePrices($from, $to);

        if ($routePrices === null) {
            return response()->json([
                'data' => [],
                'message' => 'This route requires a custom fare confirmation.',
                'meta' => [
                    'from' => $from,
                    'to' => $to,
                    'date' => $request->date,
                    'time' => $request->time,
                    'trip_type' => $tripType,
                    'distance_km' => null,
                    'fixed_price' => false,
                    'price_available' => false,
                    'contact_phone' => config('travel_site.site.phone'),
                    'contact_phone_raw' => config('travel_site.site.phone_raw'),
                ],
            ]);
        }

        $distanceKm = $this->getDistance($from, $to);
        $multiplier = $tripType === 'round-trip' ? 2 : 1;

        $cabs = collect($this->cabs())->map(function ($cab) use ($distanceKm, $multiplier, $routePrices, $tripType) {
            $fixedPrice = $this->getCabRoutePrice($routePrices, $cab['name'], $tripType);

            if ($fixedPrice !== null) {
                return array_merge($cab, [
                    'base_price'    => $fixedPrice,
                    'final_price'   => $fixedPrice,
                    'discount'      => 0,
                    'discount_pct'  => 0,
                    'distance_km'   => $distanceKm,
                    'fixed_price'   => true,
                ]);
            }

            if ($routePrices !== null) {
                return null;
            }

            $base  = round($cab['price_per_km'] * $distanceKm * $multiplier);
            $final = round($base * 0.95);          // 5 % discount
            return array_merge($cab, [
                'base_price'    => $base,
                'final_price'   => $final,
                'discount'      => $base - $final,
                'discount_pct'  => 5,
                'distance_km'   => $distanceKm,
                'fixed_price'   => false,
            ]);
        })->filter()->values();

        return response()->json([
            'data' => $cabs,
            'meta' => [
                'from'        => $from,
                'to'          => $to,
                'date'        => $request->date,
                'time'        => $request->time,
                'trip_type'   => $tripType,
                'distance_km' => $distanceKm,
                'fixed_price' => $routePrices !== null,
                'price_available' => true,
            ],
        ]);
    }

    // ─── Private helpers ────────────────────────────────────

    private function getDistance(string $from, string $to): int
    {
        $key = strtolower(preg_replace('/[^a-z]/i', '', $from))
             . '-'
             . strtolower(preg_replace('/[^a-z]/i', '', $to));

        $map = [
            'delhi-shimla'          => 345, 'shimla-delhi'          => 345,
            'delhi-chandigarh'      => 250, 'chandigarh-delhi'      => 250,
            'delhi-manali'          => 572, 'manali-delhi'          => 572,
            'chandigarh-shimla'     => 115, 'shimla-chandigarh'     => 115,
            'chandigarh-manali'     => 310, 'manali-chandigarh'     => 310,
            'amritsar-chandigarh'   => 220, 'chandigarh-amritsar'   => 220,
            'delhi-amritsar'        => 460, 'amritsar-delhi'        => 460,
            'chandigarh-ludhiana'   => 100, 'ludhiana-chandigarh'   => 100,
            'mohali-patiala'        => 65,  'patiala-mohali'        => 65,
            'delhi-noida'           => 25,  'noida-delhi'           => 25,
            'delhi-gurgaon'         => 32,  'gurgaon-delhi'         => 32,
            'kharar-jalandhar'      => 155, 'jalandhar-kharar'      => 155,
            'kharar-delhi'          => 270, 'delhi-kharar'          => 270,
            'chandigarh-pathankot'  => 190, 'pathankot-chandigarh'  => 190,
            'chandigarh-ambala'     => 50,  'ambala-chandigarh'     => 50,
            'zirakpur-gurgaon'      => 260, 'gurgaon-zirakpur'      => 260,
            'zirakpur-delhiairport' => 260, 'delhiairport-zirakpur' => 260,
            'chandigarh-hamirpur'   => 145, 'hamirpur-chandigarh'   => 145,
            'chandigarh-solan'      => 67,  'solan-chandigarh'      => 67,
            'chandigarh-kasauli'    => 58,  'kasauli-chandigarh'    => 58,
            'chandigarh-kasoli'     => 58,  'kasoli-chandigarh'     => 58,
            'chandigarh-una'        => 120, 'una-chandigarh'        => 120,
            'chandigarhairport-shimla' => 115, 'shimla-chandigarhairport' => 115,
            'chandigarh-ghaziabad'  => 295, 'ghaziabad-chandigarh'  => 295,
            'chandigarh-noida'      => 280, 'noida-chandigarh'      => 280,
            'kharar-noida'          => 275, 'noida-kharar'          => 275,
        ];

        return $map[$key] ?? 200; // default 200 km
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

    private function getCabRoutePrice(?array $routePrices, string $cabName, string $tripType): ?int
    {
        if (!$routePrices) {
            return null;
        }

        $cabKey = $this->normalizeCar($cabName);
        $priceKey = $tripType === 'round-trip' ? 'round_trip' : 'oneway';

        foreach ($routePrices as $price) {
            if ($this->normalizeCar($price['car_name']) === $cabKey) {
                return (int) $price[$priceKey];
            }
        }

        return null;
    }

    private function normalizeCity(string $city): string
    {
        $key = strtolower(preg_replace('/[^a-z]/i', '', $city));

        return [
            'jamu' => 'jammu',
            'kasoli' => 'kasauli',
        ][$key] ?? $key;
    }

    private function normalizeCar(string $car): string
    {
        $key = strtolower(preg_replace('/[^a-z0-9]/i', '', $car));

        return [
            'toyotarumion' => 'rumion',
            'marutisuzukidzire' => 'dzire',
            'marutidzire' => 'dzire',
            'marutisuzukiertiga' => 'ertiga',
            'toyotainnovacrysta' => 'crysta',
            'innovacrysta' => 'crysta',
            'toyotahycross' => 'hycross',
        ][$key] ?? $key;
    }

    private function cabs(): array
    {
        return config('travel_site.cabs', []);
    }
}
