<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('insurance_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('contact_email', 100)->nullable();
            $table->string('contact_phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('insurance_companies');
    }
};