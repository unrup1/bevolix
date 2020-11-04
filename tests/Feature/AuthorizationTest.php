<?php

namespace Tests\Feature;

use App\Facility;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->noFacilityUser = User::factory()->create();

        $this->user = User::factory()->create();
        $this->facility = Facility::factory()->create([
            'user_id' => $this->user
        ]);
    }

    public function routeWhenNoAuthorizationProvider() {
        yield ['get', '/api/facility/{facilityId}'];
        yield ['put', '/api/facility/{facilityId}'];
        yield ['delete', '/api/facility/{facilityId}'];
        yield 'QR Code 300' => ['get', '/facility/{facilityId}/qr/png-300'];
        yield 'QR Code 150' => ['get', '/facility/{facilityId}/qr/png-150'];
        yield 'QR Code SVG' => ['get', '/facility/{facilityId}/qr/svg'];
    }

    /**
     * @dataProvider routeWhenNoAuthorizationProvider
     */
    public function testWhenNoAuthorization($method, $route)
    {
        $route = str_replace('{facilityId}', $this->facility->id, $route);

        $response = $this
            ->actingAs($this->noFacilityUser)
            ->$method($route);

        $response
            ->assertStatus(403)
            ->assertJson([]);
    }
}
