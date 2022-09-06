<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';


    protected $fillable = [
        'name','measurement_unit','space','products_number_by_space', 'photo',
        'min_quantity', 'max_quantity','exist_quantity','department_id','category_id',
        'description'
    ];

    public function Department (){
        return $this->belongsTo(Department::class,'department_id');
    }

    public function Category (){

        return $this->belongsTo(Category::class,'category_id');
    }

    public function Import()
    {
        return $this->hasMany(Import::class,'product_id');
    }

    public function Bill(){
        return $this->belongsToMany(Bill::class,'product_bill');
    }

    public function Order(){
        return $this->belongsToMany(Order::class,'order_product')
            ->withPivot('price','quantity','expiry_date_id');
    }


}
