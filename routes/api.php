<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::get('/orders', [OrderController::class, 'index'])->name("orders.index");
Route::get('/orders/{merchOrderId}', [OrderController::class, 'show'])->name("orders.show");
Route::put('/orders/{merchOrderId}', [OrderController::class, 'updateStatus'])->name("orders.updateStatus");
Route::post('/orders/comments', [OrderController::class, 'addComment'])->name("orders.comments.create");

