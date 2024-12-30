<?php

namespace App\Livewire\Sale;

use App\Models\BranchProduct;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\StockAdjustmentTemp;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class InvoiceDetail extends Component
{
    #[Title('Invoicing detail template')]

    #[Url(as: 'view-detail')]
    public $invoice_id;

    use WireUiActions;

    public $customer_info = [];
    public $cart_items = [];

    public function mount()
    {
        $invoice = Invoice::find($this->invoice_id);
        $this->customer_info = [
            ['type' => 'Name', 'detail' => $invoice->customer->name],
            ['type' => 'Phone', 'detail' => $invoice->customer->phone],
            ['type' => 'Address', 'detail' => $invoice->customer->address]
        ];
    }

    public function confirmInvoice()
    {
        try {
            DB::transaction(function () {
                // dd($this->cart_items);
                $index = 10;
                //update Stock
                foreach ($this->cart_items as $item) {
                    // dd($item['id']);
                    $index++;
                    $query = BranchProduct::findOrFail($item['id']);

                    if ($query->quantity < $item['quantity']) {

                        $this->dialog()->show([
                            'icon' => 'error',
                            'title' => 'Failed',
                            'description' => 'No enough item to sell.Check Stock first.'
                        ]);
                        return;
                    } else {
                        //stock update
                        $query->update([
                            'quantity' => $query->quantity - $item['quantity'],
                        ]);

                        //stock transaction record
                        StockAdjustmentTemp::create([
                            'branch_product_id' => $item['id'],
                            'remark' => "Sale Transfer" . $index,
                            'quantity' => $item['quantity'],
                            'user_id' => auth()->user()->id,
                            'is_stock_in' => false,
                        ]);
                    }
                }
                Invoice::find($this->invoice_id)->update([
                    'invoice_status_id' => 2, //confirm
                ]);
                $this->dialog()->show([
                    'icon' => 'success',
                    'title' => 'Confirmed',
                    'description' => 'Confirmed voucher and maked a DO.'
                ]);
            });
        } catch (Exception $e) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => 'No enough item to sell.Check Stock first.'
            ]);
        }
    }

    public function render()
    {
        $invoice_items = InvoiceItem::whereInvoiceId($this->invoice_id)->get();

        foreach ($invoice_items as $item) {
            $this->cart_items[] = [
                'id' => $item->branch_product_id,
                'quantity' => $item->quantity,
            ];
        }

        // dd($this->cart_items);

        $status = Invoice::find($this->invoice_id)->invoiceStatus->name;
        // dd($status);

        return view('livewire.sale.invoice-detail', [
            'items' => $invoice_items,
            'status' => strtolower($status),
        ]);
    }
}
