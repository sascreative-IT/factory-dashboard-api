<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderDeliveryHistory;
use Illuminate\Console\Command;

class ResetOrder extends Command
{

    protected $signature = 'sas:reset-order {id : The Merch Order ID}';


    protected $description = 'The command is to reset the order.';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $orderId = $this->argument('id');
        $order = Order::where("merch_order_id", $orderId)->first();
        if ($order) {
            $local_order_id = $order->id;
            OrderDeliveryHistory::where('order_id', $local_order_id)->delete();
            $order->delete();
            $this->call("pull-orders:merch", ['from' => $orderId]);
            $this->info("The Order ID $orderId successfully reset.");
        } else {
            $this->error("The Order ID $orderId doesn't exist.");
        }

        return 0;
    }
}
