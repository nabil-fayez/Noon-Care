<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facility_service_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('insurance_company_id')->nullable()->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // استخدام اسم أقصر للمفتاح الفريد
            $table->unique(['facility_id', 'service_id', 'insurance_company_id'], 'fac_serv_ins_unique');
        });
    }

    public function down()
    {
        // أولاً: إزالة أي مفاتيح خارجية في جداول أخرى تشير إلى هذا الجدول
        // (يجب أن تكون هذه في ملفات migration الخاصة بتلك الجداول)
        
        // ثانياً: إزالة المفاتيح الخارجية من هذا الجدول
        Schema::table('facility_service_pricing', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['insurance_company_id']);
        });
        
        // أخيراً: حذف الجدول (سيتم حذف الفهرس تلقائياً)
        Schema::dropIfExists('facility_service_pricing');
    }
};