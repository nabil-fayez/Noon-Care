<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();

            // العلاقات
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');

            // المعلومات الأساسية
            $table->string('record_type'); // consultation, diagnosis, prescription, test_result, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->text('prescription')->nullable();
            $table->text('test_results')->nullable();
            $table->text('notes')->nullable();

            // التواريخ
            $table->date('record_date');
            $table->date('follow_up_date')->nullable();

            // المرفقات
            $table->string('attachment_path')->nullable();
            $table->string('attachment_name')->nullable();

            // الحالة والأولويات
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->boolean('is_urgent')->default(false);
            $table->boolean('requires_follow_up')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['doctor_id']);
            $table->dropForeign(['appointment_id']);
            $table->dropForeign(['facility_id']);
        });

        Schema::dropIfExists('medical_records');
    }
};
