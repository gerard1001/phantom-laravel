<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('names', 40);
            $table->string('email', 30)->unique()->index();
            $table->date('birth_date');
            $table->string('phone_number', 20)->unique();
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('password');
            $table->string('image')->default('https://www.pngkit.com/png/detail/72-729651_wikipedia-user-icon-bynightsight-user-icon-png.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
