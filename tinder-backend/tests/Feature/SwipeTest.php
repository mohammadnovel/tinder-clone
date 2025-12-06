<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SwipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_like_person()
    {
        $user = User::factory()->create();
        $target = \App\Models\Person::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/v1/swipe/like", ['person_id' => $target->id]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('swipes', [
            'swiper_id' => $user->id,
            'swiped_id' => $target->id,
            'type' => 'like'
        ]);
    }

    public function test_can_dislike_person()
    {
        $user = User::factory()->create();
        $target = \App\Models\Person::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/v1/swipe/dislike", ['person_id' => $target->id]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('swipes', [
            'swiper_id' => $user->id,
            'swiped_id' => $target->id,
            'type' => 'dislike'
        ]);
    }

    public function test_match_creation()
    {
        $user = User::factory()->create();
        $target = \App\Models\Person::factory()->create();

        $response = $this->actingAs($user)
            ->postJson("/api/v1/swipe/like", ['person_id' => $target->id]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'is_match'
                ]
            ]);
    }
}
