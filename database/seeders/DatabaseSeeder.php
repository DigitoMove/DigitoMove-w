<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Page;
use App\Models\PageView;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(8)->create();

        $pages = collect([
            ['title' => 'Home', 'slug' => 'home', 'eyebrow' => 'Move with confidence', 'summary' => 'A clear, conversion-focused home page.', 'status' => 'published', 'published_at' => now()->subDays(60)],
            ['title' => 'Services', 'slug' => 'services', 'eyebrow' => 'What we do', 'summary' => 'A flexible overview of core services.', 'status' => 'published', 'published_at' => now()->subDays(32)],
            ['title' => 'About us', 'slug' => 'about-us', 'eyebrow' => 'Built around people', 'summary' => 'The company story and operating principles.', 'status' => 'review'],
            ['title' => 'Contact', 'slug' => 'contact', 'eyebrow' => 'Start a conversation', 'summary' => 'A focused contact and lead capture page.', 'status' => 'published', 'published_at' => now()->subDays(15)],
            ['title' => 'Insights', 'slug' => 'insights', 'eyebrow' => 'Useful thinking', 'summary' => 'Editorial landing page.', 'status' => 'draft'],
        ])->map(fn ($data) => Page::create($data));

        $pages->each(function (Page $page) {
            $page->sections()->createMany([
                ['type' => 'hero', 'heading' => $page->title, 'body' => $page->summary, 'cta_label' => 'Explore more', 'cta_url' => '#', 'sort_order' => 0],
                ['type' => 'content', 'heading' => 'Designed for clarity', 'body' => 'Use this section to explain the value behind the page with focused, easy-to-scan content.', 'sort_order' => 1],
                ['type' => 'cta', 'heading' => 'Ready to take the next step?', 'body' => 'Turn attention into action with a clear next move.', 'cta_label' => 'Get started', 'cta_url' => '#', 'sort_order' => 2],
            ]);
        });

        foreach (range(1, 36) as $index) {
            Inquiry::create([
                'name' => fake()->name(),
                'email' => fake()->safeEmail(),
                'phone' => fake()->optional()->phoneNumber(),
                'subject' => fake()->randomElement(['Service inquiry', 'Partnership', 'Project estimate', 'General question']),
                'message' => fake()->paragraph(),
                'source' => fake()->randomElement(['website', 'referral', 'campaign', 'social']),
                'status' => fake()->randomElement(['new', 'new', 'contacted', 'qualified', 'closed']),
                'read_at' => fake()->boolean(65) ? now()->subDays(fake()->numberBetween(0, 20)) : null,
                'created_at' => now()->subDays(fake()->numberBetween(0, 45)),
                'updated_at' => now()->subDays(fake()->numberBetween(0, 20)),
            ]);
        }

        foreach (range(1, 420) as $index) {
            $page = $pages->random();
            PageView::create([
                'page_id' => $page->id,
                'path' => '/'.$page->slug,
                'referrer' => fake()->randomElement(['google.com', 'linkedin.com', 'direct', 'instagram.com']),
                'visitor_hash' => hash('sha256', fake()->uuid()),
                'viewed_at' => now()->subDays(fake()->numberBetween(0, 29))->subHours(fake()->numberBetween(0, 23)),
            ]);
        }
    }
}
