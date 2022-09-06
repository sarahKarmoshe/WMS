<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentsRequest;
use App\Http\Requests\UpdateDepartmentsRequest;
use App\Models\Department_type;
use App\Http\Requests\StoreDepartments_typeRequest;
use App\Http\Requests\UpdateDepartments_typeRequest;
use Symfony\Component\HttpFoundation\Response;

class DepartmentsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $department = Department_type::all();
        return Response()->json($department, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * //* // * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreDepartments_typeRequest $request)
    {
        $department_type = Department_type::query()->create(
            [
                'name' => $request->name,
                'details' => $request->details
            ]
        );
        return Response()->json($department_type, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateDepartments_typeRequest $request, Department_type $Department_type)
    {
        $Department_type->update([
            'name' => $request->name,
            'details' => $request->details
        ]);
        return Response()->json($Department_type, Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * // * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Department_type $Department_type)
    {
        $Department_type->delete();
        return Response()->json('department_type deleted successfully', Response::HTTP_OK);
    }
}
