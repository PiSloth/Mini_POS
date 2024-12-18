<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function price()
    {
        return $this->hasOne(Price::class);
    }

    public function productImage()
    {
        return $this->hasOne(ProductImage::class);
    }
}
