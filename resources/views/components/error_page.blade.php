<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full ">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Motorcycle trials entry, scoring and results management system.">
    <meta name="keywords" content="Motorcycle trials management system, trials scoring, trials results, trials registration">
    <title>{{config('app.name')}}</title>
</head>
<body class="h-full bg-gray-200">
<main class="bg-gray-200">
    <div class="bg-gray-200 mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
        <div class="text-sm text-center    text-gray-500"><a href="https://oldgit.uk">©2018 - {{date("Y")}} Development by Oldgit UK</a></div>
</main>
</body>
</html>
