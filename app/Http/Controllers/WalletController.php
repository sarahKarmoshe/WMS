<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Models\WalletChargeOrder;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
class WalletController extends Controller
{

    public static function create( $user_id)
    {
        // create seller wallet first time which will be created by default during account creation operation
        Wallet::query()->create([
            'value'=>0,
            'user_id'=> $user_id
        ]);
        return true;

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function showMyWallet()
    {
        if(Auth::guard('api')->user()->role != 'Seller'){
            return response()->json('unAuthorized' ,Response::HTTP_FORBIDDEN);
        }

        $wallet=Wallet::query()->where('user_id','=',Auth::guard('api')->id())->get();

        return response()->json($wallet,Response::HTTP_OK);
    }

}
