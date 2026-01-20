<?php

namespace Tests\Feature\Voorraad;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoorraadAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $role): User
    {
        return User::factory()->create([
            'role' => $role,
        ]);
    }

    public function test_vrijwilliger_cannot_access_voorraad_or_categories(): void
    {
        $user = $this->makeUser('Vrijwilliger');

        $this->actingAs($user)->get('/voorraad')->assertStatus(403);
        $this->actingAs($user)->get('/voorraad/categorieen')->assertStatus(403);
    }

    public function test_magazijnmedewerker_can_access_voorraad_but_not_categories(): void
    {
        $user = $this->makeUser('Magazijnmedewerker');

        $this->actingAs($user)->get('/voorraad')->assertOk();
        $this->actingAs($user)->get('/voorraad/categorieen')->assertStatus(403);
    }

    public function test_directie_can_access_voorraad_and_categories(): void
    {
        $user = $this->makeUser('Directie');

        $this->actingAs($user)->get('/voorraad')->assertOk();
        $this->actingAs($user)->get('/voorraad/categorieen')->assertOk();
    }
}

