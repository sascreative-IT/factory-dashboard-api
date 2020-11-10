<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemVariationValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_variation_values', function (Blueprint $table) {
            $table->id();
            $table->string('attribute_name');
            $table->string('attribute_value_name')->nullable();
            $table->float('attribute_value_price')->nullable()->default(0);
            $table->unsignedBigInteger('order_item_variation_id')->nullable();
            $table->integer('qty')->nullable()->default(1);
            $table->timestamps();

            $table->foreign('order_item_variation_id')
                ->references('id')->on('order_item_variations')
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
        Schema::dropIfExists('order_item_variation_values');
    }
}
