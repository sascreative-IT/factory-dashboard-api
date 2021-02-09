<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PackingListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/packing-list/{merch_order_id}', PackingListController::class)->name("web.packing-list");
