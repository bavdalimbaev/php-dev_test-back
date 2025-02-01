<?php

namespace App\Models\Category;

use App\Utils\Tables\Category\CategoryProductColumn;
use App\Utils\Tables\ETables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;

    protected $table = ETables::CATEGORY_PRODUCT->value;

    protected $fillable = [
        CategoryProductColumn::PRODUCT_ID,
        CategoryProductColumn::CATEGORY_ID,
    ];
}
