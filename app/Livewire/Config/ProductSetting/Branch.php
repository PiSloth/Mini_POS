<?php

namespace App\Livewire\Config\ProductSetting;

use App\Models\Branch as ModelsBranch;
use Exception;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Branch extends Component
{
    use WireUiActions;
    public $name;
    public $address;
    public $edit_id;
    public $delete_id;


    public function create()
    {
        $validated = $this->validate([
            'name' => 'required',
            'address' => 'nullable'
        ]);

        ModelsBranch::create($validated);
        $this->dispatch('closeModal', 'newModal');


        $this->reset('name', 'address');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Branch creation was successfully.'
        ]);
    }

    public function update()
    {
        $branch = ModelsBranch::find($this->edit_id);
        $branch->update([
            'name' => $this->name,
            'address' => $this->address,
        ]);

        $this->dispatch('closeModal', 'newModal');

        $this->reset('name', 'address', 'edit_id');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Branch updated was successfully.'
        ]);
    }

    public function edit($id)
    {
        $query = ModelsBranch::find($id);
        $this->name = $query->name;
        $this->address = $query->address;
        $this->edit_id = $id;

        $this->dispatch('openModal', 'newModal');
    }

    public function cancleEdit()
    {
        $this->reset('name', 'address', 'edit_id');
    }

    // Delection

    public function setDelete($id)
    {
        $this->delete_id = $id;
    }

    public function cancleDelete()
    {
        $this->reset('delete_id');
    }

    public function  delete($id)
    {

        try {
            ModelsBranch::find($id)->delete();
        } catch (Exception $e) {
            if ($e->getCode() == '23000') {
                $this->dialog()->show([
                    'icon' => 'error',
                    'title' => 'Failed!',
                    'description' => 'Can\'t delete this item coz of use in another record.'
                ]);
                $this->dispatch('close-modal', 'confirm-branch-delete');
                return;
            }
        }

        $this->dispatch('closeModal', 'confirm-branch-delete');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Branch deleted was successfully.'
        ]);
    }

    public function render()
    {
        return view('livewire.config.product-setting.branch', [
            'branches' => ModelsBranch::all(),
        ]);
    }
}
