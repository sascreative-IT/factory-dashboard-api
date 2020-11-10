<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_variations', function (Blueprint $table) {
            $table->id();
            $table->float('extra_cost');
            $table->unsignedBigInteger('order_item_id');
            $table->timestamps();

            $table->foreign('order_item_id')
                ->references('id')->on('order_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_variations');
    }
}
