<?php

use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login'])->name("user.login");
Route::get('/orders', [OrderController::class, 'index'])->name("orders.index");
Route::get('/orders/{merchOrderId}', [OrderController::class, 'show'])->name("orders.show");
Route::put('/orders/{merchOrderId}', [OrderController::class, 'updateStatus'])->name("orders.updateStatus");
Route::post('/orders/comments', [OrderController::class, 'addComment'])->name("orders.comments.create");
Route::patch('/orders/{merchOrderId}', [OrderController::class, 'update'])->name("orders.update");
Route::get(
    '/orders/search-warehouse-order/{merchOrderId}',
    [OrderController::class, 'searchWarehouseOrder']
)->name("orders.show.warehouse");
Route::put('/orders/update-delivery-date/{merchOrderId}', [OrderController::class, 'updateDeliveryDate'])->name(
    "orders.updateDeliveryDate"
);
Route::put(
    '/orders/update-delivery-status/{merchOrderId}',
    [OrderController::class, 'updateDeliveryStatus']
)->name("orders.updateDeliveryStatus");
Route::put('/orders/update-order-status/{merchOrderId}', [OrderController::class, 'updateOrderStatus'])->name(
    "orders.updateOrderStatus"
);

// update order items...
Route::put('/order-items/update-item-type/{id}', [OrderItemController::class, 'updateItemType'])->name(
    "order-items.update-item-type"
);
Route::put('/order-items/update-supplier/{id}', [OrderItemController::class, 'updateSupplier'])->name(
    "order-items.update-update-supplier"
);
Route::put(
    '/order-items/update-embellishment-supplier/{id}',
    [OrderItemController::class, 'updateEmbellishmentSupplier']
)->name("order-items.update-embellishment-supplier");

Route::put(
    '/order-items/update-supplier-status/{id}',
    [OrderItemController::class, 'updateSupplierStatus']
)->name(
    "order-items.update-update-supplier-status"
);

Route::put(
    '/order-items/update-embellishment-status/{id}',
    [OrderItemController::class, 'updateEmbellishmentStatus']
)->name(
    "order-items.update-update-embellishment-status"
);

Route::put(
    '/order-items/update-factory-status/{id}',
    [OrderItemController::class, 'updateFactoryStatus']
)->name(
    "order-items.update-factory-status"
);

Route::get('/suppliers', [SupplierController::class, 'index'])->name("suppliers.index");
Route::get('/embellishment-suppliers', [SupplierController::class, 'embellishmentSuppliers'])->name(
    "suppliers.embellishment-suppliers"
);


Route::middleware('auth:sanctum')->group(
    function () {
        Route::put('/orders/test-update-order-status/{merchOrderId}', [OrderController::class, 'updateOrderStatus'])->name(
            "orders.updateOrderStatus"
        );
    }

);

