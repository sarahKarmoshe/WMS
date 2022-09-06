<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $fillable = ['is_accepted','user_id', 'is_received','department_id'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsToMany(Product::class,'order_product')
            ->withPivot('price','quantity','expiry_date_id');
    }

    public function Department (){
        return $this->belongsTo(Department::class,'department_id');
    }


}
