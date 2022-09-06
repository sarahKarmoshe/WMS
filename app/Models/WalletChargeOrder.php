<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WalletChargeOrder extends Model
{
    use HasFactory;
    protected $table = 'wallet_charge_orders';
    protected $primaryKey = 'id';

    protected $fillable = ['wallet_id','charge_value','Is_Accepted'];

    public function wallet(){
        return $this->belongsTo(Wallet::class , 'wallet_id');
    }


}
