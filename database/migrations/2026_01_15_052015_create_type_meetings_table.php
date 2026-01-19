<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('type_meetings', function (Blueprint $table) {
            $table->id();
            $table->string('type_meeting_code')->unique();
            $table->string('type_meeting_name');
            $table->string('type_meeting_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_meetings');
    }
};
