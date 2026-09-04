<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'email' => 'test@eduhub.test',
            'password' => bcrypt('password'),
            'role' => 'student',
        ]);

        $response = $this->post('/login', [
            'email' => 'test@eduhub.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_register_as_student(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Student',
            'email' => 'newstudent@eduhub.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'student',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'newstudent@eduhub.test',
            'role' => 'student',
        ]);
        $response->assertRedirect('/dashboard');
    }

    public function test_users_can_register_as_instructor(): void
    {
        $response = $this->post('/register', [
            'name' => 'New Instructor',
            'email' => 'newinstructor@eduhub.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'instructor',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'newinstructor@eduhub.test',
            'role' => 'instructor',
        ]);
        $response->assertRedirect('/instructor/dashboard');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
