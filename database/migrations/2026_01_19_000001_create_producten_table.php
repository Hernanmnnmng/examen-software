<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producten', function (Blueprint $table) {
            // Createscript: INT UNSIGNED AUTO_INCREMENT
            $table->increments('id');

            // Createscript: product_naam VARCHAR(255) NOT NULL UNIQUE
            $table->string('product_naam', 255)->unique();

            // Createscript: ean CHAR(13) NOT NULL UNIQUE
            $table->char('ean', 13)->unique();

            // Createscript: categorie_id INT UNSIGNED NOT NULL FK
            $table->unsignedInteger('categorie_id');
            $table->foreign('categorie_id')->references('id')->on('product_categorieen');

            // Createscript: aantal_voorraad INT UNSIGNED NOT NULL DEFAULT 0
            $table->unsignedInteger('aantal_voorraad')->default(0);

            // Createscript: is_actief BIT(1) NOT NULL DEFAULT 1
            $table->boolean('is_actief')->default(true);

            // Createscript: opmerking VARCHAR(255) NULL
            $table->string('opmerking', 255)->nullable();

            // Createscript uses custom timestamps; keep same column names.
            // Note: kept SQLite-safe (no "ON UPDATE CURRENT_TIMESTAMP").
            $table->timestamp('datum_aangemaakt')->useCurrent();
            $table->timestamp('datum_gewijzigd')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producten');
    }
};

