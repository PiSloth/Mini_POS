<div>
    <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Invoicing Detail') }}
        </h2>
        @if ($status == 'new')
            <x-primary-button @click="$openModal('paymentModal')">confirm</x-primary-button>
        @endif
        {{-- @if ($status == 'confirmed')
            <x-primary-button payment</x-primary-button>
        @endif --}}
    </div>
    <div class="p-2">
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
                    @foreach ($customer_info as $info)
                        <tr>
                            <td class="px-6 py-2">{{ $info['type'] }}</td>
                            <td class="px-6 py-2">{{ $info['detail'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

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
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 sr-only">
                            Action
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $id => $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item->branchProduct->product->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $item->branchProduct->product->code }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->price }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->total }}
                            </td>
                            <td class="px-6 py-4">

                            </td>
                        </tr>
                    @empty
                        <tr class="py-4">
                            <td>There's no records yet!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Payment modal  --}}
    <x-wui-modal-card title="Payemnt Terms ရွေးချယ်ပါ" name="paymentModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-select wire:model='customer_id' label="Main Group" placeholder="eg ,Kyan Sit Thar" :async-data="route('api.contact')"
                option-label="name" option-value="id" />
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Delete" x-on:click="$closeModal('paymentModal')" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" />
            </div>
        </x-slot>
    </x-wui-modal-card>
</div>
<script>
    Livewire.on('closeModal', (name) => {
        $closeModal(name);
    });
</script>
