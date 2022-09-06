<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Http\Requests\StoreDepartmentsRequest;
use App\Http\Requests\UpdateDepartmentsRequest;
use Symfony\Component\HttpFoundation\Response;

class DepartmentsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $department = Department::all();
        return Response()->json($department, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * //* // * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreDepartmentsRequest $request)
    {
        $department = Department::query()->create(
            [
                'name'=>$request->name,
                'capacity'=>$request->capacity,
                'shipping_cost'=>$request->shipping_cost,
                'capital'=>$request->capital,
                'profit_balance'=>$request->profit_balance,
                'basic_balance'=>$request->basic_balance,
                'payments'=>$request->payments,
                'department_type_id'=>$request->de_type_id,
                'admin_id'=>$request->admin_id

            ]
        );
        return Response()->json($department, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateDepartmentsRequest $request, Department $Department)
    {
        $Department->update([
                'name'=>$request->name,
                'capacity'=>$request->capacity,
                'shipping_cost'=>$request->shipping_cost,
                'capital'=>$request->capital,
                'profit_balance'=>$request->profit_balance,
                'basic_balance'=>$request->basic_balance,
                'payments'=>$request->payments,
                'department_type_id'=>$request->department_type_id,
                'admin_id'=>$request->admin_id,

            ]);
        return Response()->json($Department, Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * // * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return Response()->json('department deleted successfully', Response::HTTP_OK);

    }
}
