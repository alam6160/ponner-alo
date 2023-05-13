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
        Schema::create('pre_paymentlogs', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->string('order_key')->nullable();
            $table->unsignedBigInteger('user_id')->comment('');
            $table->double('sub_total', 10, 2)->default();
            $table->double('delivery_charge')->default(0);
            $table->double('discount')->default(0);
            $table->double('grand_total')->default(0);
            $table->date('order_date')->nullable();
            $table->longText('shipping_address')->nullable();
            $table->longText('billing_address')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->longText('coupon_desc')->nullable();
            $table->text('order_note')->nullable();
            $table->longText('order_item')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('customer_address')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_postcode')->nullable();
            $table->string('customer_country')->nullable();
            $table->timestamp('payment_date')->nullable();
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
        Schema::dropIfExists('pre_paymentlogs');
    }
};
