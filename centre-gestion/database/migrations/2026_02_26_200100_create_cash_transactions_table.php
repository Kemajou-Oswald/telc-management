<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cash_book_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('centre_id')
                ->constrained()
                ->onDelete('cascade');

            // entree | sortie
            $table->enum('direction', ['entree', 'sortie']);

            $table->decimal('amount', 12, 2);

            // Libellé lisible (ex: Versement étudiant, Achat fournitures…)
            $table->string('label');

            // Mode de paiement (espèces, mobile money, banque…)
            $table->string('mode')->nullable();

            // Référence de reçu ou n° opération
            $table->string('reference')->nullable();

            // Lien optionnel vers une autre table (payment, expense…)
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();

            // Annulation plutôt que suppression
            $table->boolean('is_cancelled')->default(false);
            $table->timestamp('cancelled_at')->nullable();

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
        Schema::dropIfExists('cash_transactions');
    }
}

