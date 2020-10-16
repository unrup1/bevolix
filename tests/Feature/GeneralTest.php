<?php

namespace Tests\Feature;

use App\Facility;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneralTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHomepage()
    {
        $response = $this->get('/');

        $response
            ->assertStatus(200);

        // check if exist in html
        $this->assertStringContainsString('<div id="app"><app></app></div>', preg_replace('/\n+ */', '', $response->getContent()));
    }

    public function testRandomSite()
    {
        $response = $this->get('/test/random');

        $response
            ->assertStatus(200);

        // check if exist in html
        $this->assertStringContainsString('<div id="app"><app></app></div>', preg_replace('/\n+ */', '', $response->getContent()));
    }

    public function testPasswordReset()
    {
        // site should redirect to vue app
        $response = $this->get('/password/reset/2f42e4d5c03071611e000171d97b043c5a8f95938720a1ad4e22c9a2aef500d7?email=test@test.de');

        $response
            ->assertStatus(200);

        // check if exist in html
        $this->assertStringContainsString('<div id="app"><app></app></div>', preg_replace('/\n+ */', '', $response->getContent()));
    }

    public function testCoronaForm() {
        $user = User::factory()->create();
        $facility = Facility::factory()->create([
            'user_id' => $user
        ]);

        // site should redirect to vue app
        $response = $this->get('/c/' . $facility->corona_hash);

        $response
            ->assertStatus(200);

        // check if exist in html
        $this->assertStringContainsString('<div id="app"><corona-app></corona-app></div>', preg_replace('/\n+ */', '', $response->getContent()));
    }

    public function testCoronaFormWhenNotExist() {
        // site should redirect to vue app
        $response = $this->get('/c/khjdsfauhj');

        $response
            ->assertStatus(200)
            ->assertSeeText('Upps')
            ->assertSeeText('Leider konnte das Formular nicht gefunden werden...');
    }
}
