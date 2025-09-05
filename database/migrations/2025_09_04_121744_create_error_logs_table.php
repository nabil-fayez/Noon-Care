<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level')->index(); // error, warning, info, etc.
            $table->text('message');
            $table->text('details')->nullable();
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->string('url')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
