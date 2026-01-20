<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categorieen', function (Blueprint $table) {
            // Createscript: INT UNSIGNED AUTO_INCREMENT
            $table->increments('id');

            // Createscript: naam VARCHAR(100) NOT NULL UNIQUE
            $table->string('naam', 100)->unique();

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
        Schema::dropIfExists('product_categorieen');
    }
};

