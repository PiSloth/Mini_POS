<div class="grid grid-cols-2 gap-4">
    <div>
        <x-wui-input wire:model.live='search' label="Choose a Product"
            placeholder="Find a Product with [Category] or [Code] or [Name]" />
        <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Code
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>

                    <th scope="col" class="px-6 py-3 sr-only">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $product->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $product->code }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->price }}
                        </td>
                        <td class="px-6 py-4">
                            <button
                                wire:click="cart({{ $product->id }},{{ $product->name }},{{ $product->code }},{{ $product->price }})">add</button>
                        </td>

                    </tr>

                @empty
                    <center>Choose a Product</center>
                @endforelse


            </tbody>
        </table>
    </div>
    <div class="p-4 border-2 border-teal-100 rounded-lg">
        <center>
            <x-application-logo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
            ငွေရပြေစာ
        </center>
        <table>
            <thead>
                <tr>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4 sr-only">Desc</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="px-6 py-2">Name</td>
                    <td class="px-6 py-2">Aung Ba</td>
                </tr>
                <tr>
                    <td class="px-6 py-2">Ph no</td>
                    <td class="px-6 py-2">0 993 838 834</td>
                </tr>
                <tr>
                    <td class="px-6 py-2">Address</td>
                    <td class="px-6 py-2">Mandalay, Chanmyatharzi, 33,323x343 B Quarter</td>
                </tr>
            </tbody>
        </table>
        <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Code
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3 sr-only">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($cart as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item['name'] }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $item['code'] }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item['price'] }}
                        </td>

                    </tr>

                @empty
                @endforelse
                {{--
                    <tr>
                        <td>There's no records</td>
                    </tr> --}}


            </tbody>
        </table>
    </div>
</div>
