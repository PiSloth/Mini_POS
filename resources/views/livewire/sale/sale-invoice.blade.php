<div>
    <div class="flex h-16 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('New Invoice') }}
        </h2>
        <div class="text-sm">
            <select wire:model.live='branch_id' required id="branch_name"
                class="text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                <option value="" disabled>Shop</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 p-4 md:grid-cols-2">
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
                                <x-add-button
                                    wire:click="addToCart({{ $product->id }},'{{ $product->name }}','{{ $product->code }}',{{ $product->price }})">add</x-add-button>
                            </td>
                        </tr>
                    @empty
                        <center>Choose a Product</center>
                    @endforelse
                </tbody>
            </table>
            {{-- <div>{{ $products->links() }}</div> --}}
        </div>

        <div class="p-4 border-2 border-teal-100 rounded-lg">
            <center>
                <x-application-logo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
                ငွေရပြေစာ
            </center>
            @if ($customer)
                <table>
                    <thead>
                        <tr>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4 sr-only">Desc</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customer as $data)
                            <tr>
                                <td class="px-6 py-2">Name</td>
                                <td class="px-6 py-2">{{ $data['name'] }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-2">Phone</td>
                                <td class="px-6 py-2">{{ $data['phone'] }}</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-2">Address</td>
                                <td class="px-6 py-2">{{ $data['address'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <x-add-button x-on:click="$openModal('newModal')">add customer</x-add-button>
            @endif
            <table class="w-full mt-3 text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Code
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Qty
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
                    @forelse ($cart as $id => $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item['name'] }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $item['code'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item['quantity'] }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item['price'] }}
                            </td>
                        </tr>

                    @empty
                        <tr class="py-4">
                            <td>There's no records yet!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <x-primary-button wire:click='createVoucher'>save</x-primary-button>
        </div>


        {{-- New modal  --}}
        <x-wui-modal-card title="Choose a Customer" name="newModal">
            <div class="p-4 my-4 border rounded-lg border-teal-950">
                <span class="text-lg text-gray-500 ">ရှာဖွေရန်</span>

                <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2">
                    <x-wui-select wire:model='customer_id' label="Name or Phone" placeholder="eg ,Kyan Sit Thar"
                        :async-data="route('api.contact')" option-label="name" option-value="id" />
                </div>
            </div>
            <span class="mb-2 text-lg text-gray-500">အသစ်ပြုလုပ်ပါ</span>
            <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2">
                <x-wui-input label="Name" wire:model='name' placeholder=" eg. Kyan Sit Thar" />
                <x-wui-phone label="phone" wire:model='phone' placeholder=" eg. 0978654483" />
            </div>


            <div class="col-span-1 sm:col-span-2">
                <x-wui-textarea label="Address" wire:model='address' placeholder="Address" />
            </div>

            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-wui-button flat negative label="Delete" x-on:click="$closeModal('newModal')" />

                <div class="flex gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" />

                    <x-wui-button primary label="Save" wire:click="createCustomer" />
                </div>
            </x-slot>
        </x-wui-modal-card>
    </div>
</div>

<script>
    Livewire.on('closeModal', (name) => {
        $closeModal(name);
    });
    Livewire.on('openModal', (name) => {
        $openModal(name);
    });
</script>
