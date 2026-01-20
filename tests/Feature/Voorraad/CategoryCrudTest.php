<?php

namespace Tests\Feature\Voorraad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    private function makeDirectie(): User
    {
        return User::factory()->create(['role' => 'Directie']);
    }

    public function test_category_create_requires_unique_name(): void
    {
        $user = $this->makeDirectie();

        $this->actingAs($user)
            ->post('/voorraad/categorieen', ['naam' => 'Zuivel'])
            ->assertRedirect('/voorraad/categorieen')
            ->assertSessionHas('success');

        $this->actingAs($user)
            ->post('/voorraad/categorieen', ['naam' => 'Zuivel'])
            ->assertSessionHas('error');
    }

    public function test_category_delete_is_blocked_when_products_are_linked(): void
    {
        $user = $this->makeDirectie();

        $catId = (int) DB::table('product_categorieen')->insertGetId([
            'naam' => 'Test categorie',
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        DB::table('producten')->insert([
            'product_naam' => 'Test product',
            'ean' => '9999999999999',
            'categorie_id' => $catId,
            'aantal_voorraad' => 1,
            'is_actief' => 1,
            'datum_aangemaakt' => now(),
            'datum_gewijzigd' => now(),
        ]);

        $this->actingAs($user)
            ->delete("/voorraad/categorieen/{$catId}")
            ->assertRedirect('/voorraad/categorieen')
            ->assertSessionHas('error');
    }
}

