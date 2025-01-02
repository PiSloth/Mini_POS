<?php

namespace App\Livewire\Sale;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class SaleInvoice extends Component
{
    use WireUiActions;
    use WithPagination;
    public $search;
    public $selected;
    public $cart = [];
    //customer
    public $name;
    public $phone;
    public $address;
    public $customer_id;
    public $customer = [];
    public $invoice_id;

    public $branch_id = 1;

    public function addToCart($key, $name, $code, $price): void
    {
        // dd("Hello");
        if (!isset($this->cart[$key])) {
            $this->cart[$key] = [];

            $this->cart[$key] = [
                'key' => $key,
                'name' => $name,
                'code' => $code,
                'price' => $price,
                'quantity' => 1
            ];
        } else {
            $this->cart[$key]['quantity'] += 1;
            $this->cart[$key]['price'] += $price;
        }
        // dd($this->cart);
    }

    //create Voucher
    public function createVoucher()
    {
        if (count($this->cart) == 0 || count($this->customer) == 0) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => 'Customer data or Product data not found.'
            ]);
            return;
        }

        $total = 0;
        foreach ($this->cart as $item) {
            $total += $item['price'];
        }

        DB::transaction(function () use ($total) {

            $invoice = Invoice::create([
                'number' => "PV/" . Carbon::now()->format('mjyHi'),
                'customer_id' => $this->customer_id,
                'invoice_status_id' => 1, //new
                'total' => $total,
            ]);

            //set id for navigate
            $this->invoice_id = $invoice->id;

            foreach ($this->cart as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'branch_product_id' => $item['key'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }
        });

        $this->reset('cart', 'customer');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Created',
            'description' => "Voucher was successfully created."
        ]);

        return $this->redirectRoute('invoice-detail', ['view-detail' => $this->invoice_id], navigate: true);
    }

    public function createCustomer(): void
    {

        if (in_array($this->customer_id, array_column($this->customer, 'id'))) {
            // dd('true');
            return;
            $this->dispatch('closeModal', 'newModal');
        }

        if ($this->customer_id) {
            $customerData = Customer::find($this->customer_id);

            $this->customer[] = [
                'id' => $this->customer_id,
                'name' => $customerData->name,
                'phone' => $customerData->phone,
                'address' => $customerData->address,
            ];
        } else {
            $validated = $this->validate([
                'name' => 'required',
                'phone' => 'required',
                'address' => 'nullable|string',
            ]);

            $createdCustomer = Customer::create($validated);

            $this->customer_id = $createdCustomer->id;

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Created!',
                'description' => 'Customer added successfully'
            ]);

            $this->customer[] = [
                'id' => $createdCustomer->id,
                'name' => $this->name,
                'phone' => $this->phone,
                'address' => $this->address,
            ];
        }

        $this->dispatch('closeModal', 'newModal');
    }

    #[Title('Create an invoice')]

    public function render()
    {
        $products =  BranchProduct::query()
            ->select('branch_products.id', 'branch_products.price', DB::raw("concat(categories.code,sub_categories.code,products.code) as code, products.name as name"))
            ->leftJoin('products', 'products.id', 'branch_products.product_id')
            ->leftJoin('sub_categories', 'sub_categories.id', 'products.sub_category_id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id')
            ->orderBy('categories.code')
            ->where('branch_products.branch_id', '=', $this->branch_id)
            ->when(
                $this->search,
                fn(Builder $query) => $query->where(function ($query) {
                    $query->where('categories.code', 'like', "%{$this->search}%")
                        ->orWhere('sub_categories.code', 'like', "%{$this->search}%")
                        ->orWhere('products.code', 'like', "%{$this->search}%")
                        ->orWhere('products.name', 'like', "%{$this->search}%");
                })

            )
            ->paginate(4);
        // ->get();
        // dd($products);

        // dd(BranchProduct::all());

        // dd(Carbon::now()->format('mjyHi'));
        // if ($this->customer) {

        //     dd($this->customer);
        // }


        return view('livewire.sale.sale-invoice', [
            'products' => $products,
            'branches' => Branch::all(),
        ]);
    }
}
