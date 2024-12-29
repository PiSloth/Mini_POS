<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubCategoryController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return SubCategory::query()
            ->select(
                'sub_categories.id',
                DB::raw("concat(categories.code, ' / ', sub_categories.code, ' / ', sub_categories.name) as sub_category")
            )
            ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
            ->orderBy('sub_categories.name')
            ->when(
                $request->search,
                fn(Builder $query) => $query->where(function ($query) use ($request) {
                    $query->where('categories.name', 'like', "%{$request->search}%")
                        ->orWhere('sub_categories.name', 'like', "%{$request->search}%")
                        ->orWhere('sub_categories.code', 'like', "%{$request->search}%")
                        ->orWhere('categories.code', 'like', "%{$request->search}%");
                })
            )
            ->when(
                $request->exists('selected'),
                fn(Builder $query) => $query->whereIn('sub_categories.id', $request->input('selected', []))
            )
            ->get();
    }
}
