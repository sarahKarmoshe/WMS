<?php

namespace App\Models;

use App\Notifications\ReSetPassword;
use App\Notifications\SetPasswordFirstTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function sendEmailVerificationPassword($verification_code)
    {
        $this->notify(new ReSetPassword($verification_code)); // my notification
    }

    public function SendEmailAccount_PaaswordVerify($verification_code)
    {
        $this->notify(new SetPasswordFirstTime($verification_code)); // my notification
    }

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'email', 'password',
        'role','phone','photo','bonus','is_exist',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Order()
    {
        return $this->hasMany(Order::class , 'user_id');
    }

    public function Wallet()
    {
        return $this->hasOne(Wallet::class , 'user_id');

    }

    public function Reservation()
    {
        return $this->hasMany( Reservation::class , 'user_id');
    }



}
