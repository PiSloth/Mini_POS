<?php

namespace App\Livewire\Config\ProductSetting;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Price;
use App\Models\Product as ModelsProduct;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class Product extends Component
{
    use WithFileUploads;
    use WireUiActions;
    public $branch_id = '';
    public $price;
    public $edit_id;
    public $delete_id;
    public $sub_category_id;
    public $name;
    public $code;
    public $description;
    public $product_image;
    //update
    public $up_branch_id = '';
    public $up_price;
    public $up_sub_category_id;
    public $up_name;
    public $up_code;
    public $up_description;
    public $up_product_image;
    public $up_product_image_id;
    public $up_product_id;


    //edit
    private $edit_product_id;
    private $edit_branch_id;
    private $edit_price_id;
    private $edit_img_id;

    //branch history
    public $located_branches = [];
    public $history_look_product;


    public function create()
    {
        // dd("Hello");
        $validateProduct = $this->validate([
            'sub_category_id' => 'required',
            'name' => 'required',
            'code' => 'required',
            'description' => 'nullable',
        ]);

        $this->validate([
            'branch_id' => 'required',
            'price' => 'required',
        ]);

        $this->validate([
            'product_image' => 'required|sometimes|nullable',
        ]);

        // ---- Use Try Catch -----

        DB::transaction(function () use ($validateProduct) {

            //create Product
            $createdProduct = ModelsProduct::create($validateProduct);

            $path = $this->product_image->store('images', 'public');

            ProductImage::create([
                'image' => $path,
                'product_id' => $createdProduct->id,
            ]);

            BranchProduct::create([
                'branch_id' => $this->branch_id,
                'product_id' => $createdProduct->id,
                'price' => $this->price,
            ]);
        });

        $this->dispatch('closeModal', 'newModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Product Created',
            'description' => 'Product created successfully ',
        ]);
    }

    //Read for edit
    public function edit($id)
    {
        $this->edit_id = $id;
    }


    //Update
    public function update()
    {
        // dd($this->up_product_image);

        $validateProduct = $this->validate([
            'up_sub_category_id' => 'required',
            'up_name' => 'required',
            'up_code' => 'required',
            'up_description' => 'nullable',
        ]);

        // $this->validate([
        //     'up_branch_id' => 'required',
        //     'up_price' => 'required',
        // ]);

        // $this->validate([
        //     'up_product_image' => 'required|sometimes|nullable',
        // ]);

        // ---- Use Try Catch -----

        DB::transaction(function () {

            $pId = $this->up_product_id;
            $bpId = $this->edit_id;
            $imgId = $this->up_product_image_id;

            ModelsProduct::where('id', $pId)->update([
                'sub_category_id' => $this->up_sub_category_id,
                'name' => $this->up_name,
                'code' => $this->up_code,
                'description' => $this->up_description,
            ]);

            // BranchProduct::where('id', $bpId)->update([
            //     'price' => $this->up_price,
            // ]);
            // ProductImage::whereProductId($imgId)->update([
            //     'image' => $this->up_product_image,
            // ]);
        });

        $this->dispatch('closeModal', 'editModal');

        $this->reset('up_sub_category_id', 'up_name', 'up_code', 'up_description');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Product Updated',
            'description' => 'Product updated successfully ',
        ]);
    }

    //Branch history

    public function branchHistories($id)
    {
        $this->history_look_product = $id;
        $this->located_branches = [];
        $query = BranchProduct::whereProductId($id)->get();
        // dd($query);
        $this->up_product_image = $query->first()->product->productImage->image;

        foreach ($query as $item) {
            $this->located_branches[$item->branch_id] =  $item->branch->name;
        }

        $this->dispatch('openModal', 'newLocateModal');
    }


    //locate to a new branch
    public function newBranchLocate()
    {
        $this->validate([
            'branch_id' => 'required',
            'price' => 'required',
        ]);


        if (isset($this->located_branches[$this->branch_id])) {
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Failed',
                'description' => 'This item is already located  to this branch.'
            ]);
            return;
        }

        BranchProduct::create([
            'branch_id' => $this->branch_id,
            'product_id' => $this->history_look_product,
            'price' => $this->price,
        ]);

        $this->reset('branch_id', 'price', 'history_look_product');
        $this->dispatch('closeModal', 'newLocateModal');

        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Located',
            'description' => 'This item located to a new branch.'
        ]);
    }

    public function render()
    {
        $products = ModelsProduct::all();

        // dd($products);

        if ($this->edit_id) {
            $edit_item = ModelsProduct::find($this->edit_id);

            // $this->up_branch_id = $edit_item->branch_id;
            // $this->up_price = $edit_item->price;
            $this->up_sub_category_id = $edit_item->sub_category_id;
            $this->up_name = $edit_item->name;
            $this->up_code = $edit_item->code;
            $this->up_product_image = $edit_item->productImage->image;
            $this->dispatch('openModal', 'editModal');

            // dump($this->up_product_image);
        } else {
            $edit_item = [
                'product_id' => '',
                'branch_id' => '',
            ];
        }

        return view('livewire.config.product-setting.product', [
            'products' => $products,
            'branches' => Branch::all(),
            'editItem' => $edit_item,
        ]);
    }
}
