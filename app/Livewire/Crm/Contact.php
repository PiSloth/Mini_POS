<?php

namespace App\Livewire\Crm;

use App\Models\Customer;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Contact extends Component
{
    use WireUiActions;
    public $name;
    public $phone;
    public $address;
    public $edit_name;
    public $edit_phone;
    public $edit_address;
    public $edit_id;



    public function initializeId($id)
    {
        $this->edit_id = $id;

        $query = Customer::find($id);

        $this->edit_name = $query->name;
        $this->edit_phone = $query->phone;
        $this->edit_address = $query->address;

        $this->dispatch('openModal', 'editModal');
    }

    public function createCustomer()
    {
        $validated = $this->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'nullable'
        ]);

        Customer::create($validated);
        $this->reset('name', 'phone', 'address');
        $this->dispatch('closeModal', 'newModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Created',
            'description' => 'New customer successfully added.',
        ]);
    }

    public function updateCustomer()
    {
        $query = Customer::find($this->edit_id);
        $query->update([
            'name' => $this->edit_name,
            'phone' => $this->edit_phone,
            'address' => $this->edit_address
        ]);

        $this->reset('edit_name', 'edit_phone', 'edit_address');
        $this->dispatch('closeModal', 'editModal');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Updated',
            'description' => 'Customer information successfully updated.'
        ]);
    }

    public function render()
    {
        return view('livewire.crm.contant', [
            'customers' => Customer::all(),
        ]);
    }
}
