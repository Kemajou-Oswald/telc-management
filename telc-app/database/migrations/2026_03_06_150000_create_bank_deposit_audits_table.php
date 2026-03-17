<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDepositAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('bank_deposit_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_deposit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_deposit_audits');
    }
}
