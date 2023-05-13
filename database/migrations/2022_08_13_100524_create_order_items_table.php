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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_detail_id');
            $table->string('order_no')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->double('sub_total', 10, 2)->nullable();
            $table->double('grand_total', 10, 2)->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('product_type')->nullable();
            $table->string('varient')->nullable();
            $table->longText('product_desc')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable()->comment('as Vendor ID');
            $table->set('vendor_type', ['1','2'])->nullable();
            $table->double('wallet', 10, 2)->nullable();
            $table->double('commission', 10, 2)->nullable();
            $table->double('admin_charge', 10, 2)->nullable();
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
        Schema::dropIfExists('order_items');
    }
};
