<?php

namespace App\Models\Product;

use App\Models\Category\Category;
use App\Models\User\User;
use App\Utils\Tables\ETables;
use App\Utils\Tables\Product\ProductColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = ETables::PRODUCT->value;

    protected $fillable = [
        ProductColumn::USER_ID,
        ProductColumn::TITLE,
        ProductColumn::DESCRIPTION,
        ProductColumn::PRICE,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
