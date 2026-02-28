<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsAndStockMovementsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('centre_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('unit')->default('pièce');
            $table->integer('min_stock')->default(0);

            $table->timestamps();
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('centre_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            $table->string('type');
            // in | out | adjust

            $table->integer('quantity');
            $table->string('label')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

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
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('products');
    }
}

