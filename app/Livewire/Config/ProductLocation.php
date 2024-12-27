<?php

namespace App\Livewire\Config;

use App\Models\BranchProductLocation;
use App\Models\ItemLocation;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProductLocation extends Component
{
    use WireUiActions;
    public $name;
    public $description;


    public function createLocation()
    {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        ItemLocation::create($validated);
        $this->reset('name', 'description');
        $this->dispatch('closeModal', 'newModal');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Created',
            'description' => 'New location successfully added.'
        ]);
    }
    public function render()
    {
        return view('livewire.config.product-location', [
            'locations' => ItemLocation::all(),
        ]);
    }
}
