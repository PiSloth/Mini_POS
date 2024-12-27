<?php

namespace App\Livewire\Inventory;

use App\Models\BranchProduct;
use App\Models\BranchProductLocation;
use App\Models\StockAdjustmentTemp;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Balance extends Component
{
    use WireUiActions;
    public $branch_product_id;
    public $remark;
    public $quantity;
    public $item_location_id;

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

    public function render()
    {
        return view('livewire.inventory.balance', [
            'stocks' => BranchProduct::all(),
            'histories' => StockAdjustmentTemp::whereBranchProductId($this->branch_product_id)->get(),
        ]);
    }
}
