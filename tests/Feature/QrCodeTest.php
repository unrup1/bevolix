<?php

namespace Tests\Feature;

use App\Facility;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->noFacilityUser = User::factory()->create();
        $this->facility = Facility::factory()->create([
            'user_id' => $this->user
        ]);
    }

    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
