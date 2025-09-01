<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('working_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_available')->default(true);
            $table->integer('slot_duration')->default(30);
            $table->timestamps();
            
            $table->unique(['doctor_id', 'facility_id', 'day_of_week']);
        });
    }

    public function down()
    {
        Schema::table('working_hours', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['facility_id']);
        });
        
        Schema::dropIfExists('working_hours');
    }
};