<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="h-full">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
{{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    <style>
        /* Style the tab */
        .tab {
            overflow: hidden;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
            /*background-color: inherit;*/
            float: left;
            /*border: black;*/
            outline: none;
            cursor: pointer;
            /*padding: 14px 16px;*/
            transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            /*background-color: #ddd;*/
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #fff;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
        }
    </style>
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
    <title>TM Club Admin</title>
    {{--    <title><?php if (config('APP_NAME') != ''){--}}
    {{--            echo env('APP_NAME');--}}
    {{--        }  else { echo "Admin area"; } ?></title>--}}
</head>
<body class="h-full bg-red-600 text-white">
@include('nav.admin_navbar')

<header class="bg-red-800 drop-shadow-md">



            {{--        So - for small screens --}}
            <div class="topnav " id="myTopnav">

                @auth
                    <a href="/auth/profile"  class="text-white" ><i class="text-xl fa-solid fa-user"></i></a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button class="topnav"><i class="text-xl fa-solid fa-right-from-bracket"></i></button>
                    </form>
                @endauth
                <a href="/" class="text-white"><i class="text-xl  fa-solid fa-house"></i></a>
            </div>
</header>
<main class="bg-red-50 text-black">
    <div class="mx-auto max-w-7xl px-2 py-6 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
    <div class = "bg-red-600">
        <div class="text-sm mt-1 p-2 text-white font-semibold text-center">©{{date("Y")}} - Oldgit UK</div>
    </div>
</main>
</body>
</html>
