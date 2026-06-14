<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use App\Models\Page;
use App\Models\PageView;
use App\Models\Project;
use App\Models\Post;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(['email' => 'admin@digitomove.test'], [
            'name' => 'Digito Move Admin', 'role' => 'admin', 'phone' => '+256 700 000000',
            'bio' => 'Workspace administrator', 'password' => Hash::make('Admin123!'), 'email_verified_at' => now(),
        ]);
        User::factory(7)->create();

        $pages = collect([
            ['title' => 'Home', 'slug' => 'home', 'eyebrow' => 'Move with confidence', 'summary' => 'A clear, conversion-focused home page.', 'status' => 'published', 'published_at' => now()->subDays(60)],
            ['title' => 'Services', 'slug' => 'services', 'eyebrow' => 'What we do', 'summary' => 'A flexible overview of core services.', 'status' => 'published', 'published_at' => now()->subDays(32)],
            ['title' => 'About us', 'slug' => 'about-us', 'eyebrow' => 'Built around people', 'summary' => 'The company story and operating principles.', 'status' => 'review'],
            ['title' => 'Contact', 'slug' => 'contact', 'eyebrow' => 'Start a conversation', 'summary' => 'A focused contact and lead capture page.', 'status' => 'published', 'published_at' => now()->subDays(15)],
            ['title' => 'Insights', 'slug' => 'insights', 'eyebrow' => 'Useful thinking', 'summary' => 'Editorial landing page.', 'status' => 'draft'],
            ['title' => 'Selected work', 'slug' => 'work', 'eyebrow' => 'Proof in practice', 'summary' => 'A selection of digital work and product outcomes.', 'status' => 'published', 'published_at' => now()->subDays(12)],
            ['title' => 'Privacy', 'slug' => 'privacy', 'eyebrow' => 'Clear data handling', 'summary' => 'How Digito Move handles information shared through the website.', 'status' => 'published', 'published_at' => now()->subDays(10)],
        ])->map(fn ($data) => Page::updateOrCreate(['slug' => $data['slug']], $data));

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

        Project::updateOrCreate(['slug' => 'ulrika-institute-website'], [
            'title' => 'Ulrika Institute Website', 'slug' => 'ulrika-institute-website', 'type' => 'website',
            'client' => 'Ulrika Institute of Home Economics', 'summary' => 'A clearer digital presence for prospective students and the institute community.',
            'description' => 'A responsive website focused on helping prospective students understand the institute, its activities, and how to take the next step.',
            'cover_image' => 'assets/img/exhibition/ulrika/homepage-top.png', 'screenshots' => ['assets/img/exhibition/ulrika/homepage-middle.png', 'assets/img/exhibition/ulrika/homepage-activities.png'],
            'technologies' => ['Laravel', 'Bootstrap', 'Responsive design'], 'status' => 'published', 'is_featured' => true, 'published_at' => now(),
        ]);

        $admin = User::where('email', 'admin@digitomove.test')->first();
        Post::updateOrCreate(['slug' => 'start-with-the-operating-problem'], [
            'author_id' => $admin->id, 'title' => 'Start with the operating problem', 'slug' => 'start-with-the-operating-problem',
            'excerpt' => 'Why the best software conversations begin before anyone mentions features.',
            'body' => '<p>Strong digital products begin with a clear understanding of the work people are trying to do.</p><h2>Features are not outcomes</h2><p>A feature list can describe a system without explaining why it should exist. Start with the friction, the decision, or the opportunity.</p>',
            'category' => 'Product thinking', 'status' => 'published', 'reading_minutes' => 2, 'published_at' => now(),
        ]);

        foreach ([
            ['Basic Computer Training', 'basic-computer-training', 'beginner', 'Build confidence using computers, productivity tools, email, and the internet.', 350000, 50000, '6 weeks'],
            ['Advanced Programming', 'advanced-programming', 'advanced', 'Develop practical programming skills and learn how production software is designed and built.', 1200000, 100000, '16 weeks'],
        ] as [$title, $slug, $level, $summary, $tuition, $applicationFee, $duration]) {
            $course = Course::updateOrCreate(['slug' => $slug], ['title' => $title, 'level' => $level, 'summary' => $summary, 'description' => $summary, 'tuition_fee' => $tuition, 'application_fee' => $applicationFee, 'duration' => $duration, 'delivery_mode' => 'hybrid', 'status' => 'published', 'applications_open' => true]);
            collect([
                ['title' => 'Welcome and orientation', 'slug' => 'welcome-and-orientation', 'summary' => 'Understand the learning path and tools.', 'content' => '<p>Welcome to the course. This preview introduces the learning path and how to prepare.</p>', 'sort_order' => 0, 'is_preview' => true],
                ['title' => 'Core foundations', 'slug' => 'core-foundations', 'summary' => 'Build the essential concepts through guided practice.', 'sort_order' => 1],
                ['title' => 'Practical project', 'slug' => 'practical-project', 'summary' => 'Apply what you have learned to a real project.', 'sort_order' => 2],
            ])->each(fn ($lesson) => $course->lessons()->updateOrCreate(['slug' => $lesson['slug']], $lesson));
        }
    }
}
