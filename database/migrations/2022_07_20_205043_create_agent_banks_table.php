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
        Schema::create('agent_banks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->comment('agent Id form user table usertype=agent As Vendor');
            $table->string('swift_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('account_no')->nullable();
            $table->string('account_holder')->nullable();
            $table->string('other_info')->nullable();
            $table->string('bank_remark')->nullable();
            $table->string('other_remark')->nullable();
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
        Schema::dropIfExists('agent_banks');
    }
};
