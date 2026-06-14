<?php

namespace Tests\Feature;

use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_are_available()
    {
        foreach (['/', '/about', '/services', '/work', '/blog', '/privacy', '/contact', '/learning'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_visitor_can_submit_an_inquiry()
    {
        $response = $this->post('/contact', [
            'name' => 'Jane Client',
            'email' => 'jane@example.com',
            'phone' => '+256700000000',
            'subject' => 'Custom business software',
            'message' => 'We need a system that helps our team manage field operations.',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inquiries', [
            'email' => 'jane@example.com',
            'status' => 'new',
            'source' => 'website',
        ]);
    }
}
