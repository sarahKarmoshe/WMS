<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallets';
    protected $primaryKey = 'id';

    protected $fillable = ['value','user_id'];

    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }

}
