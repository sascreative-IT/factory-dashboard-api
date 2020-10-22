<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOrdersTablesWithEnabledForWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'orders',
            function (Blueprint $table) {
                $table->enum('enabled_for_warehouse', ['Yes', 'No'])->default('No')->after('status_at_factory');
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
            'orders',
            function (Blueprint $table) {
                $table->dropColumn('enabled_for_warehouse');
            }
        );
    }
}
