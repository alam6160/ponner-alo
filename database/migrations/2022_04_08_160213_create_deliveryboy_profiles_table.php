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
        Schema::create('deliveryboy_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deliveryboy_id')->unique()->comment('as user_id form users table');
            $table->unsignedBigInteger('agent_id')->comment('as user_id form users table');
            $table->string('statehead_id')->nullable()->comment('as user_id form users table');
            $table->string('driving_licence')->nullable();
            $table->string('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->string('driving_licence_file')->nullable();
            $table->string('aadhaar_file')->nullable();
            $table->string('servicable_pincodes')->comment('seperated by , symbol')->nullable();
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
        Schema::dropIfExists('deliveryboy_profiles');
    }
};
