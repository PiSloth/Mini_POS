<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use App\Models\BranchProduct;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        $validated = $request->validate([
            'search' => 'nullable|string',
            'selected' => 'nullable|array',
        ]);

        return BranchProduct::query()
            ->select(
                'branch_products.id',
                DB::raw("CONCAT(categories.code, sub_categories.code, products.code) AS code"),
                'products.name as name'
            )
            ->leftJoin('products', 'products.id', 'branch_products.product_id')
            ->leftJoin('sub_categories', 'sub_categories.id', 'products.sub_category_id')
            ->leftJoin('categories', 'categories.id', 'sub_categories.category_id')
            ->orderBy('categories.code')
            ->where('branch_products.branch_id', '=', 1)
            ->when(
                $validated['search'] ?? null,
                fn(Builder $query, $search) => $query->where(function ($query) use ($search) {
                    $query->where('categories.code', 'like', "%{$search}%")
                        ->orWhere('sub_categories.code', 'like', "%{$search}%")
                        ->orWhere('products.code', 'like', "%{$search}%")
                        ->orWhere('products.name', 'like', "%{$search}%");
                })
            )
            ->when(
                $validated['selected'] ?? null,
                fn(Builder $query, $selected) => $query->whereIn('branch_products.id', $selected)
            )
            ->get();
    }
}
