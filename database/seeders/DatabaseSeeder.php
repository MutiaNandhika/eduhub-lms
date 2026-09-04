<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Notification;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Admin User
        $admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@eduhub.test',
            'password' => $password,
            'role' => 'admin',
            'bio' => 'Lead System Administrator & Curriculum Director at EduHub.',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        // 2. Instructors
        $instructor1 = User::create([
            'name' => 'Alex Rivers',
            'email' => 'instructor@eduhub.test',
            'password' => $password,
            'role' => 'instructor',
            'bio' => 'Senior Full-Stack Architect with 10+ years specializing in Laravel, Vue.js, and high-concurrency systems.',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $instructor2 = User::create([
            'name' => 'Sarah Chen',
            'email' => 'sarah.chen@eduhub.test',
            'password' => $password,
            'role' => 'instructor',
            'bio' => 'Principal Frontend Engineer and Design Systems advocate. Passionate about Vue 3, TypeScript and web accessibility.',
            'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $instructor3 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi.santoso@eduhub.test',
            'password' => $password,
            'role' => 'instructor',
            'bio' => 'Cloud Solutions Architect and Database Consultant. Specialized in PostgreSQL optimization, Docker, and DevOps pipelines.',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $instructors = [$instructor1, $instructor2, $instructor3];

        // 3. Demo Student + 14 Other Students
        $primaryStudent = User::create([
            'name' => 'Demo Student',
            'email' => 'student@eduhub.test',
            'password' => $password,
            'role' => 'student',
            'bio' => 'Software developer enthusiast learning modern full-stack web engineering.',
            'avatar' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=150&auto=format&fit=crop&q=80',
            'email_verified_at' => now(),
        ]);

        $studentNames = [
            'Rizky Pratama', 'Jessica Taylor', 'David Miller', 'Anita Wijaya', 'Marcus Johnson',
            'Elena Rostova', 'Kevin Tan', 'Maya Putri', 'Daniel Evans', 'Chloe Bennett',
            'Fajar Nugroho', 'Liam O\'Connor', 'Sophia Martinez', 'Ryan Cooper', 'Siti Rahma'
        ];

        $students = [$primaryStudent];
        foreach ($studentNames as $idx => $sName) {
            $students[] = User::create([
                'name' => $sName,
                'email' => 'student' . ($idx + 2) . '@eduhub.test',
                'password' => $password,
                'role' => 'student',
                'avatar' => 'https://images.unsplash.com/photo-' . (1500000000000 + $idx * 1000000) . '?w=150&auto=format&fit=crop&q=80',
                'email_verified_at' => now(),
            ]);
        }

        // 4. Categories
        $categoriesData = [
            [
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Master frontend and backend web frameworks including Vue.js, Laravel, React, and Node.',
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Database Engineering',
                'slug' => 'database-engineering',
                'description' => 'Relational database architecture, SQL optimization, indexing, and data modelling.',
                'image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'UI/UX & Product Design',
                'slug' => 'ui-ux-design',
                'description' => 'User interface principles, Figma design systems, typography, and interactive prototyping.',
                'image' => 'https://images.unsplash.com/photo-1581291518655-9523c932edcf?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Cloud & DevOps',
                'slug' => 'cloud-devops',
                'description' => 'Docker containerization, CI/CD pipelines, Kubernetes, AWS, and server deployment.',
                'image' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Mobile App Development',
                'slug' => 'mobile-development',
                'description' => 'Cross-platform and native mobile app engineering with Flutter and React Native.',
                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Cyber Security',
                'slug' => 'cyber-security',
                'description' => 'Application security, penetration testing, defensive programming, and authentication protocols.',
                'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Data Science & AI',
                'slug' => 'data-science-ai',
                'description' => 'Python data pipelines, Machine Learning algorithms, and Generative AI integrations.',
                'image' => 'https://images.unsplash.com/photo-1555949963-ff9fe0c870eb?w=500&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Programming Languages',
                'slug' => 'programming-languages',
                'description' => 'Foundational algorithms, TypeScript, Go, Rust, and Object-Oriented software design.',
                'image' => 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?w=500&auto=format&fit=crop&q=80',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $c) {
            $categories[$c['slug']] = Category::create([
                'name' => $c['name'],
                'slug' => $c['slug'],
                'description' => $c['description'],
                'image' => $c['image'],
                'is_active' => true,
            ]);
        }

        // 5. Rich Realistic Courses
        $coursesDefinitions = [
            [
                'title' => 'Laravel 11 From Zero to Production',
                'slug' => 'laravel-from-zero-to-production',
                'category_slug' => 'web-development',
                'instructor' => $instructor1,
                'level' => 'intermediate',
                'price' => 49.99,
                'discount_price' => 29.99,
                'duration_minutes' => 320,
                'short_description' => 'Build scalable, secure web apps using modern Laravel 11, Eloquent ORM, background queues, and production deployment strategies.',
                'description' => "Welcome to the definitive hands-on masterclass for modern Laravel development. You will learn everything required to architect, test, and ship high-performance PHP applications with confidence.\n\nKey Highlights:\n- Modern routing, middleware, and dependency injection architecture\n- Advanced Eloquent relationships, subqueries, and eager loading optimization\n- Form Requests, custom validation rules, and authorization policies\n- Asynchronous jobs, Redis queues, and scheduled tasks\n- Database migrations, seeders, and model factories\n- Writing bulletproof automated feature tests with PHPUnit and Pest.",
                'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
            ],
            [
                'title' => 'Vue.js 3 & TypeScript Mastery',
                'slug' => 'vue-js-typescript-mastery',
                'category_slug' => 'web-development',
                'instructor' => $instructor2,
                'level' => 'intermediate',
                'price' => 39.99,
                'discount_price' => null,
                'duration_minutes' => 280,
                'short_description' => 'Master Vue 3 Composition API, script setup, strict TypeScript typing, Pinia state management, and reusable components.',
                'description' => "Elevate your frontend engineering capability by mastering Vue 3 with TypeScript. We dissect reactive state, custom composables, slots, transitions, and robust component architecture.\n\nWhat you will build:\n- Strongly-typed reactive stores and custom hooks\n- Headless accessible UI components with full keyboard support\n- Interactive SPA applications with Inertia.js server-driven hydration.",
                'thumbnail' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=b0IZo2Aho2Y',
            ],
            [
                'title' => 'PostgreSQL Essentials & Query Optimization',
                'slug' => 'postgresql-essentials-query-optimization',
                'category_slug' => 'database-engineering',
                'instructor' => $instructor3,
                'level' => 'advanced',
                'price' => 59.99,
                'discount_price' => 34.99,
                'duration_minutes' => 240,
                'short_description' => 'Deep-dive into PostgreSQL relational design, indexes (B-Tree, GIN, GiST), EXPLAIN ANALYZE tuning, and concurrency locking.',
                'description' => "Stop treating the database as a black box. This masterclass unpacks PostgreSQL query planning, composite index optimization, table partitioning, transactions isolation levels, and vacuuming strategies for high-scale applications.",
                'thumbnail' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=qw--VYLpxG4',
            ],
            [
                'title' => 'Tailwind CSS for SaaS UI/UX Design',
                'slug' => 'tailwind-css-saas-ui-ux-design',
                'category_slug' => 'ui-ux-design',
                'instructor' => $instructor2,
                'level' => 'beginner',
                'price' => 0.00,
                'discount_price' => null,
                'duration_minutes' => 180,
                'short_description' => 'Craft modern, high-converting commercial SaaS interfaces with Tailwind CSS utility classes and clean design systems.',
                'description' => "Learn the subtle art of visual hierarchy, consistent spacing tokens, harmonious dark modes, fluid micro-interactions, and accessible form controls using pure Tailwind CSS.",
                'thumbnail' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=elgqxmdVms8',
            ],
            [
                'title' => 'Docker & Kubernetes for Developers',
                'slug' => 'docker-kubernetes-for-developers',
                'category_slug' => 'cloud-devops',
                'instructor' => $instructor3,
                'level' => 'intermediate',
                'price' => 45.00,
                'discount_price' => 25.00,
                'duration_minutes' => 300,
                'short_description' => 'Containerize full-stack apps, write multi-stage Dockerfiles, manage volumes, and deploy microservices to Kubernetes clusters.',
                'description' => "Bridge the gap between local code and cloud production. Master Docker compose environments, lightweight alpine images, ingress controllers, config maps, and rolling zero-downtime updates.",
                'thumbnail' => 'https://images.unsplash.com/photo-1607799279861-4dd421887fb3?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=fqMOX6JJhGo',
            ],
            [
                'title' => 'Modern JavaScript ESNext Deep Dive',
                'slug' => 'modern-javascript-esnext-deep-dive',
                'category_slug' => 'programming-languages',
                'instructor' => $instructor1,
                'level' => 'beginner',
                'price' => 0.00,
                'discount_price' => null,
                'duration_minutes' => 210,
                'short_description' => 'Master modern ECMAScript features: Promises, Async/Await, Closures, Event Loop, Prototypes, and Modules.',
                'description' => "Demystify JavaScript internals. Gain full command over asynchronous concurrency, memory management, hoisting, the call stack, lexical scoping, and functional array pipelines.",
                'thumbnail' => 'https://images.unsplash.com/photo-1579468118864-1b9ea3c0db4a?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=W6NZfCO5SIk',
            ],
            [
                'title' => 'Git & GitHub Collaboration Fundamentals',
                'slug' => 'git-github-collaboration-fundamentals',
                'category_slug' => 'programming-languages',
                'instructor' => $instructor1,
                'level' => 'beginner',
                'price' => 0.00,
                'discount_price' => null,
                'duration_minutes' => 150,
                'short_description' => 'Professional version control: branch strategies, interactive rebasing, merge conflict resolution, and pull requests.',
                'description' => "Work seamlessly in collaborative engineering teams. Master git reflog, squash commits, cherry-pick, conventional commit messages, and automated GitHub Actions workflows.",
                'thumbnail' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=RGOj5yH7evk',
            ],
            [
                'title' => 'UI/UX Design Systems in Figma',
                'slug' => 'ui-ux-design-systems-figma',
                'category_slug' => 'ui-ux-design',
                'instructor' => $instructor2,
                'level' => 'intermediate',
                'price' => 35.00,
                'discount_price' => 19.99,
                'duration_minutes' => 200,
                'short_description' => 'Design scalable component libraries, auto-layout architectures, tokenized color palettes, and responsive grids.',
                'description' => "Transform into a product designer capable of creating enterprise Figma systems ready for seamless developer handoff with token variables and component variants.",
                'thumbnail' => 'https://images.unsplash.com/photo-1581291518655-9523c932edcf?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=c9Wg6Cb_YlU',
            ],
            [
                'title' => 'Web Application Security & OWASP Top 10',
                'slug' => 'web-application-security-owasp',
                'category_slug' => 'cyber-security',
                'instructor' => $instructor3,
                'level' => 'advanced',
                'price' => 69.99,
                'discount_price' => 39.99,
                'duration_minutes' => 260,
                'short_description' => 'Defend against SQL injection, XSS, CSRF, IDOR, SSRF, and broken access controls in production web applications.',
                'description' => "Learn how attackers find vulnerabilities and how to bulletproof your apps using defense-in-depth, Content Security Policies, rate limiting, and cryptographic signing.",
                'thumbnail' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=inWWhr5tnEA',
            ],
            [
                'title' => 'Python for Data Analysis & Automation',
                'slug' => 'python-data-analysis-automation',
                'category_slug' => 'data-science-ai',
                'instructor' => $instructor1,
                'level' => 'beginner',
                'price' => 29.99,
                'discount_price' => null,
                'duration_minutes' => 240,
                'short_description' => 'Automate repetitive workflows, scrape web data with BeautifulSoup, and manipulate tabular datasets with Pandas.',
                'description' => "Turn raw datasets into actionable insights and automated workflows using practical Python scripting, Pandas dataframes, and Matplotlib visual analytics.",
                'thumbnail' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=_uQrJ0TkZlc',
            ],
            [
                'title' => 'Flutter & Dart: Cross-Platform Mobile Apps',
                'slug' => 'flutter-dart-mobile-apps',
                'category_slug' => 'mobile-development',
                'instructor' => $instructor2,
                'level' => 'intermediate',
                'price' => 49.99,
                'discount_price' => 29.99,
                'duration_minutes' => 310,
                'short_description' => 'Build high-performance iOS and Android applications with a single Dart codebase, custom widgets, and REST APIs.',
                'description' => "Complete roadmap to crafting native-feeling mobile applications with Flutter. Covers state management with Riverpod, offline SQLite sync, and native device camera/location access.",
                'thumbnail' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=x0uinJvhNxI',
            ],
            [
                'title' => 'Inertia.js Full-Stack Architecture',
                'slug' => 'inertia-js-fullstack-architecture',
                'category_slug' => 'web-development',
                'instructor' => $instructor1,
                'level' => 'advanced',
                'price' => 39.99,
                'discount_price' => 19.99,
                'duration_minutes' => 220,
                'short_description' => 'Build single-page apps without building a separate REST/GraphQL API using the modern monolith Inertia.js bridge.',
                'description' => "Experience the joy of building reactive Single Page Applications with Laravel and Vue 3 without the client-side routing and token state boilerplate.",
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80',
                'preview_video' => 'https://www.youtube.com/watch?v=Fao1h3j3qJg',
            ],
        ];

        $createdCourses = [];

        foreach ($coursesDefinitions as $cDef) {
            $course = Course::create([
                'instructor_id' => $cDef['instructor']->id,
                'category_id' => $categories[$cDef['category_slug']]->id,
                'title' => $cDef['title'],
                'slug' => $cDef['slug'],
                'short_description' => $cDef['short_description'],
                'description' => $cDef['description'],
                'thumbnail' => $cDef['thumbnail'],
                'preview_video' => $cDef['preview_video'],
                'level' => $cDef['level'],
                'language' => 'English',
                'price' => $cDef['price'],
                'discount_price' => $cDef['discount_price'],
                'duration_minutes' => $cDef['duration_minutes'],
                'status' => 'published',
                'published_at' => now()->subDays(rand(10, 60)),
            ]);

            // Create 3 Modules per Course
            $module1 = CourseModule::create([
                'course_id' => $course->id,
                'title' => 'Module 1: Foundations & Architecture Setup',
                'description' => 'Core architectural fundamentals and initial development environment setup.',
                'sort_order' => 1,
            ]);

            $module2 = CourseModule::create([
                'course_id' => $course->id,
                'title' => 'Module 2: Core Concepts & Implementation',
                'description' => 'Hands-on practical techniques, pattern design, and implementation.',
                'sort_order' => 2,
            ]);

            $module3 = CourseModule::create([
                'course_id' => $course->id,
                'title' => 'Module 3: Advanced Optimization & Assessment',
                'description' => 'Performance tuning, security practices, and final comprehensive assessment.',
                'sort_order' => 3,
            ]);

            // Lessons for Module 1
            $l1 = Lesson::create([
                'module_id' => $module1->id,
                'title' => 'Introduction & Course Objectives',
                'slug' => Str::slug($course->title) . '-intro',
                'type' => 'video',
                'video_url' => $cDef['preview_video'],
                'duration_minutes' => 12,
                'sort_order' => 1,
                'is_preview' => true,
                'content' => "Welcome to {$course->title}!\n\nIn this initial lecture, we map out all the learning objectives, toolchains, and real-world projects we will build together throughout this masterclass.",
            ]);

            $l2 = Lesson::create([
                'module_id' => $module1->id,
                'title' => 'Environment & Project Setup',
                'slug' => Str::slug($course->title) . '-setup',
                'type' => 'article',
                'duration_minutes' => 18,
                'sort_order' => 2,
                'is_preview' => false,
                'content' => "### Architecture Setup Guidelines\n\nEnsure your local runtime environment meets the following specifications:\n- Node.js LTS and modern package manager\n- PHP / runtime compiler configured\n- Git repository configured with `.gitignore`\n\n```bash\n# Verify your development runtime\ngit --version\nnode -v\n```\n\nFollow best practices by never committing `.env` credentials to source control.",
            ]);

            // Lessons for Module 2
            $l3 = Lesson::create([
                'module_id' => $module2->id,
                'title' => 'Core Architecture & Patterns',
                'slug' => Str::slug($course->title) . '-core-patterns',
                'type' => 'video',
                'video_url' => $cDef['preview_video'],
                'duration_minutes' => 25,
                'sort_order' => 1,
                'is_preview' => false,
                'content' => "In this core module, we dive deep into production-tested design patterns, modular code organization, and decoupling business logic from presentation layers.",
            ]);

            $l4 = Lesson::create([
                'module_id' => $module2->id,
                'title' => 'Deep Dive & Practical Exercises',
                'slug' => Str::slug($course->title) . '-practical-exercises',
                'type' => 'article',
                'duration_minutes' => 20,
                'sort_order' => 2,
                'is_preview' => false,
                'content' => "### Hands-On Exercise\n\nReview the following implementation checklist:\n1. Apply single responsibility principle to domain handlers.\n2. Write unit assertions covering positive and edge-case failure modes.\n3. Validate all inputs at the boundary.\n\nKeep code concise, readable, and well-typed.",
            ]);

            // Lessons for Module 3 (including Quiz)
            $l5 = Lesson::create([
                'module_id' => $module3->id,
                'title' => 'Performance & Production Deployment',
                'slug' => Str::slug($course->title) . '-deployment',
                'type' => 'video',
                'video_url' => $cDef['preview_video'],
                'duration_minutes' => 30,
                'sort_order' => 1,
                'is_preview' => false,
                'content' => "Final walkthrough on optimizing query counts, enabling asset caching, setting up SSL termination, and configuring continuous delivery pipelines.",
            ]);

            $lQuiz = Lesson::create([
                'module_id' => $module3->id,
                'title' => 'Comprehensive Knowledge Assessment',
                'slug' => Str::slug($course->title) . '-assessment',
                'type' => 'quiz',
                'duration_minutes' => 15,
                'sort_order' => 2,
                'is_preview' => false,
                'content' => 'Complete this interactive multiple-choice quiz to demonstrate your mastery and unlock your verified certificate.',
            ]);

            // Create Quiz for the quiz lesson
            $quiz = Quiz::create([
                'lesson_id' => $lQuiz->id,
                'title' => $course->title . ' Final Assessment',
                'description' => 'Test your understanding of the core concepts, implementation patterns, and security principles.',
                'passing_score' => 75,
                'time_limit_minutes' => 20,
            ]);

            // Question 1
            $q1 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'What is the primary architectural advantage of using dedicated Service classes or Actions?',
                'explanation' => 'Service classes encapsulate business domain logic away from HTTP transport layers (like Controllers), making code reusable and easier to unit-test.',
                'sort_order' => 1,
            ]);
            QuizOption::create(['question_id' => $q1->id, 'option_text' => 'It decouples domain business logic from HTTP controllers and allows easier testing.', 'is_correct' => true]);
            QuizOption::create(['question_id' => $q1->id, 'option_text' => 'It eliminates the need for database migrations.', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q1->id, 'option_text' => 'It converts all code automatically into JavaScript.', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q1->id, 'option_text' => 'It bypasses all authorization policies.', 'is_correct' => false]);

            // Question 2
            $q2 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'Why must quiz scoring and verification always be calculated on the backend server?',
                'explanation' => 'Client-side scoring is vulnerable to manipulation and tampering. Backend grading ensures integrity and secure verification.',
                'sort_order' => 2,
            ]);
            QuizOption::create(['question_id' => $q2->id, 'option_text' => 'Because client-side scoring can be tampered with or inspected in browser devtools.', 'is_correct' => true]);
            QuizOption::create(['question_id' => $q2->id, 'option_text' => 'Because JavaScript cannot calculate percentages.', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q2->id, 'option_text' => 'Because CSS prevents radio button validation.', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q2->id, 'option_text' => 'To make requests slower on mobile devices.', 'is_correct' => false]);

            // Question 3
            $q3 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'Which database technique is crucial to avoid N+1 query performance bottlenecks?',
                'explanation' => 'Eager loading (using `with()`) queries related records in bulk rather than executing separate SQL queries per row.',
                'sort_order' => 3,
            ]);
            QuizOption::create(['question_id' => $q3->id, 'option_text' => 'Eager Loading relationships with with()', 'is_correct' => true]);
            QuizOption::create(['question_id' => $q3->id, 'option_text' => 'Dropping primary key constraints', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q3->id, 'option_text' => 'Disabling foreign keys completely', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q3->id, 'option_text' => 'Storing everything in a single string column', 'is_correct' => false]);

            // Question 4
            $q4 = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => 'What is the purpose of Laravel Authorization Policies?',
                'explanation' => 'Policies organize authorization logic around a specific model or resource, ensuring users can only edit or view resources they have permission for.',
                'sort_order' => 4,
            ]);
            QuizOption::create(['question_id' => $q4->id, 'option_text' => 'Enforcing granular user permissions and resource access control on the server.', 'is_correct' => true]);
            QuizOption::create(['question_id' => $q4->id, 'option_text' => 'Generating CSS stylesheets.', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q4->id, 'option_text' => 'Minifying client-side TypeScript code.', 'is_correct' => false]);
            QuizOption::create(['question_id' => $q4->id, 'option_text' => 'Creating SQLite database backups.', 'is_correct' => false]);

            $createdCourses[] = $course;
        }

        // 6. Seed Enrollments, Progress, Reviews, and Certificates
        $reviewsFeedback = [
            5 => [
                'Incredible course! The explanations were crystal clear and the hands-on quizzes really solidified my understanding.',
                'Best course on this topic by far. The instructor goes straight to the point with zero fluff.',
                'Gave me the exact skills I needed for my current job project. Highly recommended!',
                'Super practical and beautifully explained. The curriculum progression was structured perfectly.',
            ],
            4 => [
                'Great course with comprehensive content. The exercises were challenging and enjoyable.',
                'Really solid foundation and excellent practical code examples. Learned a ton.',
                'Very thorough material. Enjoyed the quizzes and clear architectural breakdowns.',
            ],
        ];

        $certCounter = 100;

        foreach ($createdCourses as $cIndex => $course) {
            // Enroll primary demo student in the first 4 courses
            if ($cIndex < 4) {
                $status = ($cIndex === 0 || $cIndex === 2) ? 'completed' : 'active';
                $enrollment = Enrollment::create([
                    'user_id' => $primaryStudent->id,
                    'course_id' => $course->id,
                    'status' => $status,
                    'enrolled_at' => now()->subDays(14),
                    'completed_at' => $status === 'completed' ? now()->subDays(2) : null,
                ]);

                // Mark progress for lessons
                $allLessons = $course->lessons()->get();
                foreach ($allLessons as $lIndex => $lesson) {
                    $isComp = ($status === 'completed') || ($lIndex < 3);
                    LessonProgress::create([
                        'user_id' => $primaryStudent->id,
                        'course_id' => $course->id,
                        'lesson_id' => $lesson->id,
                        'is_completed' => $isComp,
                        'progress_seconds' => $isComp ? $lesson->duration_minutes * 60 : 0,
                        'completed_at' => $isComp ? now()->subDays(3) : null,
                    ]);
                }

                // If completed, issue certificate
                if ($status === 'completed') {
                    $certCounter++;
                    $certNum = "EDU-2026-000{$certCounter}";
                    Certificate::create([
                        'user_id' => $primaryStudent->id,
                        'course_id' => $course->id,
                        'enrollment_id' => $enrollment->id,
                        'certificate_number' => $certNum,
                        'issued_at' => now()->subDays(2),
                    ]);

                    Notification::create([
                        'user_id' => $primaryStudent->id,
                        'type' => 'certificate_issued',
                        'title' => 'Certificate Issued!',
                        'message' => "Congratulations! Your official certificate for '{$course->title}' is ready ({$certNum}).",
                        'read_at' => null,
                    ]);
                }

                // Add student review
                Review::create([
                    'course_id' => $course->id,
                    'user_id' => $primaryStudent->id,
                    'rating' => 5,
                    'comment' => 'Fantastic curriculum! Structured cleanly and easy to follow from start to finish.',
                ]);
            }

            // Enroll 5-10 other random students per course to generate rich metrics
            $enrolledStudents = collect($students)->slice(1)->random(rand(5, 10));
            foreach ($enrolledStudents as $sIdx => $st) {
                $isCompleted = ($sIdx % 3 === 0);
                $enr = Enrollment::create([
                    'user_id' => $st->id,
                    'course_id' => $course->id,
                    'status' => $isCompleted ? 'completed' : 'active',
                    'enrolled_at' => now()->subDays(rand(5, 45)),
                    'completed_at' => $isCompleted ? now()->subDays(rand(1, 4)) : null,
                ]);

                // Random student reviews
                if ($sIdx < 3) {
                    $star = rand(4, 5);
                    $comments = $reviewsFeedback[$star];
                    $comment = $comments[array_rand($comments)];

                    Review::create([
                        'course_id' => $course->id,
                        'user_id' => $st->id,
                        'rating' => $star,
                        'comment' => $comment,
                    ]);
                }

                if ($isCompleted) {
                    $certCounter++;
                    Certificate::create([
                        'user_id' => $st->id,
                        'course_id' => $course->id,
                        'enrollment_id' => $enr->id,
                        'certificate_number' => "EDU-2026-000{$certCounter}",
                        'issued_at' => now()->subDays(rand(1, 4)),
                    ]);
                }
            }
        }
    }
}
