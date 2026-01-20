<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Merge-friendly: this table may already exist when the DB was created
        // from `database/createscript/script.sql`.
        if (Schema::hasTable('product_allergenen')) {
            return;
        }

        Schema::create('product_allergenen', function (Blueprint $table) {
            $table->increments('id');

            // FK -> voorraad (producten)
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('allergie_id');

            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->useCurrent();
            $table->timestamp('datum_gewijzigd')->useCurrent();

            $table->unique(['product_id', 'allergie_id'], 'product_allergenen_product_allergie_unique');

            $table->foreign('product_id')
                ->references('id')
                ->on('producten')
                ->onDelete('cascade');

            $table->foreign('allergie_id')
                ->references('id')
                ->on('allergenen')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_allergenen');
    }
};

