<x-admin>

    <x-slot:heading>New App User</x-slot:heading>
    <form action="/admin/create" method="post">
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            @csrf
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">Details</div>
            <div class="grid grid-cols-2 gap-4 px-4">
                <x-form-field>
                    <x-form-label for="username">Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="username" type="text" id="username" value=""
                                      placeholder="Name" required/>
                        <x-form-error name="username"/>
                    </div>
                    @error('username')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="email" type="email" id="email" value=""
                                      placeholder="Email address" required/>
                        <x-form-error name="email"/>
                    </div>
                    @error('email')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="password">Password</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="password" type="text" id="password" value=""
                                      placeholder="Password" required/>
                        <x-form-error name="password"/>
                    </div>
                    @error('password')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>


            </div>
        </div>

        <div id="buttons" class="py-2">
            <a href="/admin/users"
               class=" rounded-md bg-white px-3 py-2 text-sm  drop-shadow-lg text-red-900 shadow-sm hover:bg-red-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-900">Cancel</a>
            <button type="submit"
                    class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-900 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                Save
            </button>
        </div>
    </form>
</x-admin>