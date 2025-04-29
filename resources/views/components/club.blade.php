<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="h-full bg-violet-900">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
{{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <x-head.tinymce-config/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>

        function openSection(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }


        function toggle(checked, divName) {
            console.log("toggle called")
            var x = document.getElementById(divName);
            if (checked) {
                x.style.display = "inline-block";
            } else {
                x.style.display = "none";
            }
        }
    </script>

    <title><?php if (config('APP_NAME') != ''){
            echo env('APP_NAME');
        }  else { echo "Club admin"; } ?></title>
</head>
<body class="h-full bg-violet-800 text-white">

    {{--    @php $heading = "Welcome" @endphp--}}
    <header class="bg-violet-800 drop-shadow-md">
        <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8  sm:flex sm:justify-between">
            <h1 class="text-m sm:text-lg  font-bold tracking-tight text-white">{{ $heading }}</h1>


            {{--        Hidden for small screens --}}
            <div class="hidden sm:block">
                <div class="ml-4 flex space-x-4 items-center m-auto px md:ml-6" >
                    <x-nav-link href="/" :active="request()->is('/')">Public site</x-nav-link>
                    <x-nav-link href="/clubaccess" :active="request()->is('/clubaccess')">Trials</x-nav-link>
                    @guest
                        <x-nav-link href="/login" :active="request()->is('login')">Log In</x-nav-link>
                        {{--                        <x-nav-link href="/register" :active="request()->is('register')">Register</x-nav-link>--}}
                    @endguest

                    @auth
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit"
                                    class="rounded-md ml-0 bg-violet-500 px-2 py-1 text-sm font-light  border border-white text-white drop-shadow-xl hover:bg-violet-500 focus-visible:outline focus-visible:outline focus-visible:outline-offset-2 focus-visible:outline-violet-600">Log out</button>
                        </form>
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
<main class="bg-violet-100 text-black">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <div class = "bg-violet-800 text-white">
        <x-footer-link>
            <div class="text-center mx-auto  text-white">
                @auth
                    @if (Auth::user()->isAdminUser == 1)
                        <a href="/adminaccess" class="inline-block mt-1 mx-3 hover:underline">Admin access</a>
                    @endif

                    @if (Auth::user()->isClubUser == 1)
                        <a href="/clubaccess" class="inline-block mt-1 mx-3 hover:underline">Club access</a>
                    @endif
                @endauth
            </div>
        </x-footer-link>
        <div class="text-sm mt-1 text-center bg-violet-800 text-white">©{{date("Y")}} - Oldgit UK</div></div>
</main>
</body>
</html>
