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
        Schema::create('statehead_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('statehead_id')->unique()->comment('as user_id form users table');
            $table->string('organization_name')->nullable();
            $table->string('licence')->nullable();
            $table->string('address')->nullable();
            $table->string('pin_code')->nullable();
            $table->bigInteger('state_id')->nullable();
            $table->string('brand_logo_file')->nullable();
            $table->string('gst_file')->nullable();
            $table->string('drug_licence_file')->nullable();
            $table->string('aadhaar_file')->nullable();
            $table->integer('servicable_state_id')->nullable();
            $table->double('amount', 20, 2)->nullable();
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
        Schema::dropIfExists('statehead_profiles');
    }
};