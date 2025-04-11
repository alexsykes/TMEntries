<nav class="bg-blue-800">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="flex-1 flex items-center justify-start sm:items-stretch sm:justify-between">
                <div class="flex-shrink-0">
                    <a href="#" class="text-white text-lg font-bold">{{$heading}}</a>
                </div>
                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4">
                        <a href="/adminaccess" class="text-white bg-blue-700 border-white border  hover:bg-blue-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Users</a>
                        <a href="/admin/sendMail" class="text-white bg-blue-700 border-white border hover:bg-blue-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Email</a>
                        @auth
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                        class="text-white bg-blue-700 border-white border hover:bg-blue-30tme_subscription_items0 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                                    Log out
                                </button>
                            </form>
                        @endauth
                        @guest
                            <a href="/login" class="text-white bg-blue-700 border-white border hover:bg-blue-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center sm:hidden">
                <!-- Mobile menu button-->
                <button class="inline-flex items-center justify-center p-2 rounded-md border border-blue-100 bg-white text-blue-800 hover:text-white hover:bg-blue-300 " aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
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
            <a href="/" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Coming up…</a>
            <a href="/results/list" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Results</a>
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
                <a href="/login" class="text-white hover:bg-blue-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Login</a>
            @endguest
        </div>
    </div>
</nav>
