<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersAndOrdersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            //$table->string('name')->virtualAs('first_name + last_name')->nullable();
            $table->timestamps();
        });    

        if (Schema::hasTable('customers')) {
                
            Schema::create('orders', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('total');
                
                $table->foreignId('customer_id');
                $table->foreign('customer_id')
                        ->references('id')->on('customers')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');

                $table->timestamps();

                // index
                $table->index('customer_id');
            });
        }

        if (Schema::hasTable('orders') &&
            Schema::hasTable('products')
            ) {

            Schema::create('orders_products', function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->foreignId('order_id');
                $table->foreign('order_id')
                        ->references('id')->on('orders')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');

                $table->integer('quantity');//->default(0);
                $table->integer('price');//->default(0);
                
                $table->foreignId('product_id');
                $table->foreign('product_id')
                        ->references('id')->on('products')
                        ->onUpdate('cascade')
                        ->onDelete('cascade');

                $table->timestamps();

                $table->index(['order_id', 'product_id']);

            });    
        }

    }

    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders_products');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
    }
}
