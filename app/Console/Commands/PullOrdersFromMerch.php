<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PullOrdersFromMerch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pull-orders:merch {from : The from Order ID} {to? : The to Order ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The command is to pull the orders from merch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderIdFrom = $this->argument('from');
        $orderIdTo = $this->argument('to');

        $orders_sql = "SELECT * FROM orders WHERE id >= $orderIdFrom";
        if ($orderIdTo) {
            $orders_sql = "SELECT * FROM orders WHERE id BETWEEN $orderIdFrom AND $orderIdTo";
        }
        $orders = DB::connection('merch')->select($orders_sql);
        foreach ($orders as $order) {
            $isRecordAlreadyExist = Order::where("merch_order_id", $order->id)->first();
            if (!$isRecordAlreadyExist) {
                $user = collect(
                    DB::connection('merch')->select("SELECT name, email FROM users WHERE id=$order->user_id")
                )->first();
                $customer_name = '';
                $customer_email = '';
                if (isset($user)) {
                    $customer_name = $user->name;
                    $customer_email = $user->email;
                }

                $pickup_store = null;
                if (isset($order->pickup_store)) {
                    $order_pickup_store = collect(
                        DB::connection('merch')->select(
                            "SELECT shipping_location FROM shipping_types WHERE id=$order->pickup_store"
                        )
                    )->first();
                    $pickup_store = $order_pickup_store->shipping_location;
                }

                $event_title = null;
                $club_title = null;
                if (isset($order->event_club_id)) {
                    $event_club = collect(
                        DB::connection('merch')->select(
                            "SELECT (SELECT title FROM events WHERE id=event_id) as event_title,(SELECT title FROM clubs WHERE id=club_id) as club_title FROM event_clubs WHERE id=$order->event_club_id"
                        )
                    )->first();
                    $event_title = $event_club->event_title;
                    $club_title = $event_club->club_title;
                }

                $order_items = DB::connection('merch')->select("SELECT * FROM order_items WHERE order_id = $order->id");
                $arrOrderItems = [];
                if ($order_items) {
                    foreach ($order_items as $orderItemIndex=>$order_item) {
                        $item_obj = json_decode($order_item->order_item_obj, true);
                        $item_category = null;
                        if(isset($item_obj['product']['category'])) {
                            $item_category = $item_obj['product']['category'];
                        }

                        array_push($arrOrderItems,[
                            "quantity" => $order_item->quantity,
                            "product_code" => $order_item->product_code,
                            "product_title" => $item_category." - ".$order_item->product_title,
                            "product_price" => ($order_item->quantity * $order_item->product_price) + $order_item->total_extra_cost,
                            "category_title" => $item_category
                        ]);
                    }
                }

                $order_data = [
                    'merch_order_id' => $order->id,
                    'comment' => $order->comment,
                    'sponsor_code' => $order->sponsor_code,
                    'sponsor_amount' => $order->sponsor_amount,
                    'shipping_cost' => $order->shipping_cost,
                    'cart_total' => $order->cart_total,
                    'total' => $order->total,
                    'status' => $order->status,
                    'is_active' => $order->is_active,
                    'pickup_store' => $pickup_store,
                    'shipping_address' => $order->shipping_address,
                    'billing_address' => $order->billing_address,
                    'grade' => $order->grade,
                    'customer_name' => $customer_name,
                    'customer_email' => $customer_email,
                    'event_name' => $event_title,
                    'club_name' => $club_title,
                    'delivery_no' => $order->id."-".rand(100,1000),
                    'job_no' => $order->id.rand(100,1000),
                    'order_date' => $order->created_at
                ];
                $new_order_record = Order::create(
                    $order_data
                );

                if ($new_order_record) {
                    $new_order_record->items()->createMany($arrOrderItems);
                    $this->info("New Order Created, The ID $new_order_record->id");
                }
            }
        }
        return 0;
    }
}
