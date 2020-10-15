<?php

namespace Tests\Feature;

use App\Facility;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiFacilityTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->noFacilityUser = factory(User::class)->create();
        $this->facility = factory(Facility::class)->create([
            'user_id' => $this->user
        ]);
    }

    public function testGetAllForUser()
    {
        $response = $this
            ->actingAs($this->noFacilityUser)
            ->get('/api/facility');

        $response
            ->assertStatus(200)
            ->assertJson([]);
    }

    public function testGetAllForUserWithData()
    {
        $facilities = factory(Facility::class, 2)->create([
            'user_id' => $this->user
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/api/facility');

        $response
            ->assertStatus(200)
            ->assertJson(array_merge([$this->facility->toArray()], $facilities->toArray()));
    }

    public function testCreate()
    {
        $response = $this
            ->actingAs($this->user)
            ->json('post', '/api/facility', [
                'name' => 'Test Einrichtung',
                'description' => 'Et a alias autem autem occaecati. Dignissimos et molestiae ratione ipsam ex nostrum placeat. Voluptatem repellendus est repellat. Alias facilis atque asperiores in at consequatur.',
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('name', 'Test Einrichtung')
            ->assertJsonPath('description', 'Et a alias autem autem occaecati. Dignissimos et molestiae ratione ipsam ex nostrum placeat. Voluptatem repellendus est repellat. Alias facilis atque asperiores in at consequatur.')
            ->assertJsonPath('has_image', false)
            ->assertJsonPath('user_id', $this->user->id);

        // should be a 8 letter hashid
        $this->assertEquals(8, strlen($response->json('corona_hash')));
    }

    public function testCreateForValidation()
    {
        $response = $this
            ->actingAs($this->user)
            ->json('post', '/api/facility', [
                'name' => null,
                'description' => null
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function testGetOne()
    {
        $facility = factory(Facility::class)->create([
            'user_id' => $this->user
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/api/facility/' . $facility->id);

        $response
            ->assertStatus(200)
            ->assertJson($facility->toArray());
    }

    public function testUpdate()
    {
        $facility = factory(Facility::class)->create([
            'user_id' => $this->user
        ]);

        $response = $this
            ->actingAs($this->user)
            ->json('put', '/api/facility/' . $facility->id, [
                'name' => 'Test Einrichtung',
                'description' => 'Et a alias autem autem occaecati. Dignissimos et molestiae ratione ipsam ex nostrum placeat. Voluptatem repellendus est repellat. Alias facilis atque asperiores in at consequatur.',
            ]);

        $response
            ->assertStatus(200)
            ->assertJsonPath('name', 'Test Einrichtung')
            ->assertJsonPath('description', 'Et a alias autem autem occaecati. Dignissimos et molestiae ratione ipsam ex nostrum placeat. Voluptatem repellendus est repellat. Alias facilis atque asperiores in at consequatur.');
    }

    public function testUpdateForValidation()
    {
        $facility = factory(Facility::class)->create([
            'user_id' => $this->user
        ]);

        $response = $this
            ->actingAs($this->user)
            ->json('put', '/api/facility/' . $facility->id, [
                'name' => null,
                'description' => null
            ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function testDelete()
    {
        $facility = factory(Facility::class)->create([
            'user_id' => $this->user
        ]);

        $response = $this
            ->actingAs($this->user)
            ->delete('/api/facility/' . $facility->id);

        $response
            ->assertStatus(200)
            ->assertJson(['deleted' => true]);

        $this->assertDeleted($facility);
    }

    public function routeWhenNoAuthorizationProvider() {
        yield ['get', '/api/facility/'];
        yield ['put', '/api/facility/'];
        yield ['delete', '/api/facility/'];
    }

    /**
     * @dataProvider routeWhenNoAuthorizationProvider
     */
    public function testWhenNoAuthorization($method, $route)
    {
        $response = $this
            ->actingAs($this->noFacilityUser)
            ->$method($route . $this->facility->id);

        $response
            ->assertStatus(403)
            ->assertJson([]);
    }

    public function routeNotFoundProvider() {
        $id = 789789732;
        return [
            ['get', '/api/facility/' . $id],
            ['put', '/api/facility/' . $id],
            ['delete', '/api/facility/' . $id],
        ];
    }

    /**
     * @dataProvider routeNotFoundProvider
     */
    public function testWhenNotExist($method, $route)
    {
        $response = $this
            ->actingAs($this->user)
            ->$method($route);

        $response
            ->assertStatus(404)
            ->assertJson([]);
    }
}
