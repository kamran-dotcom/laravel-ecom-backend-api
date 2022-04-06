<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('meta_title');
            $table->string('meta_keyword');
            $table->text('meta_description')->nullable();
            $table->string('selling_price');
            $table->string('original_price');
            $table->string('qty');
            $table->string('brand');
            $table->string('image')->nullable();
            $table->tinyInteger('feature')->nullable();
            $table->tinyInteger('popular')->nullable();
            $table->tinyInteger('status');

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
