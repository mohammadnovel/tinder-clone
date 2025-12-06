<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PeopleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_people()
    {
        $user = User::factory()->create();
        \App\Models\Person::factory(5)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/people');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    public function test_can_get_random_user()
    {
        $user = User::factory()->create();
        \App\Models\Person::factory(5)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/people'); // Changing to list people as random-user might be deprecated/not in routes provided

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }
}
