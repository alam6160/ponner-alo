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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('title')->nullable();
            $table->string('fname');
            $table->string('lname');
            $table->string('email')->unique();
            $table->string('contact', 20);
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('approve_at')->nullable();
            $table->rememberToken();
            $table->set('status', ['1','2'])->default('1')->comment('1=inactive, 2=active');
            $table->set('user_type', ['1','2','3','4','5','6','7','8','9','0'])->comment('1=superadmin, 2=admin, 3=statehead, 4=vendor as agent, 5=retailers, 6=Pharmacist, 7=customersupport, 8=marketing, 9=Customer, 0=Delivery Boy');
            $table->set('vendor_type', ['1', '2'])->nullable()->comment('1=Regular, 2=Subscription only agent as vendor');
            $table->double('wallet', 10, 2)->default(0)->comment('only agent as vendor');
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
        Schema::dropIfExists('users');
    }
};
