<?php

namespace App\Http\Controllers;

use App\Models\Import;
use App\Models\Sale;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        if (Auth::guard('admin-api')->user()->role == 'Admin') {
            $sale = Sale::query() ->join('imports_sell_orders','sales.imports_sell_orders_id','=','imports_sell_orders.id')
                ->join('imports','imports_sell_orders.import_id','=','imports.id')
                ->join('products','imports.product_id','=','products.id')
                ->join('departments','imports.department_id','=','departments.id')
                ->with('Bill')
                ->select('sales.*','products.name as product_name','departments.name as department_name','departments.id as department_id')
                ->get();
            return response()->json($sale, Response::HTTP_OK);

        }

        if (Auth::guard('admin-api')->user()->role == 'Supervisor') {
            $sale = Sale::query()
                ->join('imports_sell_orders','sales.imports_sell_orders_id','=','imports_sell_orders.id')
                ->join('imports','imports_sell_orders.import_id','=','imports.id')
                ->join('products','imports.product_id','=','products.id')
                ->join('departments','imports.department_id','=','departments.id')
                ->where('departments.admin_id','=',Auth::guard('admin-api')->id())
                ->with('Bill')
                ->select('sales.*','products.name as product_name','departments.name as department_name','departments.id as department_id')
                ->get();
            return response()->json($sale, Response::HTTP_OK);

        }
        return response()->json('UnAuthorized', Response::HTTP_FORBIDDEN);

    }

    public function showImportedProducts(){
        if (Auth::guard('admin-api')->user()->role == 'Admin') {
          $import=Import::query()
              ->join('departments','imports.department_id','=','departments.id')
              ->with('Product')
              ->select('imports.*','departments.name as department_name')
              ->get();

            return response()->json($import, Response::HTTP_OK);

        }


        if (Auth::guard('admin-api')->user()->role == 'Supervisor') {
            $import=Import::query()
                ->join('departments','imports.department_id','=','departments.id')
                ->where('departments.admin_id','=',Auth::guard('admin-api')->id())
                ->with('Product')
                ->select('imports.*','departments.name as department_name')
                ->get();

            return response()->json($import, Response::HTTP_OK);

        }

            return response()->json('UnAuthorized', Response::HTTP_FORBIDDEN);

    }

}
