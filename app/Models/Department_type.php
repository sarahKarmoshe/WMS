<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department_type extends Model
{
    use HasFactory;

    protected $table = 'departments_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'details',
    ];

    public function Department(){

        return $this->hasMany(Department::class,'department_type_id');
    }
}
