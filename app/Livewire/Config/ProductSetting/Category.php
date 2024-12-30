<?php

namespace App\Livewire\Config\ProductSetting;

use App\Models\Category as ModelsCategory;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Category extends Component
{
    use WireUiActions;
    use WithPagination;
    public $name;
    public $code;
    public $description;

    public $edit_id;
    public $up_name;
    public $up_description;
    public $up_code;

    public $delete_id;

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
            'description' => 'nullable'
        ]);

        ModelsCategory::create($validated);

        $this->reset('name', 'code', 'description');
        $this->dispatch('closeModal', 'newModal');
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $query = ModelsCategory::find($id);
        $this->up_name = $query->name;
        $this->up_description = $query->description;
        $this->up_code = $query->code;

        $this->dispatch('openModal', 'editModal');
    }

    public function setDeleteId($id)
    {
        $this->delete_id = $id;
    }

    public function updateCategory()
    {
        $query = ModelsCategory::find($this->edit_id);

        $query->update([
            'name' => $this->up_name,
            'code' => $this->up_code,
            'description' => $this->up_description,
        ]);

        $this->reset('up_name', 'up_code', 'up_description');
        $this->dispatch('closeModal', 'editModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Updated',
            'description' => 'Category was successfully updated.'
        ]);
    }

    //delete
    public function delete()
    {
        try {
            ModelsCategory::find($this->delete_id)->delete();
        } catch (Exception $e) {
            if ($e->getCode() == '23000') {
                $this->dialog()->show([
                    'icon' => 'error',
                    'title' => 'Failed!',
                    'description' => 'Can\'t delete this item coz of use in another record.'
                ]);
                $this->dispatch('close-modal', 'confirm-category-delete');
                return;
            }
        }

        $this->reset('delete_id');
        $this->dispatch('close-modal', 'confirm-category-delete');

        $this->dialog()->show([
            'icon' => 'success',
            'title' => 'Deleted',
            'description' => 'Category was successfully deleted.'
        ]);
    }

    public function render()
    {
        $categories = ModelsCategory::paginate(10);

        return view('livewire.config.product-setting.category', [
            'categories' => $categories,
        ]);
    }
}
