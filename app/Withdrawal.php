<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable =[

        "facture_no", "status"
    ];

    public function sale()
    {
    	return $this->hasMany("App\Sale");
    }

    public function user()
    {
    	return $this->belongsTo("App\User");
    }
}
