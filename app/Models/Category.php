<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'details', 'profit_ratio',
    ];

    public function Product(){
        return $this->hasMany(Product::class,'category_id');
    }

}
