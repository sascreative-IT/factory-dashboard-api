<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use App\Services\GeneratePackingList;

class PackingListController extends Controller
{
    public function __invoke($merch_order_id)
    {
        $order = (new OrderResource(Order::with('items', 'comments')->where("merch_order_id", $merch_order_id)->first()))->resolve();
        $objGeneratePackingList = new GeneratePackingList($order);
        $packingList = $objGeneratePackingList->generateDoc();
        $file_name = $merch_order_id . ".docx";
        try {
            $packingList->save(storage_path("packing-list/".$file_name));
        } catch (Exception $e) {
            dd($e);
        }

        return response()->download(storage_path("packing-list/".$file_name));
    }
}
