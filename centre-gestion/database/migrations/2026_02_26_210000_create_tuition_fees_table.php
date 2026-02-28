<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTuitionFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuition_fees', function (Blueprint $table) {
            $table->id();

            // Tarif éventuellement spécifique à un centre (sinon global si null)
            $table->foreignId('centre_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            // Niveau (A1, A2...) déjà présent dans la table levels
            $table->foreignId('level_id')
                ->constrained('levels')
                ->onDelete('cascade');

            // Langue (ex: Allemand, Anglais) – on commence par un simple champ texte
            $table->string('language');

            // Libellé du cours (ex: "A1 Allemand soir")
            $table->string('label');

            // Prix total prévu pour ce niveau/cours
            $table->decimal('total_amount', 10, 2);

            $table->string('currency', 10)->default('FCFA');

            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('tuition_fees');
    }
}

