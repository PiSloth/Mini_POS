<?php

namespace App\Livewire\Sale;

use App\Models\BranchProduct;
use App\Models\Customer;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class SaleInvoice extends Component
{
    use WireUiActions;
    public $search;
    public $selected;
    public $cart = [];
    //customer
    public $name;
    public $phone;
    public $address;
    public $customer_id;
    public $customer = [];

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
    public function createVoucher(): void
    {
        if (count($this->cart) == 0 || count($this->customer) == 0) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => 'Customer data or Product data not found.'
            ]);
            return;
        }
        DB::transaction(function () {
            $total = 0;
            foreach ($this->cart as $item) {
                $total += $item['price'];
            }
            Invoice::create([
                'number' => Carbon::now()->format('mjyHi'),
                'customer_id' => $this->customer_id,
                'invoice_status_id' => 1, //new
                'total' => $total,
            ]);
        });

        $this->reset('cart', 'customer');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Created',
            'description' => "Voucher was successfully created."
        ]);
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
                'phone' => 'numeric|required',
                'address' => 'nullable|string',
            ]);

            $createdCustomer = Customer::create($validated);

            $this->customer_id = $createdCustomer;

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

    public function render()
    {
        $products =  BranchProduct::query()
            ->select('branch_products.id', 'branch_products.price', DB::raw("concat(categories.code,sub_categories.code,products.code) as code, products.name as name"))
            ->leftJoin('products', 'products.id', 'branch_products.product_id')
            ->leftJoin('sub_categories', 'sub_categories.id', 'products.sub_category_id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id')
            ->orderBy('categories.code')
            ->where('branch_products.branch_id', '=', 1)
            ->when(
                $this->search,
                fn(Builder $query) => $query->where(function ($query) {
                    $query->where('categories.code', 'like', "%{$this->search}%")
                        ->orWhere('sub_categories.code', 'like', "%{$this->search}%")
                        ->orWhere('products.code', 'like', "%{$this->search}%")
                        ->orWhere('products.name', 'like', "%{$this->search}%");
                })

            )
            ->get();

        // dd(Carbon::now()->format('mjyHi'));
        // if ($this->customer) {

        //     dd($this->customer);
        // }


        return view('livewire.sale.sale-invoice', [
            'products' => $products,
        ]);
    }
}
