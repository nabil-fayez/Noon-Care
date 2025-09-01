<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_specialty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('specialty_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['doctor_id', 'specialty_id']);
        });
    }

    public function down()
    {
        Schema::table('doctor_specialty', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['specialty_id']);
        });
        
        Schema::dropIfExists('doctor_specialty');
    }
};