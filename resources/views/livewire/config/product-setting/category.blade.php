<div>
    <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Category') }}
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
                        Code
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
                @forelse ($categories as $category)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $category->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $category->code }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $category->description }}
                        </td>
                        <td class="px-6 py-4">
                            <x-wui-button label="edit" wire:click='edit({{ $category->id }})'
                                @click="$openModal('editModal')" />

                            <x-danger-button x-on:click.prevent="$dispatch('open-modal', 'confirm-category-delete')"
                                wire:click='setDeleteId({{ $category->id }})'>
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
        <div class="p-4">{{ $categories->links() }}</div>
    </div>

    {{-- New modal  --}}
    <x-wui-modal-card title="New Category" name="newModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-input label="Name" wire:model='name' placeholder="eg. Cover" />

            <x-wui-input label="Code" wire:model='code' placeholder=" eg. C" />

            <div class="col-span-1 sm:col-span-2">
                <x-wui-input label="Description" wire:model='description' placeholder="description" />
            </div>


        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Delete" x-on:click="$closeModal('newModal')" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" wire:click="createCategory" />
            </div>
        </x-slot>
    </x-wui-modal-card>

    {{-- Edit modal  --}}
    <x-wui-modal-card title="Edit Category" name="editModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-input label="Name" wire:model='up_name' placeholder="eg. Cover" />

            <x-wui-input label="Code" wire:model='up_code' placeholder=" eg. C" />

            <div class="col-span-1 sm:col-span-2">
                <x-wui-input label="Description" wire:model='up_description' placeholder="description" />
            </div>
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="Delete" x-on:click="$closeModal('editModal')" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" wire:click="updateCategory" />
            </div>
        </x-slot>
    </x-wui-modal-card>

    {{-- Delete confirm modal --}}
    <x-modal name="confirm-category-delete" :show="$errors->isNotEmpty()" focusable>
        <form wire:submit="delete" class="p-6">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete  Category?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your category is deleted, all of its porducts and data will be permanently deleted.') }}
            </p>

            <div class="flex justify-end mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Category') }}
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
