<?php

namespace App\Models\Category;

use App\Models\Product\Product;
use App\Utils\Tables\Category\CategoryColumn;
use App\Utils\Tables\Category\CategoryProductColumn;
use App\Utils\Tables\ETables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = ETables::CATEGORY->value;

    protected $fillable = [
        CategoryColumn::TITLE
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, ETables::CATEGORY_PRODUCT->value, CategoryProductColumn::CATEGORY_ID, CategoryProductColumn::PRODUCT_ID);
    }
}
