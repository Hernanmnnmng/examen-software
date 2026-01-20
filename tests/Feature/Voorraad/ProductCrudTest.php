<?php

namespace Tests\Feature\Voorraad;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    private function makeWorker(): User
    {
        return User::factory()->create(['role' => 'Magazijnmedewerker']);
    }

    private function seedCategory(): int
    {
        return (int) DB::table('product_categorieen')->insertGetId([
            'naam' => 'Test categorie',
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);
    }

    public function test_create_product_requires_unique_name_and_ean(): void
    {
        $user = $this->makeWorker();
        $catId = $this->seedCategory();

        // First create
        $this->actingAs($user)
            ->post('/voorraad/producten', [
                'product_naam' => 'Melk',
                'categorie_id' => $catId,
                'ean' => '1234567890123',
                'aantal_voorraad' => 3,
            ])
            ->assertRedirect('/voorraad')
            ->assertSessionHas('success');

        // Duplicate EAN should be blocked
        $this->actingAs($user)
            ->post('/voorraad/producten', [
                'product_naam' => 'Andere melk',
                'categorie_id' => $catId,
                'ean' => '1234567890123',
                'aantal_voorraad' => 1,
            ])
            ->assertSessionHas('error');
    }

    public function test_update_product_requires_unique_name_and_ean(): void
    {
        $user = $this->makeWorker();
        $catId = $this->seedCategory();

        $id1 = (int) DB::table('producten')->insertGetId([
            'product_naam' => 'Product A',
            'ean' => '1111111111111',
            'categorie_id' => $catId,
            'aantal_voorraad' => 1,
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        $id2 = (int) DB::table('producten')->insertGetId([
            'product_naam' => 'Product B',
            'ean' => '2222222222222',
            'categorie_id' => $catId,
            'aantal_voorraad' => 2,
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Attempt to update Product B to duplicate EAN of Product A
        $this->actingAs($user)
            ->put("/voorraad/producten/{$id2}", [
                'product_naam' => 'Product B updated',
                'categorie_id' => $catId,
                'ean' => '1111111111111',
                'aantal_voorraad' => 5,
            ])
            ->assertSessionHas('error');
    }

    public function test_delete_product_is_blocked_when_used_in_voedselpakket(): void
    {
        $user = $this->makeWorker();
        $catId = $this->seedCategory();

        $productId = (int) DB::table('producten')->insertGetId([
            'product_naam' => 'Rijst',
            'ean' => '3333333333333',
            'categorie_id' => $catId,
            'aantal_voorraad' => 10,
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        // Create linkage table in SQLite test env so controller can enforce rule
        if (! Schema::hasTable('voedselpakket_producten')) {
            Schema::create('voedselpakket_producten', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('voedselpakket_id');
                $table->unsignedInteger('product_id');
                $table->unsignedInteger('aantal')->default(1);
            });
        }

        DB::table('voedselpakket_producten')->insert([
            'voedselpakket_id' => 1,
            'product_id' => $productId,
            'aantal' => 1,
        ]);

        $this->actingAs($user)
            ->delete("/voorraad/producten/{$productId}")
            ->assertRedirect('/voorraad')
            ->assertSessionHas('error');
    }
}

