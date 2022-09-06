<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellOrder extends Model
{
    use HasFactory;
    protected $table = 'sell_orders';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id','is_Received','is_Accepted','department_id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Import()
    {
        return $this->belongsToMany(Import::class,'imports_sell_orders')
            ->withPivot('quantity');

    }

    public function Department (){
        return $this->belongsTo(Department::class,'department_id');
    }

}
