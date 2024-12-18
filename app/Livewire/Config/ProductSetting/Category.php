<?php

namespace App\Livewire\Config\ProductSetting;

use App\Models\Category as ModelsCategory;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Category extends Component
{
    use WireUiActions;
    public $name;
    public $code;
    public $description;

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

    public function render()
    {
        $categories = ModelsCategory::all();

        return view('livewire.config.product-setting.category', [
            'categories' => $categories,
        ]);
    }
}
