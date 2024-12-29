<div>
    <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Product') }}
        </h2>
        <x-wui-button label="New" @click="$openModal('newModal')" />
        @if ($edit_id)
            <x-wui-button label="Cancle edit" wire:click='clearEditId' negative />
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
                        Code
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Category
                    </th>
                    {{-- <th scope="col" class="px-6 py-3">
                        Price
                    </th> --}}
                    <th scope="col" class="px-6 py-3">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 sr-only">
                        Action
                    </th>

                </tr>
            </thead>
            <tbody>
                @forelse ($products as $data)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $data->name }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $data->code }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $data->subCategory->category->name }} / {{ $data->subCategory->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $data->description }}
                        </td>
                        <td class="px-6 py-4">
                            <x-wui-button label="edit" wire:click='edit({{ $data->id }})' />

                            <x-primary-button wire:click='branchHistories({{ $data->id }})'
                                @click="$openModal('newLocateModal')">Locate</x-primary-button>

                            {{-- <x-danger-button x-on:click.prevent="$dispatch('open-modal', 'confirm-category-delete')"
                                wire:click='setDeleteId({{ $data->id }})'>
                                delete</x-danger-button> --}}
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
    <x-wui-modal-card title="New Product" name="newModal">
        <form x-data="{
            show: true,
            preview: null,
            name: null,
            files: [],
            handleFile(event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result
                        reader.readAsDataURL(file);
                        this.show = false
                    } else {
                        alert('Please select a valid image file.');
                    }
                }
            },
        
            removeFile() {
                this.preview = null,
                    this.show = true
            },
        }">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <select wire:model='branch_id'
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="" disabled>Shop</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>

                <div class="col-span-1 sm:col-span-2">
                    <x-wui-select wire:model='sub_category_id' label="Main Group" placeholder="search" :async-data="route('api.sub-category')"
                        option-label="sub_category" option-value="id" />
                </div>

                <x-wui-input label="Name" wire:model='name' placeholder="eg. Cover" />

                <x-wui-input label="Code" wire:model='code' placeholder=" eg. C" />

                <div class="col-span-1 sm:col-span-2">
                    <x-wui-input label="Description" wire:model='description' placeholder="description" />
                </div>

                <x-wui-currency label="Price" thousands="," prefix="MMK" wire:model='price' />
                <div class="col-span-1 sm:col-span-2">
                    <div x-show="show" x-data @click="$refs.fileInput.click()"
                        class="flex items-center justify-center h-64 col-span-1 bg-gray-100 shadow-md cursor-pointer group cursor-porinter sm:col-span-2 dark:bg-secondary-700 rounded-xl">
                        <div class="flex flex-col items-center justify-center">
                            <x-wui-icon name="cloud-arrow-up"
                                class="w-16 h-16 text-blue-600 group-hover:text-blue-900 dark:text-teal-600" />
                            <p class="text-blue-600 group-hover:text-blue-900 dark:text-teal-600">Click or drop files
                                here
                            </p>
                        </div>
                    </div>
                    <!-- Hidden file input -->
                    <input wire:model="product_image" id="image" accept="image/jpeg,image/jpg" type="file"
                        x-ref="fileInput" class="hidden" @change="handleFile($event)" />
                </div>

                <template x-if="preview">
                    <div class="relative group">
                        <!-- Image Box -->
                        <img x-bind:src="preview" alt="product name"
                            class="object-cover w-full h-32 rounded-lg" />

                        <!-- Close Button -->
                        <button @click="removeFile"
                            class="absolute p-1 text-white transition bg-red-500 rounded-full opacity-0 top-2 right-2 group-hover:opacity-100"
                            title="Remove image">
                            &times;
                        </button>
                    </div>
                </template>


            </div>

            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-wui-button flat negative label="Delete" x-on:click="$closeModal('newModal')" />

                <div class="flex gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" wire:click='clearEditId' />

                    <x-primary-button wire:click='create'>save</x-primary-button>

                </div>
            </x-slot>
        </form>
    </x-wui-modal-card>

    {{-- add new locate branch  --}}
    <x-wui-modal-card title="Branch Located" name="newLocateModal">
        <form x-data="{
            show: true,
            preview: null,
            name: null,
            files: [],
            handleFile(event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result
                        reader.readAsDataURL(file);
                        this.show = false
                    } else {
                        alert('Please select a valid image file.');
                    }
                }
            },
        
            removeFile() {
                this.preview = null,
                    this.show = true
            },
        }">
            <div class="mb-3">
                <span class="text-lg text-gray-400">ရောက်ရှိပြီး ဆိုင်ခွဲများ</span>
                <ul class="mt-3">
                    @foreach ($located_branches as $branch)
                        <li class="text-blue-400">
                            # {{ $branch }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-span-1 my-2 sm:col-span-2">
                <img src='{{ asset('storage/' . $up_product_image) }}' alt="product name"
                    class="object-cover w-48 h-48 rounded-lg" />
                <div class="relative group">
                    <!-- Image Box -->
                    {{-- @dump($up_product_image) --}}

                    <!-- Close Button -->
                    {{-- <button @click="removeFile"
                        class="absolute p-1 text-white transition bg-red-500 rounded-full opacity-0 top-2 right-2 group-hover:opacity-100"
                        title="Remove image">
                        &times;
                    </button> --}}
                </div>
            </div>

            <span class="text-lg text-gray-400">ပစ္စည်းထားမည့် ဆိုင်ခွဲအသစ်</span>
            <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2">
                <div>
                    <label for="branch_name">ဆိုင်ခွဲ အမည်</label>
                    <div class="mt-1">
                        <select wire:model='branch_id' required id="branch_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option value="" disabled>Shop</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <x-wui-currency label="Price" thousands="," prefix="MMK" wire:model='price' />
            </div>

            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-wui-button flat negative label="Delete" x-on:click="$closeModal('newLocateModal')" />

                <div class="flex gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" />

                    <x-primary-button wire:click='newBranchLocate'>save</x-primary-button>

                </div>
            </x-slot>
        </form>
    </x-wui-modal-card>

    {{-- edit modal  --}}
    <x-wui-modal-card title="Edit Product" name="editModal">
        <form x-data="{
            show: true,
            preview: null,
            name: null,
            files: [],
            handleFile(event) {
                const file = event.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.preview = e.target.result
                        reader.readAsDataURL(file);
                        this.show = false
                    } else {
                        alert('Please select a valid image file.');
                    }
                }
            },
        
            removeFile() {
                this.preview = null,
                    this.show = true
            },
        }">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div class="col-span-1 sm:col-span-2">
                    <x-wui-select wire:model.live='up_sub_category_id' label="Main Group" placeholder="search"
                        :async-data="route('api.sub-category')" option-label="sub_category" option-value="id" />
                </div>

                <x-wui-input label="Name" wire:model.live='up_name' placeholder="eg. Cover" />

                <x-wui-input label="Code" wire:model.live='up_code' placeholder=" eg. C" />

                <div class="col-span-1 sm:col-span-2">
                    <x-wui-input label="Description" wire:model.live='up_description' placeholder="description" />
                </div>
                <div class="col-span-1 sm:col-span-2">
                    <img src='{{ asset('storage/' . $up_product_image) }}' alt="product name"
                        class="object-cover w-48 h-48 rounded-lg" />
                    <div class="relative group">

                        <!-- Image Box -->
                        {{-- @dump($up_product_image) --}}

                        <!-- Close Button -->
                        {{-- <button @click="removeFile"
                            class="absolute p-1 text-white transition bg-red-500 rounded-full opacity-0 top-2 right-2 group-hover:opacity-100"
                            title="Remove image">
                            &times;
                        </button> --}}
                    </div>
                </div>

            </div>

            <x-slot name="footer" class="flex justify-between gap-x-4">
                <x-wui-button flat negative label="Delete" x-on:click="$closeModal('editModal')" />

                <div class="flex gap-x-4">
                    <x-wui-button flat label="Cancel" x-on:click="close" />
                    <x-primary-button wire:click="update">save</x-primary-button>
                </div>
            </x-slot>
        </form>
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
