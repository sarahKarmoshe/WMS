<?php

namespace App\Http\Controllers;

use App\Http\Traits\Images;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     *
     * /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    {
        $product = Product::all();
        return Response()->json($product, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreCategoryRequest $request
     *  @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $image_url = Images::SaveProductImage($request);


        $product = Product::query()->create(
            [
                'name'=>$request->name,
                'photo'=>$image_url,
                'description'=>$request->description,
                'max_quantity'=>$request->max_quantity,
                'min_quantity'=>$request->min_quantity,
                'space'=>$request->space,
                'measurement_unit'=>$request->measurement_unit,
                'exist_quantity'=>$request->exist_quantity,
                'products_number_by_space'=>$request->products_number_by_space,
                'department_id'=>$request->department_id,
                'category_id'=>$request->category_id
            ]
        );
        return Response()->json($product, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\jsonResponse
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $image_url = Images::SaveProductImage($request->photo);

        $product->update([
            'name'=>$request->name,
            'photo'=>$image_url,
            'description'=>$request->description,
            'max_quantity'=>$request->max_quantity,
            'min_quantity'=>$request->min_quantity,
            'space'=>$request->space,
            'measurement_unit'=>$request->measurement_unit,
            'exist_quantity'=>$request->exist_quantity,
            'products_number_by_space'=>$request->products_number_by_space,
            'department_id'=>$request->department_id,
            'category_id'=>$request->category_id
        ]);

        return Response()->json($product, Response::HTTP_OK);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * // * @return \Illuminate\Http\jsonResponse
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return Response()->json('product deleted successfully', Response::HTTP_OK);

    }
}
