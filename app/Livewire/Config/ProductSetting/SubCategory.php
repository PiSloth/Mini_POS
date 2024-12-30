<?php

namespace App\Livewire\Config\ProductSetting;

use App\Models\Category;
use App\Models\SubCategory as ModelsSubCategory;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class SubCategory extends Component
{
    use WireUiActions;
    use WithPagination;
    public $name;
    public $code;
    public $description;
    public $category_id;
    public $editId;
    public $deleteId;

    public $up_name;
    public $up_code;
    public $up_description;
    public $up_category_id;

    public function boot()
    {
        // $this->dialog()->show([
        //     'title' => 'Welcome',
        //     'description' => 'Nice to meet you',
        //     'icon' => 'success'
        // ]);
    }

    // create a category
    public function createCategory()
    {
        $validated = $this->validate([
            'name' => 'required',
            'code' => 'required',
            'category_id' => 'required',
            'description' => 'nullable'
        ]);

        ModelsSubCategory::create($validated);

        $this->reset('name', 'code', 'description', 'category_id');
        $this->dispatch('closeModal', 'newModal');
    }

    public function edit($id)
    {
        $query = ModelsSubCategory::findOrFail($id);

        $this->editId = $id;

        $this->up_category_id = $query->category_id;
        $this->up_name = $query->name;
        $this->up_code = $query->code;
        $this->up_description = $query->description;

        $this->dispatch('openModal', 'editModal');
    }

    //update sub category
    public function update()
    {
        $validated = $this->validate([
            'up_name' => 'required',
            'up_code' => 'required',
            'up_category_id' => 'required',
            'up_description' => 'nullable'
        ]);

        $query = ModelsSubCategory::findOrFail($this->editId);

        $query->update([
            'name' => $this->up_name,
            'code' => $this->up_code,
            'category_id' => $this->up_category_id,
            'description' => $this->up_description
        ]);

        $this->reset('up_name', 'code', 'up_description', 'up_category_id', 'editId');
        $this->dispatch('closeModal', 'editModal');
    }


    //clear edit  category id
    public function clearEditId()
    {
        $this->reset('up_name', 'up_code', 'up_description', 'up_category_id', 'editId');
        // $this->reset('editId');
    }

    //delete id set
    public function setDeleteId($id)
    {
        $this->deleteId = $id;
    }

    // public function delete()
    // {
    // }
    //delete item
    public function delete()
    {

        try {
            ModelsSubCategory::find($this->deleteId)->delete();
        } catch (Exception $e) {
            if ($e->getCode() == '23000') {
                $this->dialog()->show([
                    'icon' => 'error',
                    'title' => 'Failed!',
                    'description' => 'Can\'t delete this item coz of use in another record.'
                ]);
                $this->dispatch('close-modal', 'confirm-sub_category-delete');
                return;
            }
        }

        $this->reset('deleteId');
        $this->dispatch('close-modal', 'confirm-sub_category-delete');

        $this->notification()->send([
            'title' => 'Deleted!',
            'description' => 'Sub category deleted successfully',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $mainCategories = Category::all();
        $categories = ModelsSubCategory::with('category')->paginate(10);

        return view('livewire.config.product-setting.sub-category', [
            'categories' => $categories,
            'mainCategories' => $mainCategories,
        ]);
    }
}
