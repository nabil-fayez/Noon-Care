<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->nullable();
            $table->text('password');
            $table->string('business_name', 100);
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facilities');
    }
};