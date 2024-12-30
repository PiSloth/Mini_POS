<?php

namespace App\Livewire\Sale;

use App\Models\Invoice;
use Livewire\Component;
use Livewire\WithPagination;

class DailyInvoice extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.sale.daily-invoice', [
            'daily_sales' => Invoice::orderBy('id', 'desc')->paginate('5'),
        ]);
    }
}
