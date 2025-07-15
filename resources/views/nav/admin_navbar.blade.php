<nav class="bg-red-700">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="flex-1 flex items-center justify-start sm:items-stretch sm:justify-between">
                <div class="flex-shrink-0">
                    <a href="#" class="text-white text-lg font-bold">{{$heading}}</a>
                </div>
                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4">
                        <a href="/" class="text-white bg-blue-800 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Public site</a>
                        <a href="/clubs/list" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Clubs</a>
                        <a href="/admin/mails" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Mails</a>
                        <a href="/admin/venues" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Venues</a>
{{--                        <a href="/series/list" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Series</a>--}}
                        <a href="/admin/trials" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Trials</a>
                        <a href="/admin/results" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Results</a>
                        <a href="/admin/users" class="text-white bg-red-600 border-white border  hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Users</a>
{{--                        <a href="/admin/sendMailDelete" class="text-white bg-red-600 border-white border hover:bg-blue-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Email</a>--}}
                        @auth
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                        class="text-white bg-red-600 border-white border hover:bg-blue-30tme_subscription_items0 hover:text-white px-3 py-1 rounded-md text-sm font-medium">
                                    Log out
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center sm:hidden">
                <!-- Mobile menu button-->
                <button class="inline-flex items-center justify-center p-2 rounded-md border border-red-100 bg-white text-red-800 hover:text-white hover:bg-red-300 " aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="red" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 text-right">
            <a href="/" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Public site</a>

            <a href="/admin/venues" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Venues</a>

            <a href="/admin/trials" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Trials</a>

            <a href="/admin/results" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Results</a>

            <a href="/admin/users" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Users</a>
            
            @auth
                <form method="POST" action="/logout">
                    @csrf
                    <div class="flex justify-end">
                        <button type="submit"
                                class="text-white hover:bg-blue-300  hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                            Log out
                        </button>
                    </div>
                </form>
            @endauth
            @guest
                <a href="/login" class="text-white hover:bg-red-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Login</a>
            @endguest
        </div>
    </div>
</nav>
