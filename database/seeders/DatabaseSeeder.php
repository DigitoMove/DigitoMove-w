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
        if (User::where('role', '!=', 'admin')->count() === 0) {
            User::factory(7)->create();
        }

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
            foreach ([
                ['type' => 'hero', 'heading' => $page->title, 'body' => $page->summary, 'cta_label' => 'Explore more', 'cta_url' => '#', 'sort_order' => 0],
                ['type' => 'content', 'heading' => 'Designed for clarity', 'body' => 'Use this section to explain the value behind the page with focused, easy-to-scan content.', 'sort_order' => 1],
                ['type' => 'cta', 'heading' => 'Ready to take the next step?', 'body' => 'Turn attention into action with a clear next move.', 'cta_label' => 'Get started', 'cta_url' => '#', 'sort_order' => 2],
            ] as $section) {
                $page->sections()->updateOrCreate(['sort_order' => $section['sort_order']], $section);
            }
        });

        if (Inquiry::count() === 0) {
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
        }

        if (PageView::count() === 0) {
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

        foreach ([
            [
                'title' => 'Ulrika Institute Website', 'slug' => 'ulrika-institute-website', 'type' => 'website',
                'client' => 'Ulrika Institute of Home Economics', 'summary' => 'A clearer digital presence for prospective students and the institute community.',
                'description' => 'A responsive institute website helping prospective students understand courses, activities, and how to take the next step.',
                'cover_image' => 'assets/img/exhibition/ulrika/homepage-top.png', 'website_url' => 'https://ulrikainstitute.ac.ug',
                'screenshots' => ['assets/img/exhibition/ulrika/homepage-middle.png', 'assets/img/exhibition/ulrika/homepage-activities.png', 'assets/img/exhibition/ulrika/about-us.png'],
                'technologies' => ['Laravel', 'Bootstrap', 'Responsive design'], 'is_featured' => true,
            ],
            [
                'title' => 'Kisem Financial Services', 'slug' => 'kisem-financial-services', 'type' => 'website',
                'client' => 'Kisem Financial Services', 'summary' => 'A digital presence for a financial institution focused on accessible, low-interest lending.',
                'description' => 'A customer-facing website presenting financial services, loan options, and contact paths clearly.',
                'cover_image' => 'assets/img/exhibition/icons/kisemfs.png', 'website_url' => 'https://kisemfs.com',
                'technologies' => ['Web development', 'Responsive design'], 'is_featured' => true,
            ],
            [
                'title' => 'Layisi Uganda', 'slug' => 'layisi-uganda', 'type' => 'system',
                'client' => 'Layisi Uganda', 'summary' => 'An e-commerce connection platform with mobile and web experiences.',
                'description' => 'A business-to-business platform where buyers and sellers connect and exchange product information.',
                'cover_image' => 'assets/img/exhibition/icons/layisi.png', 'website_url' => 'https://layisi.ug',
                'technologies' => ['E-commerce', 'Web platform', 'Mobile application'], 'is_featured' => true,
            ],
            [
                'title' => 'Digito Institute Management System', 'slug' => 'digito-institute-management-system', 'type' => 'system',
                'client' => 'Digito Move', 'summary' => 'A system for managing institutes, semester reports, and academic transcripts.',
                'description' => 'An institute operations system supporting student records, academic reporting, and transcript generation.',
                'cover_image' => 'logo.png', 'demo_url' => 'https://dims.digitomove.com',
                'technologies' => ['Laravel', 'Management system', 'Reporting'], 'is_featured' => false,
            ],
            [
                'title' => 'Digito High School Management System', 'slug' => 'digito-high-school-management-system', 'type' => 'system',
                'client' => 'Digito Move', 'summary' => 'A digital administration system for secondary schools.',
                'description' => 'A school management platform designed to organise essential administration and academic workflows.',
                'cover_image' => 'logo.png', 'demo_url' => 'https://dhsms.digitomove.com',
                'technologies' => ['Laravel', 'School management', 'Reporting'], 'is_featured' => false,
            ],
            [
                'title' => 'Digito Spaces', 'slug' => 'digito-spaces', 'type' => 'mobile_app',
                'client' => 'Digito Move', 'summary' => 'A property-listing experience for rentals, homes, and commercial spaces.',
                'description' => 'A real-estate listing platform covering rentals and properties for sale or lease.',
                'cover_image' => 'assets/img/exhibition/icons/digitospaces.png', 'website_url' => 'https://digitospaces.com',
                'technologies' => ['Real estate', 'Mobile application', 'Web platform'], 'is_featured' => true,
            ],
            [
                'title' => 'Ulrika Guest House Website', 'slug' => 'ulrika-guest-house-website', 'type' => 'website',
                'client' => 'Ulrika Guest House', 'summary' => 'A hospitality website presenting accommodation, dining, and event services.',
                'description' => 'A welcoming online presence for guests exploring rooms, food, events, and other hospitality services.',
                'cover_image' => 'assets/img/exhibition/icons/ulrika-guest-house.png', 'website_url' => 'https://ulrikaguesthouse.com',
                'technologies' => ['Web development', 'Hospitality', 'Responsive design'], 'is_featured' => false,
            ],
        ] as $project) {
            Project::updateOrCreate(['slug' => $project['slug']], $project + [
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $admin = User::where('email', 'admin@digitomove.test')->first();
        Post::updateOrCreate(['slug' => 'start-with-the-operating-problem'], [
            'author_id' => $admin->id, 'title' => 'Start with the operating problem', 'slug' => 'start-with-the-operating-problem',
            'excerpt' => 'Why the best software conversations begin before anyone mentions features.',
            'body' => '<p>Strong digital products begin with a clear understanding of the work people are trying to do.</p><h2>Features are not outcomes</h2><p>A feature list can describe a system without explaining why it should exist. Start with the friction, the decision, or the opportunity.</p>',
            'category' => 'Product thinking', 'status' => 'published', 'reading_minutes' => 2, 'published_at' => now(),
        ]);

        foreach ([
            [
                'course' => ['title' => 'Basic Computer Training', 'slug' => 'basic-computer-training', 'level' => 'beginner', 'summary' => 'Build confidence using computers, productivity tools, email, and the internet.', 'description' => 'Learn computer concepts, essential operations, Microsoft Office applications, internet use, and practical digital skills.', 'cover_image' => 'assets/img/services/ict-basic-training.png', 'tuition_fee' => 250000, 'application_fee' => 50000, 'duration' => 'Flexible schedule'],
                'lessons' => ['The concept of a computer', 'Components and basic operations', 'Microsoft Word', 'Microsoft Excel', 'Microsoft PowerPoint', 'Microsoft Access and Publisher', 'The internet and email', 'Phones and other operating systems'],
            ],
            [
                'course' => ['title' => 'Advanced Programming', 'slug' => 'advanced-programming', 'level' => 'advanced', 'summary' => 'Develop practical programming skills and learn how production software is designed and built.', 'description' => 'Move from programming fundamentals through web development, APIs, mobile applications, hosting, integrations, and software distribution.', 'cover_image' => 'assets/img/services/advanced-computer-training.png', 'tuition_fee' => 450000, 'application_fee' => 50000, 'duration' => 'Flexible schedule'],
                'lessons' => ['Programming fundamentals', 'Static web development with HTML, CSS, and JavaScript', 'Dynamic web development with PHP', 'Python and Django', 'Web API development', 'Mobile development with Dart and Flutter', 'Hosting and deployment', 'Firebase and API integration', 'App distribution'],
            ],
        ] as $package) {
            $course = Course::updateOrCreate(['slug' => $package['course']['slug']], $package['course'] + ['delivery_mode' => 'hybrid', 'status' => 'published', 'applications_open' => true]);
            collect($package['lessons'])->each(function ($title, $index) use ($course) {
                $slug = \Illuminate\Support\Str::slug($title);
                $course->lessons()->updateOrCreate(['slug' => $slug], [
                    'title' => $title,
                    'summary' => $index === 0 ? 'Open this free preview to understand the learning path and how to prepare.' : 'Build practical knowledge through guided learning and exercises.',
                    'content' => $index === 0 ? '<p>This preview introduces the learning path, expected outcomes, and the practical tools used in the course.</p>' : null,
                    'sort_order' => $index,
                    'is_preview' => $index === 0,
                ]);
            });
        }
    }
}
