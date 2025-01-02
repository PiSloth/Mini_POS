<?php

namespace App\Livewire\Sale;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\SimpleExcel\SimpleExcelWriter;

class DailyInvoice extends Component
{
    use WithPagination;

    public function exportData()
    {
        $invoices = Invoice::all();

        // Create a temporary file with .xlsx extension
        $tempFilePath = tempnam(sys_get_temp_dir(), 'pos') . '.xlsx';

        // Create the Excel file at the temporary location
        $writer = SimpleExcelWriter::create($tempFilePath)
            ->addHeader([
                'Invoice Date',
                'Customer Name',
                'Ph no', //Category
                'Address', //Design
                'Total Amount', //Detial Design
                'Paid Amount',
                'Oustanding Amount',
                'Voucher Status',
                'Payment Status',
            ]);

        foreach ($invoices as $invoice) {
            $writer->addRow([
                date_format($invoice->created_at, 'F j, Y'),
                $invoice->customer->name,
                $invoice->customer->phone,
                $invoice->customer->address,
                $invoice->total,
                $invoice->paid_amount,
                $invoice->total - $invoice->paid_amount,
                $invoice->invoiceStatus->name,
                $invoice->payment_status,
            ]);
        }
        $writer->close();

        // Stream the file to the browser
        return Response::download($tempFilePath, Carbon::now()->format('dmY_His') . '-invoices.xlsx')->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.sale.daily-invoice', [
            'daily_sales' => Invoice::orderBy('id', 'desc')->paginate('5'),
        ]);
    }
}
