<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->comment('agent Id form user table usertype=agent As Vendor');
            $table->string('req_no')->unique();
            $table->double('amount', 10, 2)->nullable();
            $table->set('receive_by', ['1', '2'])->comment('1=Bank info, 2=Other Info form agent_banks');
            $table->set('status', ['1', '2', '3'])->default('1')->comment('1=Pending, 2=Complete, 3=Cancel');
            $table->dateTime('verification_at')->nullable();
            $table->text('cancel_remark')->nullable();
            $table->json('account_desc')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_payouts');
    }
};
