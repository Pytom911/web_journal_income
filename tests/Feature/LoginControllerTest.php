<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_login_page_renders_for_guest(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk()
            ->assertSee('Masuk');
    }

    public function test_valid_credentials_authenticate_and_redirect_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@journal.test',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'owner@journal.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_show_login_error(): void
    {
        User::factory()->create([
            'email' => 'owner@journal.test',
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'owner@journal.test',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ]);
        $this->assertGuest();
    }

    public function test_authenticated_user_can_log_out(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }
}
