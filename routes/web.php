<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| All routes are handled by the Vue SPA. The only exception is the
| API routes (defined in routes/api.php).
*/

function site_url(string $path = ''): string
{
    $base = config('app.url') ?: request()->getSchemeAndHttpHost();
    $base = rtrim($base, '/');

    return $base . '/' . ltrim($path, '/');
}

function static_seo_pages(): array
{
    return [
        '/' => [
            'title' => 'Taxi Service in Chandigarh Tricity | Maharana Travels',
            'description' => 'Book Maharana Travels for taxi and tour travel services in Zirakpur, Chandigarh, Mohali, Panchkula and Ramgarh, plus airport and outstation cabs.',
            'priority' => '1.0',
        ],
        '/our-services' => [
            'title' => 'Outstation Taxi Services | One Way & Round Trip Cabs',
            'description' => 'Explore Maharana Travels route-wise taxi services for Delhi, Chandigarh, Shimla, Manali, Amritsar, Haridwar, Rishikesh and more.',
            'priority' => '0.9',
        ],
        '/our-cabs' => [
            'title' => 'Our Cabs | Sedan, SUV, Crysta & Tempo Traveller',
            'description' => 'Choose from Maharana Travels cabs including Dzire, Ertiga, Innova Crysta, Hycross, Kia Carens and tempo travellers for comfortable journeys.',
            'priority' => '0.8',
        ],
        '/cities' => [
            'title' => 'Cities We Serve | Taxi Service Across North India',
            'description' => 'Maharana Travels serves Delhi, Chandigarh, Mohali, Patiala, Ludhiana, Amritsar, Shimla, Manali, Haridwar, Rishikesh and nearby cities.',
            'priority' => '0.8',
        ],
        '/wedding-cars' => [
            'title' => 'Wedding Car Rental | Maharana Travels',
            'description' => 'Book clean and comfortable wedding cars with Maharana Travels for guest transport, family travel and special occasions.',
            'priority' => '0.7',
        ],
        '/about' => [
            'title' => 'About Maharana Travels | Trusted Cab Service',
            'description' => 'Maharana Travels provides safe, comfortable and affordable taxi services across North India with verified drivers and 24/7 support.',
            'priority' => '0.7',
        ],
        '/contact' => [
            'title' => 'Contact Maharana Travels | Call or WhatsApp for Cab Booking',
            'description' => 'Contact Maharana Travels for cab booking, airport transfer, local taxi, outstation taxi and wedding car rental support.',
            'priority' => '0.7',
        ],
        '/taxi-service-chandigarh' => ['title' => 'Taxi & Tour Travel Services in Chandigarh | Maharana Travels', 'description' => 'Book taxi, airport transfer, outstation and local sightseeing travel from Chandigarh with Maharana Travels.', 'priority' => '0.9'],
        '/taxi-service-zirakpur' => ['title' => 'Taxi & Tour Travel Services in Zirakpur | Maharana Travels', 'description' => 'Book taxi, airport transfer, outstation and local sightseeing travel from Zirakpur with Maharana Travels.', 'priority' => '0.9'],
        '/taxi-service-mohali' => ['title' => 'Taxi & Tour Travel Services in Mohali | Maharana Travels', 'description' => 'Book taxi, airport transfer, outstation and local sightseeing travel from Mohali with Maharana Travels.', 'priority' => '0.9'],
        '/taxi-service-panchkula' => ['title' => 'Taxi & Tour Travel Services in Panchkula | Maharana Travels', 'description' => 'Book taxi, airport transfer, outstation and local sightseeing travel from Panchkula with Maharana Travels.', 'priority' => '0.9'],
        '/taxi-service-ramgarh' => ['title' => 'Taxi & Tour Travel Services in Ramgarh | Maharana Travels', 'description' => 'Book taxi and tour travel services in Ramgarh with Maharana Travels for airport transfers, local sightseeing and outstation journeys.', 'priority' => '0.9'],
        '/chandigarh-airport-taxi' => ['title' => 'Chandigarh Airport Taxi Service | Maharana Travels', 'description' => 'Book a Chandigarh Airport taxi with Maharana Travels for scheduled airport pickup and drop service.', 'priority' => '0.9'],
        '/delhi-airport-taxi' => ['title' => 'Delhi Airport Taxi Service | Maharana Travels', 'description' => 'Book a Delhi Airport (IGI) taxi with Maharana Travels for scheduled airport pickup and drop service.', 'priority' => '0.9'],
    ];
}

