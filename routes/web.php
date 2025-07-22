<?php
use App\Http\Controllers\productController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\orderController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

Route::get('/uploads/{filename}', function ($filename) {
    $path = '/mnt/data/images/' . $filename;

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    return Response::make($file, 200)->header("Content-Type", $type);
});

Route::get('/',[productController::class,'index']);
Route::get('/table',[productController::class,'table']);

Route::get('/cashiers',function(){
    return view('Layout.cashiers');
});
Route::get('/orders',function(){
    return view('Layout.orders');
});

Route::post('/productCreate',[productController::class,'create']);
Route::post('/productUpdate',[productController::class,'update']);
Route::delete('/productDelete/{id}', [productController::class, 'destroy']);

Route::post('/categoryCreate',[categoryController::class,'create']);
Route::post('/categoryUpdate',[categoryController::class,'update']);
Route::post('/categoryDelete/{id}',[categoryController::class,'destroy']);


Route::post('/orders', [orderController::class, 'store'])->name('orders.store');
Route::get('/orders/latest/{customerId}', [OrderController::class, 'getLatestOrder']);
Route::post('/orders/save', [orderController::class, 'saveOrder']);
Route::post('/orders/{id}/paid',[orderController::class,'status']);
Route::post('/addNewOrder',[orderController::class,'addItemToOrderBulk']);

Route::get('/reports',[orderController::class,'report']);

Route::get('/customers', [orderController::class, 'tableView'])->name('orders.tables');
Route::post('/customersCreate',[customerController::class,'create']);