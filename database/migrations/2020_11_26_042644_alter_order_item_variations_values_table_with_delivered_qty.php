<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderItemVariationsValuesTableWithDeliveredQty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item_variation_values', function (Blueprint $table) {
            $table->integer('delivered_qty')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_item_variation_values', function (Blueprint $table) {
            $table->dropColumn('delivered_qty');
        });
    }
}
