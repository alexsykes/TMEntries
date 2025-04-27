<x-admin>
    <x-slot:heading>Editing UserID: {{$user->id}} - {{$user->name}} ({{$user->email}})</x-slot:heading>
    <form method="POST" action="/admin/updateUser">
        @method('PATCH')
        <input type="hidden" name="id" value="{{$user->id}}">
        @csrf
        <div class=" mt-0 mb-4  bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
            <div class="font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">&nbsp;</div>

            <div class="pl-4 space-y-4">
                <x-form-field>
                    <x-form-label for="name">Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="name" type="text" id="name" value="{{$user->name}}"
                                      placeholder="Name" required/>
                        <x-form-error name="name"/>
                    </div>
                    @error('name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="email">Email</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="email" type="text" id="email" value="{{$user->email}}"
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
                        <x-form-input name="password" type="password" id="password" value=""
                                      placeholder="New password"/>
                        <x-form-error name="password"/>
                    </div>
                    @error('password')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="password_confirm">Confirm password</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="password_confirm" type="password" id="password_confirm" value=""
                                      placeholder="Confirm password" />
                        <x-form-error name="password_confirm"/>
                    </div>
                    @error('password_confirm')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <div class="flex justify-normal align-middle space-x-4 mt-2 col-span-2">
                        <div>
                            <x-form-label for="isClubUser">Club user</x-form-label>
                        </div>
                        <div class="">
                            <input name="isClubUser" type="checkbox" id="isClubUser" value="1"

                                    @php
                                        if($user->isClubUser) { echo "checked";}
                                    @endphp

                            />
                            <x-form-error name="isClubUser"/>
                        </div>
                    </div>
                    @error('isSuperUser')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
                <x-form-field>
                    <div class="flex justify-normal align-middle space-x-4 mt-2 col-span-2">
                        <div>
                            <x-form-label for="isAdminUser">Admin user</x-form-label>
                        </div>
                        <div class="">
                            <input name="isAdminUser" type="checkbox" id="isAdminUser" value="1"

                                    @php
                                        if($user->isAdminUser) { echo "checked";}
                                    @endphp

                            />
                            <x-form-error name="isAdminUser"/>
                        </div>
                    </div>
                    @error('isSuperUser')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
                <x-form-field>
                    <div class="flex justify-normal align-middle space-x-4 mt-2 col-span-2">
                        <div>
                            <x-form-label for="isSuperUser">Super user</x-form-label>
                        </div>
                        <div class="">
                            <input name="isSuperUser" type="checkbox" id="isSuperUser" value="1"

                                    @php
                                        if($user->isSuperUser) { echo "checked";}
                                    @endphp

                            />
                            <x-form-error name="isSuperUser"/>
                        </div>
                    </div>
                    @error('isSuperUser')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>
            </div>

        </div>
        <div class="mt-4" id="buttons">
            <a href="/adminaccess"
               class="rounded-md bg-white px-3 py-2 text-sm  text-blue-600 shadow-sm hover:bg-blue-900 hover:text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-900">Cancel</a>

            <button type="submit"
                    class="rounded-md ml-2 bg-blue-600 px-3 py-1 text-sm font-light  border border-blue-800 text-white drop-shadow-lg hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                Update
            </button>
        </div>
    </form>
</x-admin>