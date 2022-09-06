<?php

namespace App\Http\Controllers;

use App\Models\Truck;
use App\Http\Requests\StoreTruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use Symfony\Component\HttpFoundation\Response;

class TruckController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $Truck = Truck::all();
        return Response()->json($Truck, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * //* // * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreTruckRequest $request)
    {
        //dont forget authorization

        $Truck = Truck::query()->create(
            [
                'number'=>$request->number,
                'color'=>$request->color,
                'model'=>$request->model,
                'state'=>$request->state,
                'department_id'=>$request->department_id

            ]
        );
        return Response()->json($Truck, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateTruckRequest $request, Truck $Truck)
    {
        $Truck->update([
            'number'=>$request->number,
            'color'=>$request->color,
            'model'=>$request->model,
            'state'=>$request->state,
            'department_id'=>$request->department_id

        ]);
        return Response()->json($Truck, Response::HTTP_CREATED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * // * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Truck $Truck)
    {
        $Truck->delete();
        return Response()->json('Truck deleted successfully', Response::HTTP_OK);

    }
}
