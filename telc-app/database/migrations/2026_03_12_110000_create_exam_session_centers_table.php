<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_session_centers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('center_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            // Empêcher les doublons
            $table->unique(['exam_session_id', 'center_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_session_centers');
    }
};
