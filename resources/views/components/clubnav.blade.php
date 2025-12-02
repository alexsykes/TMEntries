<nav class="bg-violet-800">
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-16">
            <div class="flex-1 flex items-center justify-start sm:items-stretch sm:justify-between">
                <div class="flex-shrink-0">
                    <a href="#" class="text-white text-lg font-bold">{{$heading}}</a>
                </div>
                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-4">
                        @if(Auth::user()->isClubUser)
                            <a href="/"
                               class="text-white bg-violet-700 border-white border  hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Public
                                site</a>

                            <a href="/club/console"
                               class="text-white bg-violet-700 border-white border  hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Console</a>
                            <a href="/clubaccess"
                               class="text-white bg-violet-700 border-white border  hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Trials</a>
                            <a href="/club/mails"
                               class="text-white bg-violet-700 border-white border hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Mail</a>
                            <a href="/club/member/list"
                               class="text-white bg-violet-700 border-white border hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Membership</a>
                        @endif
                        @auth
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                        class="text-white bg-violet-700 border-white border hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">
                                    Log out
                                </button>
                            </form>
                        @endauth
                        @guest
                            <a href="/login"
                               class="text-white bg-violet-700 border-white border hover:bg-violet-300 hover:text-white px-3 py-1 rounded-md text-sm font-medium">Login</a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center sm:hidden">
                <!-- Mobile menu button-->
                <button class="inline-flex items-center justify-center p-2 rounded-md border border-violet-100 bg-white text-violet-800 hover:text-white hover:bg-violet-300 "
                        aria-controls="mobile-menu" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16m-7 6h7"/>
                    </svg>
                    <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="hidden sm:hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-2 text-right">
            @if(Auth::user()->isAdminUser)
                <a href="/adminaccess"
                   class="text-white hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Admin
                    Access</a>
            @endif
            @if(Auth::user()->isClubUser)
                <a href="/"
                   class="text-white hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Public
                    site</a>
                <a href="/club/console"
                   class="text-white  hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Profile</a>
                <a href="/clubaccess"
                   class="text-white hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Trials</a>
                <a href="/club/mails"
                   class="text-white hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Mail</a>
                <a href="/club/member/list"
                   class="text-white hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Membership</a>
            @endif
            @auth
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                            class="w-full text-end text-white hover:bg-violet-300  hover:text-white block px-3 py-2 rounded-md text-base font-medium">
                        Log out
                    </button>
                </form>
            @endauth
            @guest
                <a href="/login"
                   class="text-white hover:bg-violet-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium">Login</a>
            @endguest
        </div>
    </div>
</nav>
