<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gain extends Model
{
    protected $fillable =[
        "user_id", "sale_id", "total_original_price", "total_qty", "total_livraison", "total_discount", "grand_total"
    ];
}
