<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'id';


    protected $fillable = ['bill_id', 'imports_sell_orders_id', 'sale_price', 'sales_quantity'];

    public function Bill()
    {
        return $this->belongsTo(Bill::class);

    }


}
