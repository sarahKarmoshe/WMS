<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Department;
use App\Models\SellOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SellOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny($user)
    {
        //here i will put condition for user type , it have to be seller

        if ($user->role == 'Seller') {
            return Response::allow();
        }
        return Response::deny();

    }

    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view($admin, $department_admin_id)
    {

        // admin shows order
        if ($admin->role == 'Supervisor' && $admin->id == $department_admin_id) {
            return Response::allow();
        }

        return Response::deny();
    }


    /**
     * Determine whether the user can update the model.
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, $sellOrder)
    {
        if ($user->id == $sellOrder && $user->role == 'Seller') {
            return Response::allow();
        }
        return Response::deny();
    }



}
