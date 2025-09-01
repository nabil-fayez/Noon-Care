<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('doctor_facility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending');
            $table->boolean('available_for_appointments')->default(true);
            $table->timestamps();
            
            $table->unique(['doctor_id', 'facility_id']);
        });
    }

    public function down()
    {
        Schema::table('doctor_facility', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['facility_id']);
        });
        
        Schema::dropIfExists('doctor_facility');
    }
};