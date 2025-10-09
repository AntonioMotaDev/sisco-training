<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('topic_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pendiente', 'en_progreso', 'aprobado'])->default('pendiente');
            $table->timestamp('approved_at')->nullable();
            $table->float('score')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'topic_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('topic_user');
    }
};
