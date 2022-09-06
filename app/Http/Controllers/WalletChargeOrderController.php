<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletChargeOrder;
use App\Http\Requests\StoreWalletChargeOrderRequest;
use App\Http\Requests\UpdateWalletChargeOrderRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class WalletChargeOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\jsonResponse
     */
    public function index()
    { //show all charge wallet orders for accountant

        $this->authorize('view', [WalletChargeOrder::class, Auth::guard('admin-api')->user()]);

        $chargeOrtder = WalletChargeOrder::query()
            ->join('wallets','wallet_charge_orders.wallet_id','=','wallets.id')
            ->join('users','wallets.user_id','=','users.id')

        ->get();

        return response()->json($chargeOrtder, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreWalletChargeOrderRequest $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function chargeWalletRequest(StoreWalletChargeOrderRequest $request, Wallet $wallet)
    {
        //user ask for charge his wallet
        $this->authorize('create',[WalletChargeOrder::class,Auth::guard('api')->user()->id ,$wallet->user_id]);

        $chargeOrder = WalletChargeOrder::query()->create([
            'wallet_id' => $wallet->id,
            'charge_value' => $request->value
        ]);

        return response()->json($chargeOrder, Response::HTTP_CREATED);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateWalletChargeOrderRequest $request
     * @param \App\Models\WalletChargeOrder $walletChargeOrder
     * @return \Illuminate\Http\jsonResponse
     */
    public function Accept(WalletChargeOrder $walletChargeOrder)
    {

        $this->authorize('view', [WalletChargeOrder::class, Auth::guard('admin-api')->user()]);


        $walletChargeOrder->update(['Is_Accepted' => true]);
        $wallet_id = $walletChargeOrder->wallet_id;
        $wallet = Wallet::query()->find($wallet_id);
        $wallet->increment('value', $walletChargeOrder->charge_value);

        $wallet = Wallet::query()->find($wallet_id)
            ->join('users','wallets.user_id','=','users.id')->get();

        $result['message'] = 'charge Order has been Accepted';
        $result['wallet'] = $wallet;
        return response()->json($result, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\WalletChargeOrder $walletChargeOrder
     * @return \Illuminate\Http\jsonResponse
     */
    public function decline(WalletChargeOrder $walletChargeOrder)
    {
        // reject charge order

        $this->authorize('view', [WalletChargeOrder::class, Auth::guard('admin-api')->user()]);

        $walletChargeOrder->update(['Is_Accepted' => false]);

        return response()->json('charge wallet order rejected successfully', Response::HTTP_OK);
    }
}
