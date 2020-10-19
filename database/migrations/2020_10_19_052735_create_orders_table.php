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
        Schema::create(
            'orders',
            function (Blueprint $table) {
                $table->id();
                $table->integer('merch_order_id');
                $table->string('comment')->nullable();
                $table->string('sponsor_code')->nullable();
                $table->float('sponsor_amount')->nullable()->default(0);
                $table->float('shipping_cost')->nullable()->default(0);
                $table->float('cart_total');
                $table->float('total');
                $table->boolean('status');
                $table->boolean('is_active');
                $table->string('pickup_store')->nullable();
                $table->string('shipping_address');
                $table->string('billing_address');
                $table->string('grade');
                $table->string('customer_name');
                $table->string('customer_email');
                $table->string('event_name');
                $table->string('club_name');
                $table->enum('status_at_factory', ['Pending','In-Progress','Half-completed','Completed','Shipped'])->nullable()->default("Pending");
                $table->softDeletes();
                $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
}
