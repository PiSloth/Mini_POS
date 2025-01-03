<?php

namespace App\Livewire\Sale;

use App\Models\BranchProduct;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\PaymentHistory;
use App\Models\StockAdjustmentTemp;
use Carbon\Carbon;
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
    public $branch_info = [];
    public $paid_amount;
    public $payment_method;
    public $payment_date;
    public $invoice_status = [
        'NEW' => 1,
        'CONFIRM' => 2,
        'CANCLE' => 3
    ];


    public function mount()
    {

        $invoice = Invoice::findOrFail($this->invoice_id);
        $this->customer_info = [
            ['type' => 'Name', 'detail' => $invoice->customer->name],
            ['type' => 'Phone', 'detail' => $invoice->customer->phone],
            ['type' => 'Address', 'detail' => $invoice->customer->address]
        ];

        $this->payment_date = Carbon::now();

        $invoice_items = InvoiceItem::whereInvoiceId($this->invoice_id)->get();

        foreach ($invoice_items as $item) {
            $this->cart_items[] = [
                'id' => $item->branch_product_id,
                'quantity' => $item->quantity,
            ];

            if (!isset($this->branch_info[$item->branchProduct->branch->name])) {
                $this->branch_info[$item->branchProduct->branch->name] = [
                    'name' => $item->branchProduct->branch->name,
                    'address' => $item->branchProduct->branch->address,
                ];
            }
        }

        // dd($this->branch_info);
    }



    //todo make voucher confirm and payment confirm
    public function confirmAndPayment()
    {
        $this->validate([
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required',
        ]);
        //invoice confirm
        $invoice = Invoice::find($this->invoice_id);
        if ($this->paid_amount == $invoice->total) {
            $payment_status = 'paid';
        } else if ($this->paid_amount < $invoice->total) {
            $payment_status = 'partial';
        } else {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'Paid Amount is greater than actual amount'
            ]);

            return;
        }
        $invoice_items = InvoiceItem::whereInvoiceId($this->invoice_id)->get();
        //
        try {
            DB::transaction(function () use ($invoice, $payment_status, $invoice_items) {
                $invoice->update([
                    'invoice_status_id' => $this->invoice_status['CONFIRM'],
                    'paid_amount' => $this->paid_amount,
                    'payment_status' => $payment_status,
                ]);

                //payment history
                PaymentHistory::create([
                    'invoice_id' => $this->invoice_id,
                    'payment_method_id' => $this->payment_method,
                    'amount' => $this->paid_amount,
                    'payment_date' => $this->payment_date
                ]);

                //stock update
                foreach ($invoice_items as $item) {
                    $query = BranchProduct::findOrFail($item->id);

                    if ($query->quantity < $item->quantity) {
                        $this->dialog()->show([
                            'icon' => 'error',
                            'title' => 'Failed',
                            'description' => 'No enough item to sell.Check Stock first.'
                        ]);
                        return;
                    } else {
                        //stock update
                        $query->update([
                            'quantity' => $query->quantity - $item->quantity,
                        ]);

                        //stock transaction record
                        StockAdjustmentTemp::create([
                            'branch_product_id' => $item->id,
                            'remark' => "Sale Transfer /$this->invoice_id",
                            'quantity' => $item->quantity,
                            'user_id' => auth()->user()->id,
                            'is_stock_in' => false,
                        ]);
                    }
                }
            });
        } catch (Exception $e) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => $e
            ]);
            return;
        }

        $this->reset('paid_amount', 'payment_method');
        $this->dispatch('closeModal', 'paymentModal');

        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Updated',
            'Invoice is updated successfully.'
        ]);
    }

    //todo make cod payment
    public function codPayment()
    {
        $invoice = Invoice::find($this->invoice_id);

        $invoice_items = InvoiceItem::whereInvoiceId($this->invoice_id)->get();

        try {
            DB::transaction(function () use ($invoice, $invoice_items) {
                $invoice->update([
                    'invoice_status_id' => $this->invoice_status['CONFIRM']
                ]);

                //stock update
                foreach ($invoice_items as $item) {
                    $query = BranchProduct::findOrFail($item->id);

                    if ($query->quantity < $item->quantity) {
                        $this->dialog()->show([
                            'icon' => 'error',
                            'title' => 'Failed',
                            'description' => 'No enough item to sell.Check Stock first.'
                        ]);
                        return;
                    } else {
                        //stock update
                        $query->update([
                            'quantity' => $query->quantity - $item->quantity,
                        ]);

                        //stock transaction record
                        StockAdjustmentTemp::create([
                            'branch_product_id' => $item->id,
                            'remark' => "Sale Transfer /$this->invoice_id",
                            'quantity' => $item->quantity,
                            'user_id' => auth()->user()->id,
                            'is_stock_in' => false,
                        ]);
                    }
                }
            });
        } catch (Exception $e) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => 'No enough item to sell.Check Stock first.'
            ]);
            return;
        }


        $this->dispatch('closeModal', 'paymentModal');

        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Updated',
            'Invoice is updated successfully.'
        ]);
    }

    //todo ceate payment after cod payment or partial payment
    public function createPayment()
    {
        $this->validate([
            'paid_amount' => 'required|numeric',
            'payment_method' => 'required',
        ]);

        $invoice = Invoice::find($this->invoice_id);
        $remain_amount = $invoice->total - $invoice->paid_amount;

        if ($this->paid_amount == $remain_amount) {
            $payment_status = 'paid';
        } else if ($this->paid_amount < $remain_amount) {
            $payment_status = 'partial';
        } else {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'Paid Amount is greater than actual amount'
            ]);
            return;
        }

        try {
            DB::transaction(function () use ($payment_status, $invoice) {
                $invoice->update([
                    'paid_amount' => $this->paid_amount + $invoice->paid_amount,
                    'payment_status' => $payment_status,
                ]);

                //payment history
                PaymentHistory::create([
                    'invoice_id' => $this->invoice_id,
                    'payment_method_id' => $this->payment_method,
                    'amount' => $this->paid_amount,
                    'payment_date' => $this->payment_date
                ]);
            });
        } catch (Exception $e) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => $e,
            ]);
            return;
        }
        $this->reset('paid_amount');
        $this->dispatch('closeModal', 'paymentAgainModal');
        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Created',
            'description' => 'Payment was created successfully.',
        ]);
    }

    public function confirmInvoice()
    {
        try {
            DB::transaction(function () {
                // dd($this->cart_items);
                $index = 10;
                //update Stock
                foreach ($this->cart_items as $item) {
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

        $invInfo = Invoice::findOrFail($this->invoice_id);

        $status = strtolower($invInfo->invoiceStatus->name);
        $payment_status = $invInfo->payment_status;
        // dd($status);
        //show payment button or not show
        if ($status == 'confirmed' && $payment_status !== 'paid') {
            $paymentButton = true;
        } else {
            $paymentButton = false;
        }
        // dd($status);

        $invoice_items = InvoiceItem::whereInvoiceId($this->invoice_id)->get();


        return view('livewire.sale.invoice-detail', [
            'items' => $invoice_items,
            'status' => strtolower($status),
            'payment_status' => strtolower($payment_status),
            'payment_button' => $paymentButton,
            'invoice_info' => Invoice::find($this->invoice_id),
        ]);
    }
}
