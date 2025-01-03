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
            <a href="/generate/{{ $invoice_id }}">
                <x-primary-button>Print</x-primary-button>
            </a>
        @endif
    </div>
    {{-- voucher template --}}
    <div class="w-1/2 mx-auto mt-4">
        <div class="w-[5.8in] h-[8.3in] bg-white shadow-lg p-6">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    @foreach ($branch_info as $name => $item)
                        <h1 class="text-xl font-bold">{{ $name }}</h1>
                        <p class="text-sm">{{ $item['address'] }}</p>
                        <p class="text-sm">Phone: +123-456-7890</p>
                    @endforeach
                </div>
                <div>
                    <x-application-logo class="block w-auto text-gray-800 fill-current h-9 dark:text-gray-200" />
                </div>
            </div>

            <!-- Customer Information -->
            <div class="mb-6">

                <h2 class="mb-2 text-lg font-bold">Invoice To:</h2>
                @foreach ($customer_info as $info)
                    <p class="text-sm">{{ $info['detail'] }}</p>
                @endforeach
            </div>

            <!-- Invoice Items -->
            <div class="mb-6">
                <table class="w-full border border-collapse border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-sm text-left border border-gray-300">Item Name</th>
                            <th class="px-4 py-2 text-sm text-right border border-gray-300">Quantity</th>
                            <th class="px-4 py-2 text-sm text-right border border-gray-300">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="px-4 py-2 text-sm border border-gray-300">
                                    {{ $item->branchProduct->product->name }}</td>
                                <td class="px-4 py-2 text-sm text-right border border-gray-300">
                                    {{ $item->price }} x {{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-sm text-right border border-gray-300">{{ $item->total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @php
                $outstanding = $invoice_info->total - $invoice_info->paid_amount;
            @endphp


            <!-- Totals -->
            <div class="mb-6 text-right">
                <div class="flex justify-between text-sm">
                    <span>Paid Amount:</span>
                    <span>{{ $invoice_info->paid_amount }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Outstanding Amount:</span>
                    <span>{{ number_format($outstanding) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Amount:</span>
                    <span>{{ number_format($invoice_info->total) }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-600">Thank you for choosing us!</p>
            </div>
        </div>
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
