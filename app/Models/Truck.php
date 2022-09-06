<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $table = 'trucks';
    protected $primaryKey = 'id';

    protected $fillable = ['number', 'color', 'model', 'state', 'department_id'];

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reservation(){
        return $this->belongsToMany(Reservation::class,'reservations_trucks');
    }
}
