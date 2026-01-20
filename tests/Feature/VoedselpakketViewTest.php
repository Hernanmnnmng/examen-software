<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Class VoedselpakketViewTest
 *
 * This feature test verifies that all the GET routes (views) for the Voedselpakketten module
 * are accessible and render the correct Blade templates.
 *
 * IMPORTANT: These tests rely on the 'Voedselbank' MySQL database because the application logic
 * depends deeply on SQL Stored Procedures (e.g., SP_GetAllVoedselpakketen) which are not supported
 * by the default SQLite in-memory testing database.
 */
class VoedselpakketViewTest extends TestCase
{
    /**
     * The authenticated user used for requests.
     * Must have the 'Vrijwilliger' role to access these routes.
     *
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * 1. Switch database connection to the local MySQL instance.
     * 2. Find or create a user with the 'Vrijwilliger' role.
     * 3. Authenticate as this user using $this->actingAs().
     */
    protected function setUp(): void
    {
        parent::setUp();

        // 1. Configuration: Force MySQL connection
        // The codebase uses raw DB::select('CALL ...') which requires a real MySQL server.
        // We assume the local 'Voedselbank' database exists and is seeded.
        config(['database.default' => 'mysql']);
        config(['database.connections.mysql.database' => 'Voedselbank']);

        // 2. Authentication: Retrieve a 'Vrijwilliger'
        // The Middleware checks for this specific role.
        $this->user = User::where('role', 'Vrijwilliger')->first();

        // If no such user exists, try to create a dummy one for the duration of the test.
        // Ideally, the database should be seeded before running tests.
        if (!$this->user) {
             try {
                $this->user = User::factory()->create([
                    'name' => 'Test Vrijwilliger',
                    'email' => 'test_vrijwilliger_' . uniqid() . '@example.com',
                    'role' => 'Vrijwilliger',
                    'password' => bcrypt('password'),
                ]);
             } catch (\Exception $e) {
                // If creation fails (e.g. constraints), skip the tests gracefully.
                $this->markTestSkipped('No user with role Vrijwilliger found and unable to create one: ' . $e->getMessage());
             }
        }

        // 3. Login
        if ($this->user) {
            $this->actingAs($this->user);
        }
    }

    /**
     * Test if the index page (list of packages) loads correctly.
     * Route: GET /voedselpakketten
     */
    public function test_index_view_can_be_rendered()
    {
        // Act: Visit the index route
        $response = $this->get(route('voedselpakketten.index'));

        // Assert:
        // 1. Status code is 200 OK
        $response->assertStatus(200);
        // 2. Correct Blade view is returned
        $response->assertViewIs('voedselpakketten.index');
        // 3. A known string from the UI allows us to verify content (e.g. Table Header)
        $response->assertSee('Pakket Overzicht');
    }

    /**
     * Test if the create page (new package form) loads correctly.
     * Route: GET /voedselpakketten/create
     */
    public function test_create_view_can_be_rendered()
    {
        // Act: Visit the create route
        $response = $this->get(route('voedselpakketten.create'));

        // Assert:
        // 1. Status code is 200 OK
        $response->assertStatus(200);
        // 2. Correct Blade view is returned
        $response->assertViewIs('voedselpakketten.create');
        // 3. Verify page title is present
        $response->assertSee('Nieuw Voedselpakket');
    }

    /**
     * Test if the detail page (show package) loads correctly.
     * Route: GET /voedselpakketten/{id}
     */
    public function test_show_view_can_be_rendered()
    {
        // Pre-condition: We need a valid ID from the database to view.
        try {
            // Fetch any existing ID from the table
            $id = DB::table('voedselpakketten')->value('id');
        } catch (\Exception $e) {
            $this->markTestSkipped('Database not available.');
            return;
        }

        if (!$id) {
            $this->markTestSkipped('No voedselpakketten found in database to test show view.');
            return;
        }

        // Act: Visit the show route with the fetched ID
        $response = $this->get(route('voedselpakketten.show', $id));

        // Assert:
        $response->assertStatus(200);
        $response->assertViewIs('voedselpakketten.show');
    }

    /**
     * Test if the edit page loads correctly.
     * Condition: The package must NOT be delivered (datum_uitgifte IS NULL).
     * Route: GET /voedselpakketten/{id}/edit
     */
    public function test_edit_view_can_be_rendered()
    {
        // Pre-condition: Find a package that hasn't been delivered yet.
        // Delivered packages redirect back to index (as per Controller logic).
        try {
            $id = DB::table('voedselpakketten')
                    ->whereNull('datum_uitgifte') // ONLY active packages can be edited
                    ->value('id');
        } catch (\Exception $e) {
            $this->markTestSkipped('Database not available.');
            return;
        }

        if (!$id) {
            $this->markTestSkipped('No undelivered voedselpakketten found to test edit view.');
            return;
        }

        // Act: Visit the edit route
        $response = $this->get(route('voedselpakketten.edit', $id));

        // Assert:
        $response->assertStatus(200);
        $response->assertViewIs('voedselpakketten.edit');
    }
}
