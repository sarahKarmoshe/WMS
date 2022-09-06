<?php

namespace App\Models;

use App\Notifications\ReSetPassword;
use App\Notifications\VerifyApiEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function sendApiEmailVerification($verification_code)
    {
        $this->notify(new VerifyApiEmail( $verification_code)); // my notification
    }

    public function sendEmailVerificationPassword($verification_code)
    {
        $this->notify(new ReSetPassword($verification_code)); // my notification
    }


    protected $table = 'admins';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name','role','email','password', 'photo',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Department(){
        return $this->hasMany(Department::class , 'Admin_id');
    }

    public function Role()
    {
        return $this->belongsToMany( Role::class , 'roles_admins');
    }

}
