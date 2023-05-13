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
        Schema::create('application_payments', function (Blueprint $table) {
            $table->id();
            $table->string('req_no')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('department', 20);
            $table->string('payable_amount', 20)->nullable();
            $table->set('payment_mode', ['1','2'])->default('1')->comment('1=Offline, 2=Online');
            $table->string('remark')->nullable();
            $table->text('payment_proof')->nullable();
            $table->set('status', ['1','2','3'])->default('1')->comment('1=Pending, 2=Approve, 3=Cancel');
            $table->string('cancel_remark')->nullable();
            $table->timestamp('approved_at')->nullable();
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
        Schema::dropIfExists('application_payments');
    }
};
