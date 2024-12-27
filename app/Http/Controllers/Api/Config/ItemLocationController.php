<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\ItemLocation;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemLocationController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return ItemLocation::query()
            ->select('id', DB::raw("concat(name,' / ',description) as location"))
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
