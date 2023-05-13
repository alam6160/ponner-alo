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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name')->nullable();
            $table->string('components')->nullable();
            $table->integer('number_of_items')->nullable();
            $table->string('metakeywords')->nullable();
            $table->string('metadescriptions')->nullable();
            $table->longText('description');
            $table->text('short_desc')->nullable();
            $table->longText('images')->nullable();
            $table->longText('featuredimage')->nullable();
            $table->text('video_url')->nullable();
            $table->longText('mrp')->nullable();
            $table->longText('discounted_price')->nullable();
            $table->longText('strip_price')->nullable();
            $table->string('product_addon_id')->comment('form product_addons table')->nullable();
            $table->string('product_filter_id')->comment('form product_filters table')->nullable();
            $table->string('categories')->nullable();
            $table->string('attribute_1')->nullable();
            $table->string('attribute_2')->nullable();
            $table->string('attribute_3')->nullable();
            $table->longText('specifications_1')->nullable();
            $table->longText('specifications_2')->nullable();
            $table->longText('specifications_3')->nullable();
            $table->longText('specifications')->nullable();
            $table->longText('sku')->nullable();
            $table->set('product_type', ['1','2'])->comment('1 = Simple, 2 = Variable');
            $table->set('saletype', ['0','1','2','3','4','5'])->comment('0 = None, 1 = Featured, 2 = Best Selling, 3 = Deals of The Day, 4 = Deals of The Week, 5 = Deals of The Month')->default('0');
            $table->unsignedBigInteger('user_id')->comment('form users table')->nullable();
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
        Schema::dropIfExists('products');
    }
};
