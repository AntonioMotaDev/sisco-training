<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('course_topic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->integer('order_in_course')->default(1);
            $table->timestamps();
            $table->unique(['course_id', 'topic_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_topic');
    }
};
