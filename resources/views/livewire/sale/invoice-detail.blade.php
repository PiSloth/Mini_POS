<div>
    <div class="flex h-12 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Invoicing Detail') }}
        </h2>
        @if ($status == 'new')
            <x-primary-button @click="$openModal('paymentModal')">confirm</x-primary-button>
        @endif
        @if ($payment_button == true)
            <x-primary-button @click="$openModal('paymentAgainModal')">Payment</x-primary-button>
        @endif
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
                    @forelse ($items as $item)
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
    <div class="flex mr-10 justify-self-end">
        <table class="border border-gray-100">
            <thead>
                <tr class="border-2 border-gray-2">
                    <th class="px-6 py-4">Desc</th>
                    <th class="px-6 py-4 sr-only">Desc</th>
                </tr>
            </thead>
            <tbody>

                <tr class="border-2 border-gray-2">
                    <td class="px-6 py-2"> {{ 'Total' }} </td>
                    <td class="px-6 py-2">{{ number_format($invoice_info->total) }}</td>
                </tr>

                <tr class="border-2 border-gray-2">
                    <td class="px-6 py-2"> {{ 'Paid' }} </td>
                    <td class="px-6 py-2">{{ number_format($invoice_info->paid_amount) }}</td>
                </tr>

                @php
                    $outstanding = $invoice_info->total - $invoice_info->paid_amount;
                @endphp
                <tr class="border-2 border-gray-2">
                    <td class="px-6 py-2"> {{ 'Outstanding Amount' }} </td>
                    <td class="px-6 py-2">{{ number_format($outstanding) }}</td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- Payment modal  --}}
    <x-wui-modal-card title="Payemnt Terms ရွေးချယ်ပါ" name="paymentModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-select wire:model='payment_method' label="Payment Option" placeholder="eg ,NexGen Pay"
                :async-data="route('api.payment-methods')" option-label="name" option-value="id" />
        </div>
        <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2">
            <x-wui-currency thousands="," prefix="MMK" label="Amount" wire:model='paid_amount'
                placeholder=" eg. 1500" />
            {{-- <x-wui-date label="phone" wire:model='payment_date' /> --}}
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="COD" wire:click='codPayment' />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" wire:click='confirmAndPayment' />
            </div>
        </x-slot>
    </x-wui-modal-card>

    {{-- Make payment again --}}
    <x-wui-modal-card title="Payemnt Terms ရွေးချယ်ပါ" name="paymentAgainModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-wui-select wire:model='payment_method' label="Payment Option" placeholder="eg ,NexGen Pay"
                :async-data="route('api.payment-methods')" option-label="name" option-value="id" />
        </div>
        <div class="grid grid-cols-1 gap-4 mt-3 sm:grid-cols-2">
            <x-wui-currency label="Amount" prefix="MMK" thousands="," wire:model='paid_amount'
                placeholder="ကျန်ငွေ  {{ number_format($outstanding) }}" />
            {{-- <x-wui-date label="phone" wire:model='payment_date' /> --}}
        </div>

        <x-slot name="footer" class="flex justify-between gap-x-4">
            <x-wui-button flat negative label="delete" x-on:click="close" />

            <div class="flex gap-x-4">
                <x-wui-button flat label="Cancel" x-on:click="close" />

                <x-wui-button primary label="Save" wire:click='createPayment' />
            </div>
        </x-slot>
    </x-wui-modal-card>
</div>
<script>
    Livewire.on('closeModal', (name) => {
        $closeModal(name);
    });
</script>
