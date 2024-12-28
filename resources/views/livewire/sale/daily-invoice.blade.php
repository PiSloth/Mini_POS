<div>
    <div class="flex h-16 gap-2 p-3 mb-2 bg-white dark:text-white">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Sale Invoices') }}
        </h2>
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
            @forelse ($daily_sales as $item)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $item->number }}
                    </th>
                    <td class="px-6 py-4">
                        {{ $item->customer->name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->total }}
                    </td>
                    <td class="px-6 py-4">
                        <x-wui-badge secondary>{{ $item->invoiceStatus->name }}</x-wui-badge>

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
