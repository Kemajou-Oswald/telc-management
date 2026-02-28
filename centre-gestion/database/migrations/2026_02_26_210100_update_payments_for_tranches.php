<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentsForTranches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Lier éventuellement à un tarif (niveau + langue)
            $table->foreignId('tuition_fee_id')
                ->nullable()
                ->after('centre_id')
                ->constrained('tuition_fees')
                ->onDelete('set null');

            // Numéro de tranche (1, 2, 3, ...) – plusieurs paiements possibles par tranche
            $table->unsignedTinyInteger('tranche')
                ->nullable()
                ->after('type');

            // Mode de paiement + référence du versement
            $table->string('mode')->nullable()->after('amount');
            $table->string('reference')->nullable()->after('mode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['tuition_fee_id']);
            $table->dropColumn(['tuition_fee_id', 'tranche', 'mode', 'reference']);
        });
    }
}

