<x-admin>
    <x-slot:heading>Products</x-slot:heading>
    <div class=" mt-0 mb-4  bg-white border-1 border-gray-400 rounded-xl  outline outline-1 -outline-offset-1 drop-shadow-lg outline-gray-300 pb-2">
        <div class="flex font-semibold justify-between w-full pt-2 pb-2 pl-4 pr-4 rounded-t-xl  text-white bg-red-600">
            <div>Product List</div>
            <div><a href="/admin/product/add">Add new…</a></div></div>

        <div class="pl-2 pr-2 w-full ">
            <table class="table-auto w-full">
                <thead class="">
                <th>Item</th>
                <th>ClubID</th>
                <th>TrialID</th>
                <th>Category</th>
                <th>Options</th>
                <th>Purchases</th>
                </thead>
                @foreach($products as $product)
                    <tr class="">
                        <td>{{$product->product_name}}</td>
                        <td class="text-right pr-2">{{$product->club_id}}</td>
                        <td class="text-right pr-2">{{$product->trial_id}}</td>
                        <td>{{$product->product_category}}</td>
                        <td>{{$product->options}}</td>
                        <td class="text-right pr-4">{{$product->purchases}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
</x-admin>