function seo_for_path(string $path): array
{
    $path = '/' . trim($path, '/');
    $path = $path === '/' ? '/' : rtrim($path, '/');
    $site = config('travel_site.site', []);
    $defaultImage = site_url('/images/logo-180.webp');
    $pages = static_seo_pages();

    $nonIndexable = [
        '/search' => ['title' => 'Search Cabs | Maharana Travels', 'description' => 'Compare available cabs and fares for your selected route with Maharana Travels.', 'robots' => 'noindex, follow'],
        '/booking' => ['title' => 'Book a Cab Online | Maharana Travels', 'description' => 'Confirm your cab booking with Maharana Travels.', 'robots' => 'noindex, follow'],
        '/booking/success' => ['title' => 'Booking Confirmed | Maharana Travels', 'description' => 'Your Maharana Travels cab booking request has been submitted successfully.', 'robots' => 'noindex, nofollow'],
    ];

    $seo = $pages[$path] ?? $nonIndexable[$path] ?? [
        'title' => 'Maharana Travels | Cab Booking | Taxi Service',
        'description' => 'Book reliable taxi service with Maharana Travels for one way cabs, round trips, airport transfers and outstation taxi across North India.',
    ];

    if (Str::startsWith($path, '/our-services/')) {
        $slug = Str::after($path, '/our-services/');
        $service = collect(config('travel_site.services', []))->firstWhere('slug', $slug);

        if ($service) {
            $seo = [
                'title' => "{$service['from']} to {$service['to']} Taxi | One Way & Round Trip Cab",
                'description' => "Book {$service['from']} to {$service['to']} taxi with Maharana Travels. Affordable one way and round trip cabs with verified drivers and clean vehicles.",
                'schema' => [
                    '@context' => 'https://schema.org',
                    '@type' => 'Service',
                    'name' => $service['title'],
                    'serviceType' => 'Outstation taxi service',
                    'provider' => [
                        '@id' => site_url('/#business'),
                    ],
                    'areaServed' => [$service['from'], $service['to']],
                ],
            ];
        }
    }

    return array_merge([
        'image' => $defaultImage,
        'url' => site_url($path),
        'robots' => 'index, follow',
        'schema' => [
            '@context' => 'https://schema.org',
            '@type' => 'TaxiService',
            '@id' => site_url('/#business'),
            'name' => $site['name'] ?? 'Maharana Travels',
            'url' => site_url('/'),
            'image' => $defaultImage,
            'telephone' => $site['phone'] ?? '+91 9416198045',
            'email' => $site['email'] ?? 'maharanatravels0001@gmail.com',
            'address' => $site['address'] ?? 'Zirakpur, Punjab',
            'areaServed' => ['Zirakpur', 'Chandigarh', 'Mohali', 'Panchkula', 'Ramgarh', 'Chandigarh Tricity', 'Delhi NCR', 'Himachal Pradesh', 'Uttarakhand'],
        ],
    ], $seo);
}

Route::get('/sitemap.xml', function () {
    $urls = collect(static_seo_pages())->reject(fn ($page) => ($page['robots'] ?? '') === 'noindex, follow')->map(fn ($page, $path) => [
        'loc' => site_url($path),
        'priority' => $page['priority'] ?? '0.7',
    ]);

    $serviceUrls = collect(config('travel_site.services', []))->map(fn ($service) => [
        'loc' => site_url('/our-services/' . $service['slug']),
        'priority' => '0.85',
    ]);

    $xml = view('sitemap', [
        'urls' => $urls->merge($serviceUrls),
    ])->render();

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

Route::get('/robots.txt', function () {
    return response(
        "User-agent: *\nAllow: /\nDisallow: /api/\nDisallow: /booking/success\nSitemap: " . site_url('/sitemap.xml') . "\n",
        200
    )->header('Content-Type', 'text/plain');
});

Route::get('/{any}', function (string $any = '') {
    $path = '/' . trim($any, '/');
    $path = $path === '/' ? '/' : rtrim($path, '/');
    $knownPaths = array_merge(array_keys(static_seo_pages()), ['/search', '/booking', '/booking/success']);
    $knownServicePaths = collect(config('travel_site.services', []))
        ->map(fn ($service) => '/our-services/' . $service['slug'])
        ->all();
    $isKnown = in_array($path, $knownPaths, true) || in_array($path, $knownServicePaths, true);

    $seo = seo_for_path($any);
    if (!$isKnown) {
        $seo = array_merge($seo, [
            'title' => 'Page Not Found | Maharana Travels',
            'description' => 'The page you are looking for could not be found.',
            'robots' => 'noindex, follow',
            'url' => null,
            'schema' => null,
        ]);
    }

    return response()->view('app', [
        'seo' => $seo,
    ], $isKnown ? 200 : 404);
})->where('any', '.*');
