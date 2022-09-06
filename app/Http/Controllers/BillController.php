<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        if (Auth::guard('admin-api')->user()->role == 'Admin' || Auth::guard('admin-api')->user()->role == 'Accountant' ) {
            $bills=Bill::query()->with('Sale')->get();

            return  response()->json($bills,Response::HTTP_OK);
        }
        return response()->json('you can not do this action', Response::HTTP_FORBIDDEN);



    }

}
