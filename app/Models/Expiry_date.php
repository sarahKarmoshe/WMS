<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expiry_date extends Model
{
    use HasFactory;

    protected $table = 'expiry_dates';
    protected $primaryKey = 'id';

    protected $fillable =['expiration_date'];

    public function import(){
        return $this->belongsToMany(Import::class,'expiry_dates');
    }
}
