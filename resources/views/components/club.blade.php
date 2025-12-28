<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <x-head.tinymce-config/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        function openSection(evt, tabName) {
            // Declare all variables
            var i, tabcontent, tablinks;

            document.cookie = "selectedTab=" + tabName;

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
            color: lavender;
        }

        .topnav a:hover {
            color: lavender;
        }

        .topnav a.active {
        }

        @media screen and (max-width: 600px) {
            .topnav {
                display: inline-flex;
            }

            .topnav a {
                display: inline-block;
            }

            .topnav button {
                display: inline-block;
            }
        }

    </style>

    <title>{{config('app.name')}}</title>
</head>
<body class="h-full bg-violet-800 text-white">
@include('components.clubnav')

<main class="bg-violet-100 text-black">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <div class="bg-violet-800 text-white">
        <x-footer-link>
            <div class="text-center mx-auto  text-white">
                @auth
                    @if (Auth::user()->isAdminUser == 1)
                        <a href="/adminaccess" class="inline-block mt-1 mx-3 hover:underline">Admin access</a>
                    @endif

                    @if (Auth::user()->isClubUser == 1)
                        <a href="/results/list" class="inline-block mt-1 mx-3 hover:underline">Public access</a>
                    @endif
                @endauth
            </div>
        </x-footer-link>
        <div class="text-sm text-center  text-white"><a href="https://oldgit.uk">©2018 - {{date("Y")}} Development by
                Oldgit UK</a><br>&nbsp;
        </div>
    </div>
</main>
<script>
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
