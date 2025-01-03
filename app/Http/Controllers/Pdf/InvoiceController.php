<?php

namespace App\Http\Controllers\Pdf;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Spatie\Browsershot\Browsershot;

class InvoiceController extends Controller
{

    public $invoice_id;
    public $customer_info = [];
    public $branch_info = [];

    public function generateInvoice($id)
    {
        // dd($id);
        $invoice = Invoice::findOrFail($id);

        $this->customer_info = [
            ['type' => 'Name', 'detail' => $invoice->customer->name],
            ['type' => 'Phone', 'detail' => $invoice->customer->phone],
            ['type' => 'Address', 'detail' => $invoice->customer->address]
        ];


        $invoice_items = InvoiceItem::whereInvoiceId($id)->get();

        foreach ($invoice_items as $item) {



            if (!isset($this->branch_info[$item->branchProduct->branch->name])) {
                $this->branch_info[$item->branchProduct->branch->name] = [
                    'name' => $item->branchProduct->branch->name,
                    'address' => $item->branchProduct->branch->address,
                ];
            }
        }

        $pdf = Pdf::loadView('livewire.sale.invoice-detail', [
            'items' => $invoice_items,
            'customer_info' => $this->customer_info,
            'branch_info' => $this->branch_info,
            'status' => 'confirmed',
            'payment_button' => 'false'
        ]);
        $pdf->setPaper('A5', 'portrait');
        return $pdf->download('voucher' . '.pdf', [
            "Attachment" => true
        ]);

        // $template = view('livewire.sale.invoice-detail', [
        //     'items' => $invoice_items,
        //     'customer_info' => $this->customer_info,
        //     'branch_info' => $this->branch_info,
        // ])->render();

        // Browsershot::html($template)
        //     ->save('exp.pdf');
    }


    // dd($this->branch_info);

}
