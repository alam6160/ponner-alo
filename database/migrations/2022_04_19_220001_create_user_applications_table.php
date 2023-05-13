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
        Schema::create('user_applications', function (Blueprint $table) {
            $table->id();
            $table->string('appl_no')->unique();
            $table->string('title', 10);
            $table->string('fname');
            $table->string('lname');
            $table->string('email');
            $table->string('contact', 20);
            $table->string('department', 20);
            $table->string('address')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->string('pin_code')->nullable();
            $table->integer('servicable_state_id')->nullable();
            $table->string('servicable_pincodes')->nullable();
            $table->json('content');
            $table->set('status',['1','2','3'])->default('1')->comment('1=Pending, 2=Approve, 3=Cancel');
            $table->text('cancel_msg')->nullable();
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
        Schema::dropIfExists('user_applications');
    }
};
