<div>
    <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Product Item Locations') }}
        </h2>
        <x-wui-button label="New" @click="$openModal('newModal')" />
    </div>



    <div class="px-12 mt-2 overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Category Name
                    </th>

                    <th scope="col" class="px-6 py-3">
                        Description
                    </th>

                    <th scope="col" class="px-6 py-3 sr-only">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($locations as $location)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $location->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $location->description }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td>There's no records</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

    {{-- New modal  --}}
    <x-wui-modal-card title="New Category" name="newModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-input label="Name" wire:model='name' placeholder="eg. Cover" />

            <div class="col-span-1 sm:col-span-2">
                <x-wui-input label="Description" wire:model='description' placeholder="description" />
            </div>
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Delete" x-on:click="$closeModal('newModal')" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" wire:click="createLocation" />
            </div>
        </x-slot>
    </x-wui-modal-card>
</div>
<script>
    Livewire.on('closeModal', (name) => {
        $closeModal(name);
    });
</script>
