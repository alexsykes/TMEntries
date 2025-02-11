<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="h-full bg-violet-900">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!--script src="https://cdn.tailwindcss.com"></script -->
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map {
            height: 600px;
            width: 100%;
        }

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
    <title><?php if (env('APP_NAME') != ''){
            echo env('APP_NAME');
        } ?></title>



</head>
<body class="h-full bg-violet-800 text-white">

<header class="bg-violet-800 drop-shadow-md">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8  sm:flex sm:justify-between">
        <h1 class="text-m sm:text-2xl  font-bold tracking-tight text-white">{{ $heading }}</h1>


{{--        Hidden for small screens --}}
        <div class="hidden sm:block">
            <div class="ml-4 flex space-x-4 items-center m-auto px md:ml-6" >
                <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                @guest
                    <x-nav-link href="/login" :active="request()->is('login')">Log In</x-nav-link>
                    <x-nav-link href="/register" :active="request()->is('register')">Register</x-nav-link>
                @endguest

                @auth
{{--                Logout via form --}}
                    <form method="POST" action="/logout">
                        @csrf
                        <x-form-button>Log Out</x-form-button>
                    </form>
                    <x-nav-link href="/auth/profile" :active="request()->is('auth/profile')">Profile</x-nav-link>
                @endauth
            </div>
        </div>



{{--        So - for small screens --}}
        <div class="topnav " id="myTopnav">
            @guest
                <a href="/register"  class="text-white" ><i class="text-xl fa-solid fa-user-plus"></i></a>
                <a href="/login"  class="text-white " ><i class="text-xl fa-solid fa-right-to-bracket"></i></a>
            @endguest
            @auth
                <a href="/auth/profile"  class="text-white" ><i class="text-xl fa-solid fa-user"></i></a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="topnav"><i class="text-xl fa-solid fa-right-from-bracket"></i></button>
                    </form>
            @endauth
            <a href="/" class="text-white"><i class="text-xl  fa-solid fa-house"></i></a>
        </div>
    </div>
</header>
<main class="bg-gray-50 text-black">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <hr>
    <div class = "bg-violet-800 text-white">
    <x-footer-link>
        <div class="text-center mx-auto  text-white">
            <a href="/about" class="inline-block mt-1 mx-3 hover:underline">About</a>
            <a href="/terms" class="inline-block mt-1 mx-3 hover:underline">Terms and Conditions</a>
            <a href="/privacy"  class="inline-block mt-1 mx-3 hover:underline">Privacy Policy</a>
            <a href="/contact"  class="inline-block mt-1 mx-3 hover:underline">Contact</a>
        </div>
    </x-footer-link>
    <div class="text-sm mt-1 text-center bg-violet-800 text-white">©{{date("Y")}} - SlopeFinder UK</div></div>
</main>
</body>
</html>
