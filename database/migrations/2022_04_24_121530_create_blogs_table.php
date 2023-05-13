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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('blog_title')->nullable();
            $table->longText('blog_desc')->nullable();
            $table->string('metakeywords')->nullable();
            $table->text('metadescriptions')->nullable();
            $table->set('publish_status',['1','2'])->default('2')->comment('1=Schedule, 2=Daft');
            $table->date('schedule_date')->nullable();
            $table->text('thumbnail')->nullable();
            $table->string('categories')->nullable()->comment('Blog Category ID');
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
        Schema::dropIfExists('blogs');
    }
};
