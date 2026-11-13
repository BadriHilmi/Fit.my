<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    public function test_profile_updates_body_metrics_and_auto_calculates_calorie_target(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patchJson('/profile', [
                'name' => 'Fit User',
                'email' => $user->email,
                'age' => 25,
                'height' => 175,
                'weight' => 70,
                'gender' => 'male',
                'goal_mode' => 'auto',
                'activity_level' => '1.55',
            ]);

        $response->assertOk()
            ->assertJsonPath('user.weight', 70)
            ->assertJsonPath('metrics.bmi', 22.9)
            ->assertJsonPath('metrics.bmi_category', 'Healthy Weight');

        $user->refresh();
        $this->assertSame(70, $user->weight);
        $this->assertSame(175, $user->height);
        $this->assertGreaterThan(2500, (int) $user->calorie_intake);
    }

    public function test_profile_supports_custom_calorie_target(): void
    {
        $user = User::factory()->create(['calorie_intake' => 2200]);

        $this->actingAs($user)
            ->patchJson('/profile', [
                'name' => $user->name,
                'email' => $user->email,
                'goal_mode' => 'custom',
                'calorie_intake' => 1850,
            ])
            ->assertOk()
            ->assertJsonPath('user.calorie_intake', 1850);

        $this->assertSame(1850, (int) $user->fresh()->calorie_intake);
    }
}
