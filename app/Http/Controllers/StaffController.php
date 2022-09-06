<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentsRequest;
use App\Http\Requests\UpdateDepartmentsRequest;
use App\Models\Staff;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Traits\Images;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $department = Staff::all();
        return Response()->json($department, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreStaffRequest $request)
    {
        $image_url = Images::SaveUserImage($request);


        $staff = Staff::query()->create(
            [
                'name'=>$request->name,
                'phone'=>$request->phone,
                'birth_date'=>$request->birth_date,
                'rate'=>$request->rate,
                'department_id'=>$request->department_id,
                'photo'=>$image_url

            ]
        );
        return Response()->json($staff, Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateStaffRequest $request, Staff $Staff)
    {
        $image_url = Images::SaveUserImage($request);


        $Staff->update([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'birth_date'=>$request->birth_date,
            'rate'=>$request->rate,
            'department_id'=>$request->department_id,
            'photo'=>$image_url


        ]);
        return Response()->json($Staff, Response::HTTP_CREATED);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * // * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Staff $Staff)
    {
        $Staff->delete();
        return Response()->json(' Staff deleted successfully', Response::HTTP_OK);

    }
}
