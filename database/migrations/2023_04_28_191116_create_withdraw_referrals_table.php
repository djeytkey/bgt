<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_referrals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referral_user_id');
            $table->integer('referral_id');
            $table->string('facture_no');
            $table->integer('status')->default(0);            
            $table->string('withdraw_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_referrals');
    }
}
