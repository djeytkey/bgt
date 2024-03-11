<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawReferral extends Model
{
    protected $fillable =[

        "referral_user_id", "referral_id", "facture_no", "status", "withdraw_amount"
    ];

    public function referral()
    {
    	return $this->hasMany("App\Referral");
    }

    public function user()
    {
    	return $this->belongsTo("App\User");
    }
}
