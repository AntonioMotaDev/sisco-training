<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles');
            $table->string('username')->unique();
            $table->string('access_token')->nullable();
            $table->string('session_token')->nullable();
            
            // Modificar email para que sea nullable
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['role_id', 'username', 'access_token', 'session_token']);
            
            // Revertir email a NOT NULL
            $table->string('email')->nullable(false)->change();
        });
    }
};
