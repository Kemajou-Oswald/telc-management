<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('user_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affected_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_audits');
    }
}
