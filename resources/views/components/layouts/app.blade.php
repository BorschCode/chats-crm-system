@props([
    'title' => config('app.name'),
    'description' => null,
    'keywords' => null,
    'canonical' => null,
    'image' => null,
    'robots' => 'index, follow',
    'author' => config('app.name'),
    'publisher' => config('app.name'),
    'type' => 'website',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title }}</title>

        @if ($description)
            <meta name="description" content="{{ $description }}">
        @endif
        @if ($keywords)
            <meta name="keywords" content="{{ $keywords }}">
        @endif
        @if ($robots)
            <meta name="robots" content="{{ $robots }}">
        @endif
        @if ($author)
            <meta name="author" content="{{ $author }}">
        @endif
        @if ($publisher)
            <meta name="publisher" content="{{ $publisher }}">
        @endif
        @if ($canonical)
            <link rel="canonical" href="{{ $canonical }}">
        @endif

        {{-- Open Graph --}}
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:type" content="{{ $type }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:url" content="{{ $canonical ?? url()->current() }}">
        @if ($description)
            <meta property="og:description" content="{{ $description }}">
        @endif
        @if ($image)
            <meta property="og:image" content="{{ $image }}">
            <meta property="og:image:alt" content="{{ $title }}">
        @endif

        {{-- Twitter Card --}}
        <meta name="twitter:card" content="{{ $image ? 'summary_large_image' : 'summary' }}">
        <meta name="twitter:title" content="{{ $title }}">
        @if ($description)
            <meta name="twitter:description" content="{{ $description }}">
        @endif
        @if ($image)
            <meta name="twitter:image" content="{{ $image }}">
        @endif

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 min-h-screen flex flex-col">
        <x-header />
        <main class="flex-grow">
            {{ $slot }}
        </main>
        <x-footer />
    </body>
</html>
