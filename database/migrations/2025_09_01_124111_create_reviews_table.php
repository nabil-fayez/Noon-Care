<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating_for_doctor');
            $table->tinyInteger('rating_for_facility');
            $table->text('comment_for_doctor')->nullable();
            $table->text('comment_for_facility')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['facility_id']);
        });
        
        Schema::dropIfExists('reviews');
    }
};