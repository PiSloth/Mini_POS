<?php

namespace App\Livewire\Inventory;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\BranchProductLocation;
use App\Models\Customer;
use App\Models\StockAdjustmentTemp;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Balance extends Component
{
    use WireUiActions;
    use WithPagination;
    public $branch_product_id;
    public $remark;
    public $quantity;
    public $item_location_id;
    public $branch_id = 1;


    // public function mount(){}

    public function initialStockId($id)
    {
        $this->branch_product_id = $id;
    }

    public function increaseStock()
    {
        $this->validate([
            'quantity' => 'required',
            'remark' => 'required'
        ]);

        DB::transaction(function () {
            $balance = BranchProduct::find($this->branch_product_id);

            $balance->update([
                'quantity' =>  $this->quantity + $balance->quantity,
            ]);

            StockAdjustmentTemp::create([
                'branch_product_id' => $this->branch_product_id,
                'remark' => $this->remark,
                'quantity' => $this->quantity,
                'user_id' => auth()->user()->id,
                'is_stock_in' => true,
            ]);
        });

        $this->reset('remark', 'quantity');
        $this->dispatch('closeModal', 'stockAdjustmentModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Adjusted',
            'description' => 'Stock adjustment successfully edited.'
        ]);
    }

    public function reduceStock()
    {
        $this->validate([
            'quantity' => 'required',
            'remark' => 'required'
        ]);


        $balance = BranchProduct::find($this->branch_product_id);

        if ($balance->quantity < $this->quantity) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Out of Stock',
                'description' => 'Not enough stock to transfer.'
            ]);
            return;
        }

        DB::transaction(function () use ($balance) {

            $balance->update([
                'quantity' =>   $balance->quantity - $this->quantity,
            ]);

            StockAdjustmentTemp::create([
                'branch_product_id' => $this->branch_product_id,
                'remark' => $this->remark,
                'quantity' => $this->quantity,
                'user_id' => auth()->user()->id,
                'is_stock_in' => false
            ]);
        });

        $this->reset('remark', 'quantity');
        $this->dispatch('closeModal', 'stockAdjustmentModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Adjusted',
            'description' => 'Stock adjustment successfully edited.'
        ]);
    }

    public function createItemLocation(): void
    {
        BranchProductLocation::create([
            'branch_product_id' => $this->branch_product_id,
            'item_location_id' => $this->item_location_id
        ]);

        $this->reset('branch_product_id', 'item_location_id');
        $this->dispatch('closeModal', 'itemLocationModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Located',
            'description' => 'Item successfully located.'
        ]);
    }

    #[Title('main stock in our inventory')]

    public function render()
    {
        $branch_products = BranchProduct::where('branch_id', $this->branch_id)
            ->paginate(10);

        return view('livewire.inventory.balance', [
            'stocks' => $branch_products,
            'histories' => StockAdjustmentTemp::whereBranchProductId($this->branch_product_id)->get(),
            'branches' => Branch::all(),
        ]);
    }
}
