<?php

namespace App\Livewire\Sale;

use App\Models\BranchProduct;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SaleInvoice extends Component
{
    public $search;
    public $selected;
    public $cart = [];

    public function cart($key, $name, $code, $price)
    {
        dd(
            "Hello"
        );
        $this->cart[] = [
            'key' => $key,
            '$name' => $name,
            '$code' => $code,
            'price' => $price
        ];
        dd($this->cart);
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

        // if ($this->search) {

        //     dd($products);
        // }

        return view('livewire.sale.sale-invoice', [
            'products' => $products,
        ]);
    }
}
