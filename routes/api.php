<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentsController;
use App\Http\Controllers\DepartmentsTypeController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SellOrderController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TruckController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletChargeOrderController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::prefix('User')->group(function () {
    Route::post("/AccountVerify", [UserController::class, "verifyAccountRequest"]);
    Route::post("/verify", [UserController::class, "verify"]);
    Route::post('/login', [UserController::class, 'login']);

    Route::middleware(['auth:api'])->group(function () {
        Route::put("/ProfileUpdate", [UserController::class, "ProfileUpdate"]);
        Route::get("/MyProfile", [UserController::class, "MyProfile"]);
        Route::get('/ResetPasswordRequest', [UserController::class, 'ResetPasswordRequest']);
        Route::post('/ResetPassword', [UserController::class, 'ResetPassword']);
        Route::get('/logout', [UserController::class, 'logout']);


    });

});

Route::prefix('Admin')->group(function () {
    Route::post('/Signup', [AdminsController::class, 'signUp']);
    Route::post("/AccountVerify", [AdminsController::class, "verifyAccountRequest"]);
    Route::post("/verify", [AdminsController::class, "verify"]);
    Route::post('/login', [AdminsController::class, 'login']);

    Route::middleware(['auth:admin-api'])->group(function () {
    Route::get("/email/AdminVerify", [AdminsController::class, "AdminVerify"]);
    Route::get("/email/resend", [AdminsController::class, "resend"]);
    Route::put("/ProfileUpdate", [AdminsController::class, "ProfileUpdate"]);
    Route::get("/MyProfile", [AdminsController::class, "MyProfile"]);
    Route::get('ResetPasswordRequest', [AdminsController::class, 'ResetPasswordRequest']);
    Route::post('ResetPassword', [AdminsController::class, 'ResetPassword']);
    Route::post('AddSupervisor', [AdminsController::class, 'AddSupervisor']);
    Route::post('/AddSupplier', [UserController::class, 'AddSupplier']);
    Route::post('/AddSeller', [UserController::class, 'AddSeller']);
    Route::post('/AddAccountant', [UserController::class, 'AddAccountant']);
    Route::get('/getAllSupervisor', [AdminsController::class, 'getAllSupervisor']);
    Route::get('/getAllUsersAndAdmins', [AdminsController::class, 'getAllUsersAndAdmins']);
    Route::delete('deleteUserAccount/{user}',[AdminsController::class,"deleteUserAccount"]);
    Route::get('/logout', [AdminsController::class, 'logout']);


    //sales
        Route::get('Sale',[SaleController::class,'index']);
        //import product
        Route::get('AllImport',[SaleController::class,'showImportedProducts']);


        //Accountant
    Route::prefix('Accountant')->group(function () {
        Route::get('bill',[BillController::class,'index']);
        Route::get('showAllAccountant',[AdminsController::class,'showAllAccountant']);
        Route::get('listWalletChargeOrders', [WalletChargeOrderController::class, 'index']);
        Route::get('AcceptOrder/{walletChargeOrder}', [WalletChargeOrderController::class, 'Accept']);
        Route::get('declineOrder/{walletChargeOrder}', [WalletChargeOrderController::class, 'decline']);
    });

    //category
    Route::prefix('category')->group(function () {
        Route::get('', [CategoryController::class, 'index']);
        Route::post('', [CategoryController::class, 'store']);
        Route::put('/{category}', [CategoryController::class, 'update']);
        Route::delete('/{category}', [CategoryController::class, 'destroy']);
    });

    //department type
    Route::prefix('Department_type')->group(function () {
        Route::get('', [DepartmentsTypeController::class, 'index']);
        Route::post('', [DepartmentsTypeController::class, 'store']);
        Route::put('/{Department_type}', [DepartmentsTypeController::class, 'update']);
        Route::delete('/{Department_type}', [DepartmentsTypeController::class, 'destroy']);
    });

    //department
    Route::prefix('Department')->group(function () {
        Route::get('', [DepartmentsController::class, 'index']);
        Route::post('', [DepartmentsController::class, 'store']);
        Route::put('/{Department}', [DepartmentsController::class, 'update']);
        Route::delete('/{Department}', [DepartmentsController::class, 'destroy']);
    });

    //product
    Route::prefix('product')->group(function () {
        Route::get('', [ProductController::class, 'index']);
        Route::post('', [ProductController::class, 'store']);
        Route::put('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });

    //staff
    Route::prefix('staff')->group(function () {
        Route::get('', [StaffController::class, 'index']);
        Route::post('', [StaffController::class, 'store']);
        Route::put('/{staff}', [StaffController::class, 'update']);
        Route::delete('/{staff}', [StaffController::class, 'destroy']);
    });
    //truck
    Route::prefix('truck')->group(function () {
        Route::get('', [TruckController::class, 'index']);
        Route::post('', [TruckController::class, 'store']);
        Route::put('/{truck}', [TruckController::class, 'update']);
        Route::delete('/{truck}', [TruckController::class, 'destroy']);
    });

    //reservation
    Route::prefix('reservation/')->group(function () {

        Route::get('/check_sell_order_ability/{sellOrder}',[ReservationController::class,'check_sell_order_ability']);
        Route::get('/{department_id}', [ReservationController::class, 'display_not_available_Reservation']);
        Route::get('/staff/{department_id}', [ReservationController::class, 'display_available_staff']);
        Route::get('/truck/{department_id}', [ReservationController::class, 'display_available_trucks']);

    });
    //orders for supervisor
    Route::prefix('orders/')->group(function () {

        Route::prefix('supplier/')->group(function () {
            Route::post('accept/{order}', [OrderController::class, 'accept_order']);
            Route::put('reject/{order}', [OrderController::class, 'reject_order']);
            Route::post('receipt/{order}', [OrderController::class, 'receipt_confirmation']);
            Route::get('reject/{department}', [OrderController::class, 'show_the_reject_order']);
            Route::get('waiting/{department}', [OrderController::class, 'show_the_waiting_order']);
            Route::get('received/{department}', [OrderController::class, 'show_the_received_order']);
            Route::get('acceptedNotReceived/{department}', [OrderController::class, 'show_the_accept_and_not_received_order']);
        });

        Route::prefix('Seller/')->group(function () {
            Route::post('accept/{order}', [SellOrderController::class, 'accept_sell_order']);
            Route::get('reject/{order}', [SellOrderController::class, 'reject_sell_order']);
            Route::get('waiting/{department}', [SellOrderController::class, 'WaitingOrders']);
            Route::get('showRejected/{department}', [SellOrderController::class, 'RejectedOrders']);
            Route::get('acceptedNotReceived/{department}', [SellOrderController::class, 'ShowAcceptedNotReceived']);
        });


    });


     });
});

Route::middleware(['auth:api'])->group(function () {

    //category for user
    Route::prefix('category')->group(function () {
        Route::get('', [CategoryController::class, 'index']);
    });

        Route::prefix('Seller/')->group(function () {

        //charge Wallet requests
        Route::post('chargeWalletRequest/{wallet}', [WalletChargeOrderController::class, 'chargeWalletRequest']);
        Route::get('showMyWallet', [WalletController::class, 'showMyWallet']);

        //Sell Order
        Route::prefix('SellOrder/')->group(function () {

            Route::get('{department}/Imports', [SellOrderController::class, 'ShowImportProduct']);

            Route::post('{department}/', [SellOrderController::class, 'store']);
            Route::put('{sell_Order}', [SellOrderController::class, 'update']);
            Route::delete('{sell_Order}', [SellOrderController::class, 'destroy']);

            Route::get('showAcceptedOrders/{department}', [SellOrderController::class, 'showAcceptedOrders']);
            Route::get('showPendedOrders/{department}', [SellOrderController::class, 'showPendedOrders']);
            Route::get('showAcceptedReceivedOrders/{department}', [SellOrderController::class, 'showAcceptedReceivedOrders']);
            Route::get('showRejectedOrders/{department}', [SellOrderController::class, 'showRejectedOrders']);
            Route::get('ReceiveOrder/{sell_Order}', [SellOrderController::class, 'ReceiveOrder']);
            Route::post('SellOrRewindOrder/{sellOrder}', [SellOrderController::class, 'SellOrRewindOrder']);

        });

    });

    //SupplyOrder
    Route::prefix('SupplyOrder/')->group(function () {
        Route::get('/show product/{department_id}', [\App\Http\Controllers\OrderController::class, 'display_all_product_in_department']);
        Route::post('/{department}/add', [\App\Http\Controllers\OrderController::class, 'add_order_to_supply']);
        Route::get('/reject order/{department}', [\App\Http\Controllers\OrderController::class, 'show_my_reject_order']);
        Route::get('/waiting order/{department}', [\App\Http\Controllers\OrderController::class, 'show_my_waiting_order']);
        Route::get('/received order/{department}', [\App\Http\Controllers\OrderController::class, 'show_my_received_order']);
        Route::get('/accept not received order/{department}', [\App\Http\Controllers\OrderController::class, 'show_my_accept_and_not_received_order']);

    });


});


