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
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned()->comment('Foreign key from table restaurants');
            $table->string('name');
            $table->double('amount', 15, 8);
            $table->string('image')->nullable();
            $table->string('category');
            $table->longText('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->softDeletes();
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
