<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('centre_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('category')->default('autre');
            // stock | finance | technique | autre

            $table->string('title');
            $table->text('description')->nullable();

            $table->string('status')->default('ouvert');
            // ouvert | en_cours | resolu

            $table->foreignId('resolved_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_requests');
    }
}

