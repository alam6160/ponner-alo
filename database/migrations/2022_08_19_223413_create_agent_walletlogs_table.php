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
        Schema::create('agent_walletlogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id')->nullable()->comment('as Vendor ID');
            $table->double('wallet', 10, 2)->nullable();
            $table->double('commission', 10, 2)->nullable();
            $table->double('admin_charge', 10, 2)->nullable();

            $table->unsignedBigInteger('order_detail_id');
            $table->string('order_no')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->double('sub_total', 10, 2)->nullable();
            $table->double('grand_total', 10, 2)->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_image')->nullable();
            $table->set('type', ['1','2'])->default('1')->comment('1=Creadit, 2=Debuit');
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
        Schema::dropIfExists('agent_walletlogs');
    }
};
