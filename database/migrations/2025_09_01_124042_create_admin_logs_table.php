<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('admin_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
            $table->string('action', 255);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('admin_logs', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
        });
        
        Schema::dropIfExists('admin_logs');
    }
};