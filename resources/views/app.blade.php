<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $seo = $seo ?? [
            'title' => 'Maharana Travels | Cab Booking | Taxi Service',
            'description' => 'Book reliable taxi service with Maharana Travels. One way cabs, round trips, airport transfers and outstation taxi at affordable fares.',
            'url' => url()->current(),
            'image' => url('/images/logo-180.webp'),
            'robots' => 'index, follow',
            'schema' => null,
        ];
        $allowTracking = app()->environment('production') && !config('app.debug');
        $initialSiteData = [
            'site' => config('travel_site.site'),
            'routes' => config('travel_site.routes'),
            'destinations' => config('travel_site.destinations'),
            'advantages' => config('travel_site.advantages'),
            'faqs' => config('travel_site.faqs'),
            'cabs' => config('travel_site.cabs'),
            'cities' => config('travel_site.cities'),
        ];
        $initialSiteDataJson = json_encode($initialSiteData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <link rel="canonical" href="{{ $seo['url'] }}">
    <link rel="preload" as="image" href="/images/hero-travel-bg.webp" fetchpriority="high">
    <link rel="preload" as="image" href="/images/logo-180.webp" fetchpriority="high">

    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $seo['url'] }}">
    <meta property="og:image" content="{{ $seo['image'] }}">
    <meta property="og:site_name" content="Maharana Travels">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    <meta name="twitter:image" content="{{ $seo['image'] }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/images/favicon-48.png">
    <link rel="apple-touch-icon" href="/images/logo-180.png">

    @if (!empty($seo['schema']))
        <script id="server-jsonld" type="application/ld+json">@json($seo['schema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
    @endif
    <script id="initial-site-data" type="application/json">{!! $initialSiteDataJson !!}</script>

    <script>
        (function () {
            try {
                var saved = localStorage.getItem('maharana-theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = saved || (prefersDark ? 'dark' : 'light');
                document.documentElement.dataset.theme = theme;
                document.querySelector('meta[name="theme-color"]').setAttribute('content', theme === 'dark' ? '#0f172a' : '#ffffff');
            } catch (e) {}
        })();
    </script>

    @if ($allowTracking && env('VITE_GTM_ID'))
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{ env('VITE_GTM_ID') }}');
        </script>
    @endif

    @if ($allowTracking && env('VITE_GA_ID'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('VITE_GA_ID') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ env('VITE_GA_ID') }}');
        </script>
    @endif

    @if ($allowTracking && env('VITE_META_PIXEL_ID'))
        <script>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ env('VITE_META_PIXEL_ID') }}');
            fbq('track', 'PageView');
        </script>
    @endif

    <!-- Vite assets (CSS + JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @if ($allowTracking && env('VITE_GTM_ID'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ env('VITE_GTM_ID') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    @if ($allowTracking && env('VITE_META_PIXEL_ID'))
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ env('VITE_META_PIXEL_ID') }}&ev=PageView&noscript=1" alt=""></noscript>
    @endif
    {{-- Vue mounts here --}}
    <div id="app"></div>
</body>
</html>
