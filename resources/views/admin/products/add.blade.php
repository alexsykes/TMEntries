<x-admin>
    <x-slot:heading>New Product</x-slot:heading>
    <div class=" mt-0 mb-4  bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex font-semibold justify-between w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">
            <div>Product Details</div>
        </div>
        <form method="post" action="/admin/product/store">
            @csrf
            <div class="grid grid-cols-2 gap-4 px-4">
                <x-form-field>
                    <x-form-label for="product_name">Product Name</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="product_name" type="text" id="product_name" value=""
                                      placeholder="Product Name" required/>
                        <x-form-error name="product_name"/>
                    </div>
                    @error('product_name')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>


                <x-form-field>
                    <x-form-label for="product_description">Description</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="product_description" type="text" id="product_description" value=""
                                      placeholder="Stripe Product Description" required/>
                        <x-form-error name="product_description"/>
                    </div>
                    @error('product_description')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="price">Price</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="price" type="text" id="price" value=""
                                      placeholder="Price - omit £ eg. 5.99" required/>
                        <x-form-error name="price"/>
                    </div>
                    @error('price')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label for="trial_id">Trial ID</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="trial_id" type="text" id="trial_id" value=""
                                      placeholder="Trial ID - leave empty if none"/>
                        <x-form-error name="trial_id"/>
                    </div>
                    @error('trial_id')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

                <x-form-field>
                    <x-form-label class="pb-2" for="club_id">Club</x-form-label>

                    <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                        <div class="pb-2 pt-2 bg-white sm:col-span-2">
                            <select class="ml-2  bg-white  space-x-4 border-none" name="club_id"
                                    id="club_id"
                                    required>

                                <option value="0">Select a club</option>
                                @foreach($clubs as $club)
                                    <option value="{{strtolower($club->id)}}">{{$club->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-form-field>

                <x-form-field>
                    <x-form-label class="pb-2" for="product_category">Category</x-form-label>

                    <div class="flex max-w-80  items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 drop-shadow-lg outline-blue-700 ">
                        <div class="pb-2 pt-2 bg-white sm:col-span-2">
                            <select class="ml-2  bg-white  space-x-4 border-none" name="product_category"
                                    id="product_category"
                                    required>
                                @foreach($categories as $product_category)
                                    <option value="{{strtolower($product_category)}}">{{$product_category}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </x-form-field>

                <x-form-field>
                    <div class="flex justify-normal col-span-3">
                        <div class="font-semibold text text-blue-700">Youth
                        </div>
                        <div class="pl-2">
                            <input name="isYouth" type="checkbox" value="1"/>
                        </div>
                    </div>
                </x-form-field>

                <x-form-field>
                    <div class="flex justify-normal col-span-3">
                        <div class="font-semibold text text-blue-700">Optional
                        </div>
                        <div class="pl-2">
                            <input name="optional" type="checkbox" value="1"/>
                        </div>
                    </div>
                </x-form-field>

                <x-form-field>
                    <div class="flex justify-normal col-span-3">
                        <div class="font-semibold text text-blue-700">Has Quantity
                        </div>
                        <div class="pl-2">
                            <input name="hasQuantity" type="checkbox" value="1"/>
                        </div>
                    </div>
                </x-form-field>


                <x-form-field>
                    <x-form-label for="options">Options (Comma separated list)</x-form-label>
                    <div class="mt-2 col-span-2">
                        <x-form-input name="options" type="text" id="options" value=""
                                      placeholder="Product options"/>
                        <x-form-error name="options"/>
                    </div>
                    @error('options')
                    <p class="text-xs text-red-500 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </x-form-field>

            </div>
            <div class="mt-4 ml-2 " id="buttons">
                <button type="submit"
                        class="rounded-md ml-2 bg-red-600 px-3 py-1 text-sm font-light  border border-red-800 text-white drop-shadow-lg hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">
                    Add Product
                </button>
            </div>

        </form>
    </div>
</x-admin>
