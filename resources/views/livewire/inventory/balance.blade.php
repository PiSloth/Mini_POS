<div>
    <table class="w-full mt-3 text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Product
                </th>
                <th scope="col" class="px-6 py-3">
                    Price
                </th>
                <th scope="col" class="px-6 py-3">
                    Stock Quantity
                </th>
                <th scope="col" class="px-6 py-3">
                    Branch
                </th>
                <th scope="col" class="px-6 py-3 sr-only">
                    Action
                </th>

            </tr>
        </thead>
        <tbody>
            @forelse ($stocks as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->product->name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $item->price }} <small>ks</small>
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->quantity }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->branch->name }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-2">
                            <x-add-button class="h-6 " wire:click='initialStockId({{ $item->id }})'
                                @click="$openModal('stockAdjustmentModal')">Adjusment</x-add-button>
                            <x-add-button class="h-6 " wire:click='initialStockId({{ $item->id }})'
                                @click="$openModal('itemLocationModal')">Locate</x-add-button>
                            <x-secondary-button class="h-6 " wire:click='initialStockId({{ $item->id }})'
                                @click="$openModal('transferHistoryModal')">
                                <x-wui-icon name="clock" class="w-4 h-4" />
                            </x-secondary-button>

                            {{-- <x-secondary-button class="h-6" wire:click='initialStockId({{ $item->id }})'
                                @click="$openModal('transferHistoryModal') >
                                <x-wui-icon name="clock"
                                class="w-4 h-4" />
                            </x-secondary-button> --}}
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="py-4">
                    <td>There's no records yet!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- item location creation modal  --}}
    <x-wui-modal-card title="Item Location Define/ ပစ္စည်းများ အသေးစိတ် နေရာ သတ်မှတ်ပါ" name="itemLocationModal">
        <div class="col-span-1 mb-3 sm:col-span-2">
            <x-wui-select wire:model='item_location_id' label="တည်နေရာ" placeholder="search" :async-data="route('api.item-location')"
                option-label="location" option-value="id" />
        </div>
        <span class="text-red-500">မှတ်ချက် > တည်နေရာသတ်မှတ်ခြင်းသည် Configuration ထဲမှ "Item Location"
            တွင်ထည့်သွင်းနိုင်သည်။</span>
        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Delete" x-on:click="$closeModal('itemLocationModal')" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" wire:click="createItemLocation" />
            </div>
        </x-slot>
    </x-wui-modal-card>

    {{-- Stock Adjustment  --}}
    <x-wui-modal-card title="Stock Adjustment/ ပစ္စည်းလက်ကျန် အလျော့အတင်းပြုလုပ်ရန်" name="stockAdjustmentModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-input type="number" label="Quantity" wire:model='quantity' placeholder="eg. 23" />

            <div class="col-span-1 sm:col-span-2">
                <x-wui-textarea label="Description" wire:model='remark' placeholder="Write a note" />
            </div>
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Reduce" wire:click='reduceStock' />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />
                <x-wui-button primary label="Increase" wire:click="increaseStock" />
            </div>
        </x-slot>
    </x-wui-modal-card>

    {{-- Transfer History  --}}
    <x-wui-modal-card title="ပစ္စည်းအဝင်/အထွက် မှတ်တမ်း" name="transferHistoryModal">
        <table class="w-full mt-3 text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Remark
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Quantity
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Stock in/out
                    </th>
                    <th scope="col" class="px-6 py-3">
                        တာဝန်ခံ
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($histories as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item->remark }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->quantity ? 'အဝင်' : 'အထွက်' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->user->name }}
                        </td>
                    </tr>
                @empty
                    <tr class="py-4">
                        <td>There's no records yet!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />
            </div>
        </x-slot>
    </x-wui-modal-card>
</div>
<script>
    Livewire.on('closeModal', (name) => {
        $closeModal(name);
    });
</script>
