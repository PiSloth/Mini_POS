<div>
    <div>
        <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('New Shop') }}
            </h2>
            <x-wui-button label="New" @click="$openModal('newModal')" />
            @if ($edit_id)
                <x-wui-button label="Cancle edit" wire:click='cancleEdit' negative />
            @endif
        </div>



        <div class="px-12 mt-2 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Address
                        </th>

                        <th scope="col" class="px-6 py-3 sr-only">
                            Action
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @forelse ($branches as $branch)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $branch->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $branch->address }}
                            </td>

                            <td class="px-6 py-4">
                                <x-wui-button label="edit" wire:click='edit({{ $branch->id }})' />
                                <x-danger-button x-on:click.prevent="$dispatch('open-modal', 'confirm-branch-delete')"
                                    wire:click='setDelete({{ $branch->id }})'>
                                    delete</x-danger-button>
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
                <x-wui-input label="Name" wire:model='name' placeholder="eg. Humburger" />

                <x-wui-input label="Address" wire:model='address' placeholder=" eg . Hlaetan.Yangon" />

            </div>

            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-wui-button flat negative label="Delete" x-on:click="$closeModal('newModal')" />

                <div class="flex gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" wire:click='cancleEdit' />
                    @if ($edit_id)
                        <x-wui-button primary label="Update" wire:click="update" />
                    @else
                        <x-wui-button primary label="Save" wire:click="create" />
                    @endif
                </div>
            </x-slot>
        </x-wui-modal-card>

        <x-modal name="confirm-branch-delete" :show="$errors->isNotEmpty()" focusable>
            <form wire:submit="delete" class="p-6">

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete Sub Category?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once your category is deleted, all of its porducts and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete this category.') }}
                </p>

                <div class="flex justify-end mt-6">
                    <x-secondary-button wire:click='cancleDelete' x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button class="ms-3">
                        {{ __('Delete Sub-category') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        Livewire.on('closeModal', (name) => {
            $closeModal(name);
        });

        Livewire.on('openModal', (name) => {
            $openModal(name);
        });
    </script>
</div>
