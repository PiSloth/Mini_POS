<?php

namespace App\Livewire\Config\Accounting;

use App\Models\PaymentMethod as ModelsPaymentMethod;
use Exception;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class PaymentMethod extends Component
{
    use WireUiActions;
    public $name;
    public $description;
    public $edit_id;
    public $delete_id;

    public $up_name;
    public $up_description;


    public function create()
    {
        $validated = $this->validate([
            'name' => 'required',
            'description' => 'nullable'
        ]);

        ModelsPaymentMethod::create($validated);
        $this->dispatch('closeModal', 'newModal');


        $this->reset('name', 'description');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Created',
            'description' => 'Payment creation was successfully.'
        ]);
    }

    public function update()
    {
        $branch = ModelsPaymentMethod::find($this->edit_id);
        $branch->update([
            'name' => $this->up_name,
            'description' => $this->up_description,
        ]);

        $this->dispatch('closeModal', 'editModal');

        $this->reset('up_name', 'up_description', 'edit_id');
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Updated',
            'description' => 'Branch updated was successfully.'
        ]);
    }

    public function edit($id)
    {
        $query = ModelsPaymentMethod::find($id);
        $this->up_name = $query->name;
        $this->up_description = $query->description;

        $this->edit_id = $id;

        $this->dispatch('openModal', 'editModal');
    }

    public function cancleEdit()
    {
        $this->reset('up_name', 'up_description', 'edit_id');
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

    public function  delete()
    {

        try {
            ModelsPaymentMethod::find($this->delete_id)->delete();
        } catch (Exception $e) {
            if ($e->getCode() == '23000') {
                $this->dialog()->show([
                    'icon' => 'error',
                    'title' => 'Failed!',
                    'description' => 'Can\'t delete this item coz of use in another record.'
                ]);
                $this->dispatch('close-modal', 'confirm-method-delete');
                return;
            }
        }

        $this->dispatch('closeModal', 'confirm-method-delete');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Branch deleted was successfully.'
        ]);
    }

    #[Title('Payment methods defined')]
    public function render()
    {
        return view('livewire.config.accounting.payment-method', [
            'methods' => ModelsPaymentMethod::orderBy('name')->get(),
        ]);
    }
}
