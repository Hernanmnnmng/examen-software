<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Merge-friendly: table may already exist from `database/createscript/script.sql`.
        if (Schema::hasTable('product_kenmerken')) {
            return;
        }

        Schema::create('product_kenmerken', function (Blueprint $table) {
            $table->increments('id');

            // FK -> voorraad (producten) and wensen
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('wens_id');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->useCurrent();
            $table->timestamp('datum_gewijzigd')->useCurrent();

            $table->unique(['product_id', 'wens_id'], 'product_kenmerken_product_wens_unique');

            $table->foreign('product_id')
                ->references('id')
                ->on('producten')
                ->onDelete('cascade');

            $table->foreign('wens_id')
                ->references('id')
                ->on('wensen')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_kenmerken');
    }
};

