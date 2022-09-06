<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $table = 'reservations';
    protected $primaryKey = 'id';


    protected $fillable=['start_date','end_date','user_id','department_id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function Department()
    {
        return $this->belongsTo(Department::class);
    }

    public function Staff(){
        return $this->belongsToMany(Staff::class,'reservations_staff');
    }

    public function Truck(){
        return $this->belongsToMany(Truck::class,'reservations_trucks');
    }

}
