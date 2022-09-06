<?php
namespace App\Http\Traits ;

use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;

trait Images
{

    public static function SaveUserImage(Request $request): string
{
    $fileExtionsion = $request->file('photo')->getClientOriginalExtension();
    $fileName = time() . '.' . $fileExtionsion;
//    $photo->move($path, $fileName);

    //this Url changes up to the host
    $base_url = "http://localhost:8000/storage/";
    $fileName =  $request->file('photo')->storeas('UsersProfile',$fileName,'public');
    $image_url = $base_url . '' .$fileName;

    return $image_url;

}

     public static function SaveProductImage(StoreProductRequest $request): string
     {
         $fileExtionsion =$request->file('photo')->getClientOriginalExtension();
         $fileName = time() . '.' . $fileExtionsion;
//         $photo->move($path, $fileName);

         //this Url changes up to the host
         $base_url = "http://localhost:8000/storage/";
         $fileName =  $request->file('photo')->storeas('Products',$fileName,'public');
         $image_url = $base_url . '' .$fileName;

         return $image_url;
     }



}
