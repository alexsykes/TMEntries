<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="h-full">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-head.tinymce-config/>
{{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        /*#map {*/
        /*    height: 600px;*/
        /*    width: 100%;*/
        /*}*/

        /* Hide display on large screens */
        .topnav {
            display: none;
            overflow: hidden;
            color: white;
            position: absolute;
            top: 1.1rem;
            right: 1rem;
        }

        .topnav a {
            float: left;
            display: inline;
            text-align: left;
            padding: 8px 6px;
            text-decoration: none;
            font-size: 15px;
        }

        .topnav button {
            position: relative;
            top: 0;
            right: 0;
            padding: 8px 6px;
            text-align: left;
            font-size: 15px
        }

        .topnav button:hover {
            color:lavender;
        }

        .topnav a:hover {
            color:lavender;
        }

        .topnav a.active {
        }

        @media screen and (max-width: 600px) {
            .topnav {
                display: inline-flex;
            }
            .topnav a {display: inline-block;}
            .topnav button {display: inline-block;}
        }

    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Motorcycle trials entry, scoring and results management system.">
    <meta name="keywords" content="Motorcycle trials management system, trials scoring, trials results, trials registration">
    <title>TM Club Admin</title>
</head>
<body class="h-full bg-red-600 text-white">
@include('nav.admin_navbar')


<main class="bg-red-50 text-black">
    <div class="mx-auto max-w-7xl px-2 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <div class = "bg-red-600">
        <div class="text-sm text-center   text-white"><a href="https://oldgit.uk">©2018 - {{date("Y")}} Development by Oldgit UK</a><br>&nbsp;</div>
    </div>
</main><script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.querySelector('button[aria-controls="mobile-menu"]');
        const menu = document.getElementById('mobile-menu');

        button.addEventListener('click', function () {
            menu.classList.toggle('hidden');
        });
    });
</script>
</body>
</html>
