<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_for_guests_with_required_sections_and_guest_calculator(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSeeText('Track your calories and reach your daily nutrition goals.');
        $response->assertSee(route('login'), false);
        $response->assertSee(route('register'), false);
        $response->assertSee(route('calorie-intake.calculate'), false);
        $response->assertSee('Sign up to save your goal and start tracking meals.', false);
    }

    public function test_authenticated_standard_user_is_redirected_from_landing_page_to_dashboard(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_authenticated_admin_user_is_redirected_from_landing_page_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'user_type' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/');

        $response->assertRedirect(route('Admin.dashboard'));
    }

    public function test_guest_calculator_json_endpoint_computes_calories_without_saving_user_rows(): void
    {
        $initialUserCount = User::count();

        $response = $this->postJson(route('calorie-intake.calculate'), [
            'age' => 26,
            'gender' => 'male',
            'weight' => 70,
            'height' => 175,
            'activity_level' => '1.55',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['calorieIntake', 'bmr', 'activity_level']);

        $this->assertSame($initialUserCount, User::count());
    }
}
