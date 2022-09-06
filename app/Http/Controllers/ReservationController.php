<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\SellOrder;
use App\Models\Staff;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */

    public function display_not_available_Reservation($department_id)
    {
// i will display the time which has been Reservation
        $Reservation = Reservation::query()->where('department_id', '=', $department_id)->get(['id', 'start_date', 'end_date']);


        return Response()->json($Reservation, Response::HTTP_OK);
    }

    public function display_available_staff($department_id)
    {

        $all_staff = DB::table('reservations_staff')
            ->join('reservations', 'reservations_staff.reservation_id', '=', 'reservations.id')
            ->join('staff', 'reservations_staff.staff_id', '=', 'staff.id')
            ->where('staff.department_id', '=', $department_id)
            ->where('reservations.department_id', '=', $department_id)->get()->pluck('staff_id')->toArray();

        $staff = Staff::query()->get()->where('department_id', '=', $department_id);

        $result = $staff->except($all_staff);

        return Response()->json($result, Response::HTTP_OK);
    }

    public static function check_sell_order_ability($sellOrder)
    {

        $bool =true;
        $import = SellOrder::query()->find($sellOrder)
            ->join('imports_sell_orders', 'sell_orders.id', '=', 'imports_sell_orders.sell_order_id')
            ->join('imports', 'imports_sell_orders.import_id', '=', 'imports.id')
            ->select('imports.id as import_id', 'imports.product_id', 'imports_sell_orders.quantity')
            ->get();

        foreach ($import as $item) {

            $product_id = $item['product_id'];
            $exist_quantity = Product::query()->where('id', '=', $product_id)->value('exist_quantity');
            $min_quantity = Product::query()->where('id', '=', $product_id)->value('min_quantity');
            $sub=$exist_quantity -$item['quantity'];

            if($sub<=$min_quantity) {
               $bool=false;
            }
        }
        if($bool)
        {
            $result['message']='True , you can Accept this order';
            return response()->json($result , Response::HTTP_OK);
        }
        $result['message']='False , you can not Accept this order ';

        return response()->json('', Response::HTTP_OK);

    }

    public function display_available_trucks($department_id)
    {
        {
            $all_trucks = DB::table('reservations_trucks')
                ->join('reservations', 'reservations_trucks.reservation_id', '=', 'reservations.id')
                ->join('trucks', 'reservations_trucks.truck_id', '=', 'trucks.id')
                ->where('trucks.department_id', '=', $department_id)
                ->where('reservations.department_id', '=', $department_id)->get()->pluck('truck_id')->toArray();

            $trucks = Truck::query()->get()->where('department_id', '=', $department_id);

            $result = $trucks->except($all_trucks);


            return Response()->json($result, Response::HTTP_OK);
        }
    }


    public static function add_Reservation(StoreReservationRequest $request)
    {

        global $state;
        $state = true;
        $reservation = Reservation::query()->get();
        foreach ($reservation as $item) {
            if ($request->start_time < $item['start_date'] && $request->end_time < $item['end_date']) {
                $state = false;
                break;
            } elseif ($request->start_time < $item['start_date'] && $request->end_time > $item['end_date']) {
                $state = false;
                break;
            } elseif ($request->start_time < $item['start_date'] && $request->end_time = $item['end_date']) {
                $state = false;
                break;
            }

            if ($request->start_time > $item['start_date'] && $request->end_time < $item['end_date']) {
                $state = false;
                break;
            } elseif ($request->start_time > $item['start_date'] && $request->end_time > $item['end_date']) {
                $state = false;
                break;
            } elseif ($request->start_time > $item['start_date'] && $request->end_time = $item['end_date']) {
                $state = false;
                break;
            }

            if ($request->start_time = $item['start_date'] && $request->end_time < $item['end_date']) {
                $state = false;
                break;
            } elseif ($request->start_time = $item['start_date'] && $request->end_time > $item['end_date']) {
                $state = false;
                break;
            } elseif ($request->start_time = $item['start_date'] && $request->end_time = $item['end_date']) {
                $state = false;
                break;
            }
        }

        if ($state = true) {
            $reservation = Reservation::query()->create(
                [
                    'start_date' => $request->start_time,
                    'end_date' => $request->end_time,
                    'user_id' => $request->user_id,
                    'department_id' => $request->department_id,
                ]
            );

            $reservation->save();


            $reservation->Staff()->SyncWithoutDetaching($request->staff_ids);

            $reservation->Truck()->SyncWithoutDetaching($request->truck_ids);

            return true;
        } else {

            return false;


        }
    }


}












