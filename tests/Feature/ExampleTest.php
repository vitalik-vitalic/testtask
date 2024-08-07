<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_users_show(): void
    {
        /*$user = User::factory()->create([
            'id' => 5,
            'name' => 'John Doe',
        ]);*/

        $user = User::find(5);

        $response = $this->actingAs($user)->get('api/users/5');
        $response->assertStatus(200);

        $response->assertJson([
            'name' => $user->name
        ]);

    }
}
