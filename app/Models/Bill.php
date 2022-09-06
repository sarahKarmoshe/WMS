<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $table = 'bills';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'total_price'];

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'bill_id');

    }

    public function Product(){
        return $this->belongsToMany(Product::class,'product_bill');
    }
}
