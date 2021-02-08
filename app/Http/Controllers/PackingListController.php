<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PackingListController extends Controller
{
    public function __invoke($merch_order_id)
    {
        dd($merch_order_id);
        return view('welcome');
    }
}
