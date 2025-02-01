<?php

use App\Utils\Tables\Category\CategoryColumn;
use App\Utils\Tables\Category\CategoryProductColumn;
use App\Utils\Tables\ETables;
use App\Utils\Tables\Product\ProductColumn;
use App\Utils\Tables\User\UserColumn;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(ETables::PRODUCT->value, function (Blueprint $table) {
            $table->id();

            $table->foreignId(ProductColumn::USER_ID)->index();
            $table->foreign(ProductColumn::USER_ID)->on(ETables::USER->value)
                ->references(UserColumn::ID)->onDelete('cascade');

            $table->string(ProductColumn::TITLE);
            $table->text(ProductColumn::DESCRIPTION)->nullable();
            $table->integer(ProductColumn::PRICE)->default(0);

            $table->timestamps();
        });

        Schema::create(ETables::CATEGORY->value, function (Blueprint $table) {
            $table->id();
            $table->string(ProductColumn::TITLE);
            $table->timestamps();
        });

        Schema::create(ETables::CATEGORY_PRODUCT->value, function (Blueprint $table) {
            $table->id();

            $table->foreignId(CategoryProductColumn::PRODUCT_ID)->index();
            $table->foreign(CategoryProductColumn::PRODUCT_ID)->on(ETables::PRODUCT->value)
                ->references(ProductColumn::ID)->onDelete('cascade');

            $table->foreignId(CategoryProductColumn::CATEGORY_ID)->index();
            $table->foreign(CategoryProductColumn::CATEGORY_ID)->on(ETables::CATEGORY->value)
                ->references(CategoryColumn::ID)->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ETables::CATEGORY_PRODUCT->value);
        Schema::dropIfExists(ETables::PRODUCT->value);
        Schema::dropIfExists(ETables::CATEGORY->value);
    }
};
