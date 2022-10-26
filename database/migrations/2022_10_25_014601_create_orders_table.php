<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('restaurant_id')->unsigned()->comment('Foreign key from table restaurants');
            $table->bigInteger('product_id')->unsigned()->comment('Foreign key from table products');
            $table->bigInteger('user_id')->unsigned()->comment('Foreign key from table users');
            $table->double('amount', 15, 8);
            $table->integer('quantity');
            $table->string('status')->default('pending');
            $table->boolean('is_delivered')->default(false);

            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('orders');
    }
}
