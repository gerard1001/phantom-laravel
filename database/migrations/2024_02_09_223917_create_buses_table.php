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
        Schema::create('buses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('route_id')->nullable();
            $table->foreign('route_id')->references('id')->on('routes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('plate_number', 10)->unique();
            $table->enum('model', ['Yutong', 'Coaster']);
            $table->integer('capacity')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
