<?php

namespace App\Livewire\Config\ProductSetting;

use App\Models\Category;
use App\Models\SubCategory as ModelsSubCategory;
use Exception;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class SubCategory extends Component
{
    use WireUiActions;
    public $name;
    public $code;
    public $description;
    public $category_id;
    public $editId;
    public $deleteId;

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

        $this->category_id = $query->category_id;
        $this->name = $query->name;
        $this->code = $query->code;
        $this->description = $query->description;

        $this->dispatch('openModal', 'newModal');
    }

    //update sub category
    public function update()
    {
        $validated = $this->validate([
            'name' => 'required',
            'code' => 'required',
            'category_id' => 'required',
            'description' => 'nullable'
        ]);

        $query = ModelsSubCategory::findOrFail($this->editId);

        $query->update($validated);

        $this->reset('name', 'code', 'description', 'category_id', 'editId');
        $this->dispatch('closeModal', 'newModal');
    }


    //clear edit  category id
    public function clearEditId()
    {
        $this->reset('name', 'code', 'description', 'category_id', 'editId');
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
        $categories = ModelsSubCategory::with('category')->get();

        return view('livewire.config.product-setting.sub-category', [
            'categories' => $categories,
            'mainCategories' => $mainCategories,
        ]);
    }
}
