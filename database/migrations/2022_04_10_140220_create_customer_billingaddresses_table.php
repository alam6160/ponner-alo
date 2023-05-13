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
        Schema::create('customer_billingaddresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique()->comment('as user_id form users table');
            $table->string('title')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('email')->nullable();
            $table->string('contact', 20)->nullable();
            $table->string('alt_contact', 20)->nullable();
            $table->string('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->bigInteger('state_id')->nullable();
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
        Schema::dropIfExists('customer_billingaddresses');
    }
};
