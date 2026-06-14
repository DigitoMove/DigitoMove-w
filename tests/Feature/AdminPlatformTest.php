<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_routes_are_guarded()
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/admin/projects')->assertRedirect('/login');
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_admin_can_login_and_manage_core_content()
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'role' => 'admin', 'password' => Hash::make('password123')]);
        $this->post('/login', ['email' => $admin->email, 'password' => 'password123'])->assertRedirect('/dashboard');

        $this->actingAs($admin)->post('/admin/projects', [
            'title' => 'Demo System', 'type' => 'system', 'summary' => 'A safe system demo.',
            'demo_url' => 'https://example.com/demo', 'demo_username' => 'demo', 'demo_password' => 'demo123',
            'technologies' => 'Laravel, MySQL', 'status' => 'published',
        ])->assertRedirect();
        $this->assertDatabaseHas('projects', ['slug' => 'demo-system', 'status' => 'published']);

        $this->actingAs($admin)->post('/admin/posts', [
            'title' => 'A useful article', 'excerpt' => 'A useful article excerpt.', 'body' => '<h2>Useful</h2><p>Practical content.</p>',
            'category' => 'Product', 'status' => 'published',
        ])->assertRedirect();
        $this->assertDatabaseHas('posts', ['slug' => 'a-useful-article']);
    }

    public function test_public_course_application_creates_payment_reference()
    {
        $course = Course::create([
            'title' => 'Basic Computer Training', 'slug' => 'basic-computer-training', 'level' => 'beginner',
            'summary' => 'Learn the basics.', 'tuition_fee' => 350000, 'application_fee' => 50000,
            'delivery_mode' => 'hybrid', 'status' => 'published', 'applications_open' => true,
        ]);

        $this->post('/learning/basic-computer-training/apply', [
            'name' => 'Student One', 'email' => 'student@example.com', 'phone' => '+256700000001',
            'education_level' => 'Secondary', 'motivation' => 'I want to improve my skills.',
        ])->assertOk();

        $this->assertDatabaseHas('course_applications', ['course_id' => $course->id, 'email' => 'student@example.com']);
        $this->assertDatabaseHas('payments', ['amount' => 50000, 'status' => 'pending']);
    }
}
