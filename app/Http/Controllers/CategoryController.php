<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $category = Category::all();
        return Response()->json($category, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     *  @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {

        $category = Category::query()->create(
            [
                'name'=> $request->name,
                'profit_ratio'=>$request->profit_ratio,
                'details'=>$request->details
            ]
        );
        return Response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {

        $category->update([
            'name'=> $request->name,
            'profit_ratio'=>$request->profit_ratio,
            'details'=>$request->details
        ]);

        return Response()->json($category,Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * // * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return Response()->json('category deleted successfully', Response::HTTP_OK);

    }

}
