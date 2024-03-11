<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable =[
        "referral_user_id", "referral_id", "montant"
    ];

    public function user()
    {
    	return $this->hasMany('App\User', 'first_name', 'last_name');
    }
}
