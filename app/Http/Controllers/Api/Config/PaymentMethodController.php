<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return PaymentMethod::query()
            ->select('id', 'name')
            ->orderBy('name')
            ->when(
                $request->search,
                fn(Builder $query) => $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', "%{$request->search}%");
                })

            )
            ->when(
                $request->exists('selected'),
                fn(Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                // fn (Builder $query) => $query->limit(10)
            )
            ->get();
    }
}
