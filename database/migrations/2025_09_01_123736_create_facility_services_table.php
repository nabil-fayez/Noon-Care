<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('facility_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            
            $table->unique(['facility_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::table('facility_services', function (Blueprint $table) {
            $table->dropForeign(['facility_id']);
            $table->dropForeign(['service_id']);
        });
        
        Schema::dropIfExists('facility_services');
    }
};