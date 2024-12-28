<div>
    <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Contact') }}
        </h2>
        <x-wui-button label="New" @click="$openModal('newModal')" />
    </div>

    <table class="w-full mt-3 text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Voucher Number
                </th>
                <th scope="col" class="px-6 py-3">
                    Customer Name
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
            @forelse ($customers as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $item->phone }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->address }}
                    </td>
                    <td class="px-6 py-4">
                        <x-wui-button teal label="Edit" wire:click='initializeId({{ $item->id }})' />`
                    </td>
                </tr>
            @empty
                <tr class="py-4">
                    <td>There's no records yet!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Create customer  --}}
    <x-wui-modal-card title="Contant အသစ်တည်ဆောက်ပါ" name="newModal">
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

    {{-- Edit customer  --}}
    <x-wui-modal-card title="Contant ကိုပြင်ဆင်ပါ" name="editModal">
        <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2">
            <x-wui-input label="Name" wire:model='edit_name' placeholder=" eg. Kyan Sit Thar" />
            <x-wui-phone label="phone" wire:model='edit_phone' placeholder=" eg. 0978654483" />
        </div>


        <div class="col-span-1 sm:col-span-2">
            <x-wui-textarea label="Address" wire:model='edit_address' placeholder="Address" />
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Delete" x-on:click="$closeModal('editModal')" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Update" wire:click="updateCustomer" />
            </div>
        </x-slot>
    </x-wui-modal-card>
</div>


<script>
    Livewire.on('closeModal', (name) => {
        $closeModal(name);
    });

    Livewire.on('openModal', (name) => {
        $openModal(name);
    });
</script>
