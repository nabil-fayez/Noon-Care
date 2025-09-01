<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_facility_id')->constrained('doctor_facility')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('insurance_company_id')->nullable()->constrained()->onDelete('set null');
            $table->dateTime('appointment_datetime');
            $table->integer('duration')->default(30);
            $table->enum('status', ['new', 'confirmed', 'cancelled', 'done'])->default('new');
            $table->text('notes')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['doctor_facility_id']);
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['insurance_company_id']);
        });
        
        Schema::dropIfExists('appointments');
    }
};