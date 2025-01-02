<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 dark:bg-gray-800 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex items-center shrink-0">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                {{-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div> --}}

                {{-- Config links  --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <div x-data="{ name: 'Configuration' }" x-text="name"
                                        x-on:profile-updated.window="name = $event.detail.name"></div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!--Shop or Branch -->
                                <x-dropdown-link :href="route('branch')" wire:navigate>
                                    {{ __('Branch') }}
                                </x-dropdown-link>

                                <!--Cat -->
                                <x-dropdown-link :href="route('category')" wire:navigate>
                                    {{ __('Category') }}
                                </x-dropdown-link>

                                <!-- Sub Cat -->
                                {{-- <button class="w-full text-start"> --}}
                                <x-dropdown-link :href="route('sub-category')" wire:navigate>
                                    {{ __('Sub Category') }}
                                </x-dropdown-link>

                                {{-- Product Create --}}
                                <button class="w-full text-start">
                                    <x-dropdown-link :href="route('product')" wire:navigate>
                                        {{ __('Product') }}
                                    </x-dropdown-link>
                                </button>

                                {{-- Branch product items location --}}
                                <button class="w-full text-start">
                                    <x-dropdown-link :href="route('item-location')" wire:navigate>
                                        {{ __('Item Location') }}
                                    </x-dropdown-link>
                                </button>

                                {{-- Payment methods in accounting --}}
                                <button class="w-full text-start">
                                    <x-dropdown-link :href="route('payment-method')" wire:navigate>
                                        {{ __('Payment Methods') }}
                                    </x-dropdown-link>
                                </button>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                {{-- Inventory links  --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <div x-data="{ name: 'Inventory' }" x-text="name"
                                        x-on:profile-updated.window="name = $event.detail.name"></div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!--Stock balance -->
                                <x-dropdown-link :href="route('stock-balance')" wire:navigate>
                                    {{ __('Balance') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
                {{-- Sale links  --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <div x-data="{ name: 'Sale' }" x-text="name"
                                        x-on:profile-updated.window="name = $event.detail.name"></div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!--Shop or Branch -->
                                <x-dropdown-link :href="route('invoice')" wire:navigate>
                                    {{ __('Invoice') }}
                                </x-dropdown-link>

                                <!--Cat -->
                                <x-dropdown-link :href="route('daily-invoice')" wire:navigate>
                                    {{ __('Daily Sales') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                {{-- Crm links  --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <div class="hidden sm:flex sm:items-center">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                    <div x-data="{ name: 'CRM' }" x-text="name"
                                        x-on:profile-updated.window="name = $event.detail.name"></div>
                                    <div class="ms-1">
                                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <!--Contact List -->
                                <x-dropdown-link :href="route('contact')" wire:navigate>
                                    {{ __('Contacts') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </div>


            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>
                            <div class="ms-1">
                                <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="flex items-center -me-2 sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 text-gray-400 transition duration-150 ease-in-out rounded-md dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400">
                    <svg class="w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>


        <!-- Responsive Config Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-teal-600 dark:text-gray-200" x-data="{ name: 'Config' }"
                    x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">Conguration of your business</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('branch')" wire:navigate>
                    {{ __('Branch') }}
                </x-responsive-nav-link>


                <x-responsive-nav-link :href="route('category')" wire:navigate>
                    {{ __('Category') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('sub-category')" wire:navigate>
                    {{ __('Sub Category') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('product')" wire:navigate>
                    {{ __('Product') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('item-location')" wire:navigate>
                    {{ __('Item Location') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('payment-method')" wire:navigate>
                    {{ __('Payment Methods') }}
                </x-responsive-nav-link>

            </div>
        </div>

        <!-- Responsive Inventory Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-teal-600 dark:text-gray-200" x-data="{ name: 'Inventory' }"
                    x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">Keep your inventory healthy</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('stock-balance')" wire:navigate>
                    {{ __('Balance') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <!-- Responsive Sales Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-teal-600 dark:text-gray-200" x-data="{ name: 'Sales' }"
                    x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">Make more revenue everyday</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('invoice')" wire:navigate>
                    {{ __('Invoice') }}
                </x-responsive-nav-link>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('daily-invoice')" wire:navigate>
                    {{ __('Daily Sales') }}
                </x-responsive-nav-link>
            </div>
        </div>
        <!-- Responsive Sales Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-teal-600 dark:text-gray-200" x-data="{ name: 'CRM' }"
                    x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">Relationship management for customer retained</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('contact')" wire:navigate>
                    {{ __('Contacts') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="text-base font-medium text-gray-800 dark:text-gray-200" x-data="{{ json_encode(['name' => auth()->user()->name]) }}"
                    x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>

    </div>
</nav>
