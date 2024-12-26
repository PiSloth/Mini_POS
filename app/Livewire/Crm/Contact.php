<?php

namespace App\Livewire\Crm;

use App\Models\Customer;
use Livewire\Component;

class Contact extends Component
{
    public function render()
    {
        return view('livewire.crm.contant', [
            'customer' => Customer::all(),
        ]);
    }
}
