<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'StrongPass123!',
            'password_confirmation' => 'StrongPass123!',
            'age' => 25,
            'height' => 175,
            'weight' => 70,
            'gender' => 'male',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_registration_fails_if_password_does_not_meet_requirements(): void
    {
        $response = $this->post('/register', [
            'name' => 'Weak User',
            'email' => 'weak@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
            'age' => 25,
            'height' => 175,
            'weight' => 70,
            'gender' => 'male',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertGuest();
    }
}
