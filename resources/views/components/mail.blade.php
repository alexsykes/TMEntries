<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full ">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/086d4db9c7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        p {
            line-height: 20px;   /* within paragraph */
            margin-bottom: 8px; /* between paragraphs */
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="h-full bg-blue-800 text-white">
<main class="bg-blue-100 text-black">
    <div class="mx-auto max-w-7xl px-4 py-6">
        <div class=" bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="p-2">
        {{ $slot }}
        </div>
        </div>
    </div>
    <div class="bg-blue-800 text-white pb-4">

            <div class="text-center mx-auto  text-white">
                <a href="{{Config::get('app.url')}}/profile" class="inline-block mt-1 mx-3 hover:underline">Don't want these emails? To unsubscribe, click here</a>
            </div>

        <div class="text-sm mt-2 text-center  bg-blue-800 text-white"><a href="https://oldgit.uk">©2018 - {{date("Y")}} Development by Oldgit UK</a></div>
    </div>
</main>
</body>
</html>
