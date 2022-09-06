<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'capacity', 'capital', 'profit_balance', 'basic_balance', 'payments', 'shipping_cost', 'department_type_id', 'admin_id',
    ];

    public function Product()
    {
        return $this->hasMany(product::class, 'Department_id');

    }
    public function Order()
    {
        return $this->hasMany(Order::class, 'Department_id');

    }
    public function SellOrder()
    {
        return $this->hasMany(SellOrder::class, 'Department_id');

    }

    public function Staff()
    {
        return $this->hasMany(Staff::class, 'department_id');

    }

    public function Truck()
    {
        return $this->hasMany(Truck::class, 'department_id');

    }

    public function Import()
    {
        return $this->hasMany(Import::class, 'department_id');

    }

    public function Admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function Department_type()
    {
        return $this->belongsTo(Department_type ::class);
    }

}
