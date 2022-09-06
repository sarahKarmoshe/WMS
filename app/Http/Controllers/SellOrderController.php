<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Bill;
use App\Models\Department;
use App\Models\Import;
use App\Models\Sale;
use App\Models\SellOrder;
use App\Http\Requests\StoreSell_OrderRequest;
use App\Http\Requests\UpdateSell_OrderRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SellOrderController extends Controller
{
    // ------- orders management for admin ----------------------

    public function accept_sell_order(SellOrder $order, StoreReservationRequest $request)
    {
//    accept sell order for admin

        //authorization
        $department_id=$order->department_id;
        $department=Department::query()->find($department_id);
        $department_admin_id=$department->admin_id;
        $this->authorize('view', [SellOrder::class, Auth::guard('admin-api')->user()->id, $department_admin_id]);


        $order->update([
            'is_Accepted' => true
        ]);

        $result = ReservationController::add_Reservation($request);

        if ($result == false) {
            return response()->json('the date which you chose is not available', Response::HTTP_BAD_REQUEST);

        }
        return response()->json($order, Response::HTTP_OK);

    }

    public function reject_sell_order(SellOrder $order)
    {
        //authorization
        $department_id=$order->department_id;
        $department=Department::query()->find($department_id);
        $department_admin_id=$department->admin_id;

        $this->authorize('view', [SellOrder::class, Auth::guard('admin-api')->user()->id, $department_admin_id]);


        $order->update([
            'is_Accepted' => false
        ]);

        return Response()->json($order, Response::HTTP_OK);
    }

    // -------show orders for seller ----------------------

    public function showPendedOrders($department)
    {
        //show all pended sell orders for seller

        //authorization just have to be seller to do this action
        $this->authorize('viewAny',[SellOrder::class,Auth::guard('api')->user()]);

        $order = SellOrder::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_Accepted', '=', NULL)
            ->where('department_id', '=', $department)
            ->with('Import')->get();

        return response()->json($order, Response::HTTP_OK);

    }

    public function showAcceptedOrders($department)
    {
        //show all accepted and not received yet sell orders for seller

        //authorization just have to be seller to do this action
        $this->authorize('viewAny',[SellOrder::class,Auth::guard('api')->user()]);

        $order = SellOrder::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_Received', '=', NULL)
            ->where('is_Accepted', '=', true)
            ->where('department_id', '=', $department)
            ->with('Import')
            ->get();


        return response()->json($order, Response::HTTP_OK);

    }

    public function showRejectedOrders($department)
    {
        //show all rejected sell orders for seller

        //authorization just have to be seller to do this action
        $this->authorize('viewAny',[SellOrder::class,Auth::guard('api')->user()]);

        $order = SellOrder::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_Accepted', '=', false)
            ->where('department_id', '=', $department)
            ->with('Import')->get();


        return response()->json($order, Response::HTTP_OK);


    }

    public function showAcceptedReceivedOrders($department)
    {
        //show all accepted and received sell orders for seller

        //authorization just have to be seller to do this action
        $this->authorize('viewAny',[SellOrder::class,Auth::guard('api')->user()]);

        $order = SellOrder::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_Received', '=', true)
            ->where('is_Accepted', '=', true)
            ->where('department_id', '=', $department)
            ->with('Import')->get();


        return response()->json($order, Response::HTTP_OK);

    }


    // ----------here show sell order for admin ----------


    public function WaitingOrders($department_id)
    {
        //show all waiting sell orders for admin

        $this->authorize('view', [SellOrder::class, Auth::guard('admin-api')->user()->id, $department_id]);

        $order = SellOrder::query()
            ->where('is_Received', '=', NULL)
            ->where('is_Accepted', '=', NULL)
            ->where('department_id', '=', $department_id)
            ->with('Import')
            ->with('User')
            ->get();


        return response()->json($order, Response::HTTP_OK);

    }

    public function RejectedOrders($department) //admin
    {
        //show all rejected sell orders for admin

        //dont forget authorization
        $this->authorize('view', [SellOrder::class, Auth::guard('admin-api')->user()->id, $department]);


        $order = SellOrder::query()
            ->where('is_Accepted', '=', false)
            ->where('department_id', '=', $department)
            ->with('Import')
            ->with('User')
            ->get();


        return response()->json($order, Response::HTTP_OK);

    }

    public function ShowAcceptedNotReceived($department)
    {
        //show all accepted and not received sell orders for admin

        //dont forget authorization
        $this->authorize('view', [SellOrder::class, Auth::guard('admin-api')->user()->id, $department]);


        $order = SellOrder::query()
            ->where('is_Received', '=', NULL)
            ->where('is_Accepted', '=', true)
            ->where('department_id', '=', $department)
            ->with('Import')
            ->with('User')
            ->get();

        return response()->json($order, Response::HTTP_OK);

    }

// ------- orders management for seller ----------------------

    public function ShowImportProduct(Department $department)
    {
        //authorization just have to be seller to do this action
        $this->authorize('viewAny',[SellOrder::class,Auth::guard('api')->user()]);

        //show all products stored in specified department to sell them.
        $products=Import::query()->where('imports.department_id','=',$department->id)
            ->join('products','imports.product_id','=','products.id')
            ->get();

        return response()->json($products,Response::HTTP_OK);

    }

    public function store(StoreSell_OrderRequest $request, Department $department) //add sell order
    {
        //authorization just have to be seller to do this action
        $this->authorize('viewAny',[SellOrder::class,Auth::guard('api')->user()]);

        $sell_order = SellOrder::query()->create([
            'user_id' => Auth::guard('api')->id(),
            'department_id' => $department->id
        ]);
        foreach ($request->products_list as $item) {
            $product = [
                'import_id' => $item['import_id'],
                'sell_order_id' => $sell_order->id,
                'quantity' => $item['quantity'],
            ];
            $sell_order->import()->SyncWithoutDetaching([$product]);
        }

        return response()->json($sell_order, Response::HTTP_CREATED);

    }


    public function ReceiveOrder(SellOrder $sell_Order)
    {

        //authorization
        $this->authorize('update', [SellOrder::class, Auth::guard('api')->user()->id, $sell_Order->user_id]);

        if (!$sell_Order->is_Accepted) {
            return response()->json('you can not receive this product yet', Response::HTTP_BAD_REQUEST);
        }
        $sell_Order->update(['is_Received' => True]);

        return response()->json('order receiving operation has done', Response::HTTP_OK);

    }

    public function SellOrRewindOrder(SellOrder $sellOrder, Request $request)
    {
        //authorization
        $this->authorize('update', [SellOrder::class, Auth::guard('api')->user()->id, $sellOrder->user_id]);

        $validator = Validator::make($request->all(), [
            'products' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $bill = Bill::query()->create();

        $order_products = SellOrder::query()->where('sell_orders.id', '=', $sellOrder->id)
            ->join('imports_sell_orders', 'sell_orders.id', '=', 'imports_sell_orders.sell_order_id')
            ->join('imports', 'imports_sell_orders.import_id', '=', 'imports.id')
            ->join('products', 'imports.product_id', '=', 'products.id')
            ->select('imports_sell_orders.id as pivot_id', 'products.id as product_id'
                , 'imports_sell_orders.quantity as quantity', 'imports.supply_price as price'
                , 'imports.id as import_id', 'sell_orders.department_id')
            ->get();

        //   here you have to put for loop and do sell for each product in this order

        $count = count($order_products);
        $totalPrice = 0;
        for ($i = 0; $i < $count; $i++) {
            $incomming_quantity = $request->products[$i]['sold'] + $request->products[$i]['returned'];
            if ($order_products[$i]['quantity'] == $incomming_quantity) {
                if ($request->products[$i]['sold'] != 0) {
                    $sell = Sale::query()->create([
                        'bill_id' => $bill->id,
                        'sale_price' => $order_products[$i]['price'] * $order_products[$i]['quantity'],
                        'sales_quantity' => $request->products[$i]['sold'],
                        'imports_sell_orders_id' => $request->products[$i]['pivot_id']
                    ]);
                    $totalPrice = $totalPrice + $order_products[$i]['price'] * $order_products[$i]['quantity'];

                    if ($request->products[$i]['returned'] == 0) {
                        Import::query()->where('id', '=', $order_products[$i]['import_id'])->update([
                            'is_returns' => -1
                        ]);
                    }

                }

                if ($request->products[$i]['returned'] != 0) {

                    Import::query()->where('id', '=', $order_products[$i]['import_id'])->update([
                        'is_returns' => $request->products[$i]['returned']
                    ]);
                }
            }
            else return response()->json('Wrong values' ,Response::HTTP_OK);
        }
        $bill->update(['total_price' => $totalPrice]);
        $id = $order_products[0]['department_id'];

        $department = Department::query()->find($id)->increment('payments', $totalPrice);

        $wallet=Wallet::query()->where('user_id','=',Auth::guard('api')->id())->get()->first();
        $wallet->decrement('value',$totalPrice);


        return response()->json('operation has been done successfully ', Response::HTTP_OK);

    }

    public function update(UpdateSell_OrderRequest $request, SellOrder $sell_Order)
    {
        // can not update accepted orders
        if ($sell_Order->is_Accepted == true) {
            return response()->json('you can no longer do this action', Response::HTTP_OK);
        }

        //authorization
        $this->authorize('update', [SellOrder::class, Auth::guard('api')->user()->id, $sell_Order->user_id]);


        foreach ($request->products_list as $item) {
            $product = [
                'import_id' => $item['import_id'],
                'sell_order_id' => $sell_Order->id,
                'quantity' => $item['quantity'],
            ];

            $sell_Order->import()->SyncWithoutDetaching([$product]);
        }
        return response()->json($sell_Order, Response::HTTP_OK);

    }

    public function destroy(SellOrder $sell_Order)
    {
        //can not delete accepted orders
        if ($sell_Order->is_Accepted == true) {
            return response()->json('you can no longer do this action', Response::HTTP_OK);
        }

        //authorization
        $this->authorize('update', [SellOrder::class, Auth::guard('api')->user()->id, $sell_Order->user_id]);

        $sell_Order->delete();

        return response()->json('your sell order deleted successfully', Response::HTTP_OK);

    }


}
