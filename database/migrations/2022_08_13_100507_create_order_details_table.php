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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->string('order_key')->nullable();
            $table->unsignedBigInteger('user_id')->comment('');
            $table->double('sub_total', 10, 2)->default();
            $table->double('delivery_charge')->default(0);
            $table->double('discount')->default(0);
            $table->double('grand_total')->default(0);
            $table->date('order_date')->nullable();
            $table->set('payment_mode', ['1','2'])->default('1')->comment('1=COD, 2=Online Payment');
            $table->set('status', ['1','2','3','4','5','6'])->default('1')->comment('1=Recieved, 2=Processed, 3=Shipped, 4=Delivered, 5=Cancel, 6=Return');
            $table->set('payment_status', ['1','2'])->default('1')->comment('1=Incomplete, 2=Complete');
            $table->longText('shipping_address')->nullable();
            $table->longText('billing_address')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('coupon_code')->nullable();
            $table->longText('coupon_desc')->nullable();
            $table->text('order_note')->nullable();
            $table->text('cancel_remark')->nullable();
            $table->text('return_remark')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('return_last_date')->nullable();
            $table->set('return_status', ['1','2','3'])->nullable()->comment('1=Pending, 2=Approved, 3=Reject');
            $table->text('return_admin_remark')->nullable();

            $table->string('shurjopayment_order_id')->nullable();
            $table->longText('shurjopayment_desc')->nullable();
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
        Schema::dropIfExists('order_details');
    }
};
