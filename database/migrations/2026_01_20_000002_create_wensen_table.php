<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Merge-friendly: table may already exist from `database/createscript/script.sql`.
        if (Schema::hasTable('wensen')) {
            return;
        }

        Schema::create('wensen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('omschrijving', 100)->unique();
            $table->boolean('is_actief')->default(true);
            $table->string('opmerking', 255)->nullable();
            $table->timestamp('datum_aangemaakt')->useCurrent();
            $table->timestamp('datum_gewijzigd')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wensen');
    }
};

