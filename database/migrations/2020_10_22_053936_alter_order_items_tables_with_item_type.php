<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrderItemsTablesWithItemType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'order_items',
            function (Blueprint $table) {
                $table->enum('item_type', ['Local', 'Factory'])->nullable()->default(null);
                $table->string('supplier')->nullable();
                $table->enum('supplier_status',['Received','Pending','Half Received','Completed','In Progress'])->nullable();
                $table->string('embellishment_supplier')->nullable();
                $table->enum('embellishment_status',['Sent To Embellisher','Pending','Received','Half Received','Completed'])->nullable();
                $table->enum('factory_status',['Half Received','Received','Completed','In Progress'])->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'order_items',
            function (Blueprint $table) {
                $table->dropColumn('item_type');
                $table->dropColumn('supplier');
                $table->dropColumn('embellishment_supplier');
                $table->dropColumn('factory_status');
            }
        );
    }
}
