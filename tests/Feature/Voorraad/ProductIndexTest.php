<?php

namespace Tests\Feature\Voorraad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    private function makeWorker(): User
    {
        return User::factory()->create(['role' => 'Magazijnmedewerker']);
    }

    public function test_product_index_shows_empty_state_when_no_products(): void
    {
        $user = $this->makeWorker();

        $this->actingAs($user)
            ->get('/voorraad')
            ->assertOk()
            ->assertSee('Er zijn geen producten beschikbaar.');
    }

    public function test_product_index_shows_products_and_supports_sorting_and_ean_search(): void
    {
        $user = $this->makeWorker();

        $catId = (int) DB::table('product_categorieen')->insertGetId([
            'naam' => 'Test categorie',
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        DB::table('producten')->insert([
            [
                'product_naam' => 'Bananen',
                'ean' => '1234567890123',
                'categorie_id' => $catId,
                'aantal_voorraad' => 5,
                'is_actief' => 1,
                'datum_aangemaakt' => now(),
                'datum_gewijzigd' => now(),
            ],
            [
                'product_naam' => 'Appels',
                'ean' => '0234567890123',
                'categorie_id' => $catId,
                'aantal_voorraad' => 10,
                'is_actief' => 1,
                'datum_aangemaakt' => now(),
                'datum_gewijzigd' => now(),
            ],
        ]);

        // Sorting by EAN asc: Appels (0...) should appear before Bananen (1...)
        $this->actingAs($user)
            ->get('/voorraad?sort=ean&dir=asc')
            ->assertOk()
            ->assertSeeInOrder(['0234567890123', '1234567890123']);

        // Search by EAN should filter to one row
        $this->actingAs($user)
            ->get('/voorraad?ean=1234567890123')
            ->assertOk()
            ->assertSee('Bananen')
            ->assertDontSee('Appels');
    }
}

