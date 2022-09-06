<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'photo', 'phone', 'birth_date', 'rate', 'department_id'];

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }

    public function reservation(){
        return $this->belongsToMany(Reservation::class,'reservations_staff');
    }
}
