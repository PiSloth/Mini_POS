<?php

namespace App\Livewire\Sale;

use App\Models\Invoice;
use Livewire\Component;

class DailyInvoice extends Component
{
    public function render()
    {
        return view('livewire.sale.daily-invoice', [
            'daily_sales' => Invoice::all(),
        ]);
    }
}
