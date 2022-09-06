<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Category;
use App\Models\Department;
use App\Models\Expiry_date;
use App\Models\Import;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Product;
use Carbon\Carbon;
use Database\Seeders\SellOrderSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\True_;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */


    public function display_all_product_in_department($department_id)
    {
        $product = Product::query()->where('department_id', '=', $department_id)
            ->get(['id', 'name','description', 'photo', 'department_id', 'category_id']);

        return Response()->json($product, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreOrderRequest $request
     * @return \Illuminate\Http\jsonResponse
     */


    public function add_order_to_supply(StoreOrderRequest $request, Department $department)
    {
        $Order = Order::query()->create(
            [
                'user_id' => Auth::guard('api')->id(),
                'department_id' => $department->id
            ]);
        foreach ($request->products as $item) {
            $expiry_date = Expiry_date::query()->where('expiration_date', '=', $item['expiry_date'])->pluck('id')->first();
            if ($expiry_date) {

                $exp_id = $expiry_date;

            } else {
                $exp = Expiry_date::query()->create([
                    'expiration_date' => $item['expiry_date']
                ]);
                $exp_id = $exp['id'];

            }

            $product = [
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'expiry_date_id' => $exp_id
            ];


            $Order->product()->SyncWithoutDetaching([$product]);

        }

        return Response()->json($Order, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\jsonResponse
     */

    public function show_my_reject_order($department_id)
    {
        $order=Order::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_accepted', '=', false)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')->get();

        return Response()->json($order, Response::HTTP_OK);
    }

    public function show_my_waiting_order($department_id)
    {

        $order=Order::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_received', '=',NULL)
            ->where('is_accepted', '=', NULL)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')->get();


        return Response()->json($order, Response::HTTP_OK);
    }

    public function show_my_received_order($department_id)

    {
        $order=Order::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_received', '=',true)
            ->where('is_accepted', '=', true)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')->get();

        return Response()->json($order, Response::HTTP_OK);
    }

    public function show_my_accept_and_not_received_order($department_id)
    {

        $order=Order::query()
            ->where('user_id', '=', Auth::guard('api')->id())
            ->where('is_received', '=',NULL)
            ->where('is_accepted', '=', true)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')->get();

        return Response()->json($order, Response::HTTP_OK);
    }


    //for supervisor

    public function accept_order(Order $order, StoreReservationRequest $request)
    {

        $order->update([
            'is_accepted' => true
        ]);

        $result = ReservationController::add_Reservation($request);

        if ($result == false) {
            return response()->json('the date which you chose is not available', Response::HTTP_BAD_REQUEST);

        }
        return response()->json($order, Response::HTTP_OK);

    }

    public function show_the_reject_order($department_id)

    {
        $order=Order::query()
            ->where('is_accepted', '=', false)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')
            ->with('User')
            ->get();


        return Response()->json($order, Response::HTTP_OK);
    }

    public function show_the_waiting_order($department_id)
    {

        $order=Order::query()
            ->where('is_received', '=',NULL)
            ->where('is_accepted', '=', NULL)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')
            ->with('User')
            ->get();

        return Response()->json($order, Response::HTTP_OK);
    }

    public function show_the_received_order($department_id)

    {
        $order=Order::query()
            ->where('is_received', '=',true)
            ->where('is_accepted', '=', true)
            ->where('orders.department_id', '=', $department_id)
            ->with('Product')
            ->with('User')
            ->get();

        return Response()->json($order, Response::HTTP_OK);
    }

    public function show_the_accept_and_not_received_order($department_id)
    {
        $order=Order::query()
                ->where('is_received', '=', NULL)
                ->where('is_accepted', '=', True)
                ->where('orders.department_id', '=', $department_id)
            ->with('Product')
            ->with('User')
            ->get();

        return Response()->json($order, Response::HTTP_OK);
    }


    //for superviser

    public function reject_order(Order $order)
    {

        $order->update([
            'is_accepted' => false
        ]);

        return Response()->json($order, Response::HTTP_OK);
    }

    // to receipt confirmation  order and add product to the import table
    public function receipt_confirmation(Order $order, UpdateOrderRequest $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => ['required'],
                'department_id' => ['required'],

            ]);
        if ($validator->failed()) {
            return Response()->json($validator->errors(), 'you have error');
        }

        $order->update([
            'is_received' => true
        ]);
        $new_import = OrderController::add_to_import_with_new_price($order, $request);

        $result['order'] = $order;
        $result['new_import'] = $new_import;

        return Response()->json($result, Response::HTTP_OK);
    }


//calculate new price to add product to the imports
    public function add_to_import_with_new_price($order, UpdateOrderRequest $request)
    {
        $order_products = $order->product()->pluck('product_id');


        foreach ($order_products as $item) {

            $category = Product::query()->find($item)->value('category_id');

            $profit_ratio = Category::query()->find($category)->value('profit_ratio');

            $price = DB::table('order_product')->where('product_id', '=', $item)
                ->where('order_id', '=', $order->id)->value('price');

            $quantity = DB::table('order_product')->where('product_id', '=', $item)
                ->where('order_id', '=', $order->id)->value('quantity');

            $new_exist_quantity=Product::query()->where('id','=',$item)->increment('exist_quantity',$quantity);
            $final_price = ($profit_ratio * $price ) / 100;

            $import = Import::query()->create([

                'supply_price' => $final_price,
                'supply_quantity' => $quantity,
                'product_id' => $item,
                'user_id' => $request->user_id,
                'department_id' => $request->department_id,
                'is_returns' => false

            ]);

        }

        return $import;

    }
}
