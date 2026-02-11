<x-main>
    <x-slot:heading>
        Register for {{$trial->name}}
    </x-slot:heading>

    <form action="/entries/create" method="post">
        @csrf
        <input type="hidden" name="trialID" id="trialID" value="{{$trial->id}}">
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 grid columns-1 sm:columns-2 ">

            <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">
                Personal Details
            </div>

            <div class=" pl-4 border grid gap-4 grid-cols-1 sm:grid-cols-2 p-4">
                <div class="col-span-1"><label for="firstname">First name</label>
                    <x-form-input type="firstname" name="firstname" label="First name"/>
                </div>

                <div class="col-span-1"><label for="lastname">Last name</label>
                    <x-form-input type="lastname" name="lastname" label="Last name"/>
                </div>

                <div class="col-span-1"><label for="email">Email</label>
                    <x-form-input type="email" name="email" label="Email"/>
                </div>

                <div class="col-span-1"><label for="phone">Phone</label>
                    <x-form-input type="phone" name="phone" label="Phone"/>
                </div>

                <div class="col-span-1"><label for="address">Address</label>
                    <x-form-input type="address" name="address" label="Address"/>
                </div>

                <div class="col-span-1"><label for="postcode">Postcode</label>
                    <x-form-input type="postcode" name="postcode" label="Postcode"/>
                </div>
            </div>
        </div>
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 grid columns-1 sm:columns-2 ">

            <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">
                Entry Details
            </div>

            <div class=" pl-4 border grid gap-4 grid-cols-1 sm:grid-cols-2 p-4">
                <div class="col-span-1"><label for="make">Make</label>
                    <x-form-input type="make" name="make" label="Make"/>
                </div>

                <div class="col-span-1"><label for="size">Capacity</label>
                    <x-form-input type="size" name="size" label="Capacity"/>
                </div>

{{--                <div class="col-span-1"><label for="type">Type</label>--}}
{{--                    <x-form-input type="radio" name="type" label="Type"/>--}}
{{--                </div>--}}

                <div class="col-span-1"><label for="course">Course</label>
                    <x-form-input type="course" name="course" label="Course"/>
                </div>
                <div class="col-span-1"><label for="class">Class</label>
                    <x-form-input type="class" name="class" label="Class"/>
                </div>
            </div>
        </div>
        <div class=" mt-4 bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 grid columns-1 sm:columns-2 ">

            <div class=" font-bold w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-blue-600">
                Extras
            </div>

            <div class=" pl-4 border grid gap-4 grid-cols-1 sm:grid-cols-2 p-4">
                <div class="col-span-1"><label for="firstname">First name</label>
                    <x-form-input type="firstname" name="firstname" label="First name"/>
                </div>

                <div class="col-span-1"><label for="lastname">Last name</label>
                    <x-form-input type="lastname" name="lastname" label="Last name"/>
                </div>

                <div class="col-span-1"><label for="email">Email</label>
                    <x-form-input type="email" name="email" label="Email"/>
                </div>

                <div class="col-span-1"><label for="phone">Phone</label>
                    <x-form-input type="phone" name="phone" label="Phone"/>
                </div>
                <div class="col-span-1"><label for="address">Address</label>
                    <x-form-input type="address" name="address" label="Address"/>
                </div>
            </div>
        </div>

    </form>
</x-main>