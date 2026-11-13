<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthenticationLogicTest extends TestCase
{
    use RefreshDatabase; 

    public function test_guest_cannot_access_profile_page(): void
    {
        $response = $this->get('/profile');

        // Assert they are redirected (HTTP 302) to the login page
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * POSITIVE TEST: A user can register successfully.
     */
    public function test_user_can_register_as_candidate(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'user_type' => 'candidate',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert redirect to profile after successful registration
        $response->assertRedirect('/profile');

        // Assert the user actually exists in the database now
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'user_type' => 'candidate',
        ]);
    }

    /**
     * NEGATIVE TEST: Wrong passwords fail to log in.
     */
    public function test_user_cannot_login_with_incorrect_password(): void
    {
        // First, create a fake user in the testing database
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correctpassword'),
        ]);

        // Try to log in with the WRONG password
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert they stay on the current page (redirect back) and there are errors
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email'); 
        $this->assertGuest(); // Confirms they are NOT logged in
    }
}