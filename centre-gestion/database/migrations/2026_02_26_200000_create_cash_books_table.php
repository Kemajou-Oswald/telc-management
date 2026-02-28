<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_books', function (Blueprint $table) {
            $table->id();

            $table->foreignId('centre_id')
                ->constrained()
                ->onDelete('cascade');

            // Date de la journée de caisse
            $table->date('date');

            // Soldes et totaux figés au moment de la clôture
            $table->decimal('solde_veille', 12, 2)->default(0);
            $table->decimal('total_entrees', 12, 2)->default(0);
            $table->decimal('total_sorties', 12, 2)->default(0);
            $table->decimal('solde_final', 12, 2)->default(0);

            $table->timestamp('date_cloture')->nullable();

            // Pour empêcher toute écriture après clôture
            $table->boolean('is_closed')->default(false);

            $table->timestamps();

            $table->unique(['centre_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_books');
    }
}

