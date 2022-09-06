<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WalletChargeOrder;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WalletChargeOrderPolicy
{
    use HandlesAuthorization;


    /**
     * Determine whether the user can view the model.
     *

     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view( $admin)
    {
        if($admin->role == 'Accountant')
        {
            return Response::allow();
        }
        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create( $user, $wallet_user_id)
    {
        if($user->role == 'Seller' && $user->id == $wallet_user_id){
            return Response::allow();
        }
        return Response::deny();

    }


}
