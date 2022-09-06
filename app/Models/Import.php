<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory;
    protected $table = 'imports';
    protected $primaryKey = 'id';
    protected $with = 'product';


    protected $fillable = ['supply_price', 'supply_quantity', 'is_returns', 'user_id', 'product_id', 'department_id'];

    public function Product()
    {
        return $this->belongsTo(Product::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }

    public function Sale()
    {
        return $this->hasMany(Sale::class, 'category_id');

    }

    public function expiry_date()
    {
        return $this->belongsToMany(Expiry_date::class, 'expiry_dates');
    }

    public function SellOrder(){

        return $this->belongsToMany(SellOrder::class,'imports_sell_orders')
            ->withPivot('quantity');
    }


}
