# EduHub — Production-Grade Learning Management System (LMS)

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org/)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-v2-9553E9?style=for-the-badge&logo=inertia&logoColor=white)](https://inertiajs.com)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=for-the-badge&logo=typescript&logoColor=white)](https://www.typescriptlang.org/)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)](LICENSE)

> **"Learn. Practice. Grow."** — A modern, full-stack, enterprise-grade online learning platform built with Laravel 11, Vue 3 (Composition API + TypeScript), Inertia.js, and Tailwind CSS. Designed with rich micro-interactions, dark/light mode, role-based authorization, secure video/article player, server-side quiz engine, verifiable digital certificates, multi-step course studio, and comprehensive analytics.

---

## 📸 Application Preview & Screenshots

### 🌟 1. Landing Page & Course Discovery
<img width="1920" height="8690" alt="landingpage" src="https://github.com/user-attachments/assets/3590cad9-41f2-4587-b1cf-bf0f21a1aa84" />

### 📚 2. Course Catalog & Smart Filtering
<img width="1920" height="3334" alt="explore" src="https://github.com/user-attachments/assets/6748561d-041b-4eb7-bce5-e7d34cf9257a" />

### 📖 3. Interactive Course Detail & Syllabus Preview
<img width="1920" height="4250" alt="course" src="https://github.com/user-attachments/assets/4d88518b-5be6-40e1-ae86-4bc71fb79f88" />

### 🎓 4. Student Learning Dashboard & My Courses
| Student Learning Dashboard | Enrolled & Completed Courses |
| :---: | :---: |
| <img width="1920" height="4596" alt="dashboardstudent" src="https://github.com/user-attachments/assets/9d22cea8-cf2e-4c43-8451-d4c5b6c9788a" />| <img width="1920" height="1149" alt="inside" src="https://github.com/user-attachments/assets/e29c28b4-48cf-4a61-8e4c-bad26f4328ce" /> |

### 🏆 5. Verifiable Digital Certificate of Completion
<img width="1920" height="1867" alt="contohsertif" src="https://github.com/user-attachments/assets/fd3dd122-d9e3-481c-81ec-3d00ce4a2e90" />

### 📊 6. Instructor Studio & Admin Governance Portals
| Instructor Management Studio | Super Admin Governance Portal |
| :---: | :---: |
| <img width="1920" height="1435" alt="Instructor" src="https://github.com/user-attachments/assets/455239ae-13c6-4158-bffe-118f25d9a75a" />
 | <img width="1920" height="1505" alt="superadmin" src="https://github.com/user-attachments/assets/db9151c7-cb04-43b9-b5d3-5579a86bb06a" />
 |

---

## 🌟 Key Highlights & Features

### 🎓 1. Public & Student Experience
- **Interactive Landing & Catalog**: Dynamic hero with live stats, search bar with debounce, multi-facet filtering (categories, levels, price, rating), and instant sorting.
- **Rich Course Detail Page**: Video preview teaser, comprehensive syllabus accordion, learning objectives, requirements, instructor bios, and verified student reviews.
- **Distraction-Free Learning Player**:
  - Full-screen modern video embed & formatted markdown article reader.
  - Interactive collapsible curriculum sidebar with lesson status indicators.
  - Instant progress synchronization & auto-completion triggers.
  - Downloadable lesson resources and attachments.
- **Secure Quiz Assessment Engine**:
  - Timed quizzes with multiple-choice, true/false, and multiple-select questions.
  - Server-evaluated grading, passing score thresholds, and instant attempt review.
- **Cryptographically Verifiable Digital Certificates**:
  - Auto-generated upon 100% course completion with unique format: `EDU-2026-XXXXXX`.
  - Public verification URL (`/verify/{certificateNumber}`) accessible by employers and institutions.
  - Print & PDF download-friendly layout.
- **Student Dashboard**: Live progress tracking, resume-learning quick shortcuts, enrolled courses grid, review submission modals, and profile management.

### 👨‍🏫 2. Instructor Studio
- **Multi-Step Course Builder**:
  - **Step 1 — Basic Info**: Title, subtitle, slug generator, category, difficulty level, pricing (free/paid).
  - **Step 2 — Curriculum**: Drag/reorder modules, add lessons (Video URL, Article content, duration, free preview toggle), upload resources.
  - **Step 3 — Quizzes**: Create module-level or final assessments, add questions, set answer keys and passing scores.
  - **Step 4 — Media & Details**: Thumbnail upload/URL, promo video URL, requirements, learning outcomes.
  - **Step 5 — Preview & Publish**: Course submission for administrative moderation with instant preview.
- **Instructor Dashboard & Analytics**: Total earnings, student enrollments, course ratings, active students, and enrollment charts.
- **Student Management**: Real-time progress monitoring per student for all owned courses.

### 🛡️ 3. Admin Portal & Governance
- **Platform Analytics**: Total revenue, platform-wide enrollments, course completion rates, active user trends, and category distribution.
- **Course Moderation Queue**: Review submitted courses, inspect lessons, approve or reject with direct feedback messages.
- **User Management**: Filter and search users, manage roles (`student`, `instructor`, `admin`), toggle account status, with built-in self-demotion protection.
- **Category & Taxonomy CRUD**: Manage parent/child categories with icons, colors, and order.
- **Review Moderation**: Audit student feedback and delete spam or inappropriate reviews.

### 🎨 4. Design System & Aesthetics
- **Dark / Light Mode**: Seamless theme switcher with persistent `localStorage` and system preference fallback.
- **22+ Reusable Vue 3 Components**: Buttons, Inputs, Modals, Dropdowns, Cards, Tables, Tabs, Ratings, Progress Bars, Toasts, Skeletons, and Confirm Dialogs.
- **Accessibility & UX**: Smooth transitions, accessible keyboard navigation, responsive layouts (mobile, tablet, desktop), and zero generic placeholder text.

---

## 🏗️ Architecture & Tech Stack

```
EduHub Full-Stack Architecture
├── Backend (Laravel 11)
│   ├── Eloquent ORM & Relationships
│   ├── Domain Services (Enrollment, CourseProgress, Quiz, Certificate, Notification)
│   ├── Strict Policy Authorization (CoursePolicy, LessonPolicy, QuizPolicy, etc.)
│   └── Form Request Validation
├── Frontend (Vue 3 + TypeScript)
│   ├── Inertia.js Server-Driven SPA Architecture
│   ├── Vue 3 Composition API (<script setup lang="ts">)
│   ├── Tailwind CSS v3 + Modern Glassmorphism & Micro-animations
│   └── Lucide Vue Next Icons
└── Database (SQLite / MySQL / PostgreSQL)
    └── Fully normalized schema with foreign keys, indexes, and cascades
```

---

## 🚀 Getting Started

### Prerequisites
- **PHP 8.2+** with `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json` extensions.
- **Composer 2.x**
- **Node.js 18+** & **npm**

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/MutiaNandhika/eduhub-lms.git
   cd eduhub-lms
   ```

2. **Install Backend Dependencies**
   ```bash
   composer install
   ```

3. **Install Frontend Dependencies**
   ```bash
   npm install
   ```

4. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Migration & Seeder**
   ```bash
   touch database/database.sqlite
   php artisan migrate:fresh --seed
   ```

6. **Build Frontend Assets**
   ```bash
   npm run build
   ```

7. **Start Development Servers**
   ```bash
   # Terminal 1: Laravel Backend
   php artisan serve

   # Terminal 2: Vite Dev Server (Hot Module Replacement)
   npm run dev
   ```

   Visit **`http://localhost:8000`** in your browser!

---

## 🔑 Demo Credentials

EduHub comes pre-seeded with realistic courses, lessons, quizzes, reviews, and test accounts:

| Role | Email | Password | Access Highlights |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@eduhub.test` | `password` | Course moderation, User management, Category CRUD, Platform analytics |
| **Instructor** | `instructor@eduhub.test` | `password` | Course studio, Curriculum builder, Revenue stats, Student tracking |
| **Student** | `student@eduhub.test` | `password` | Course enrollment, Learning player, Quizzes, Verified certificates |

*💡 Tip: The Login page includes convenient **1-Click Demo Login** buttons for instant role switching!*

---

## 🧪 Testing Suite

EduHub includes a comprehensive automated test suite covering authentication, enrollment logic, progress tracking, quiz security, certificates, and role policies.

```bash
# Run all automated tests
php artisan test

# Run with detailed testdox output
php artisan test --testdox
```

### Test Coverage Highlights:
- ✅ Authentication & registration workflows (Student & Instructor)
- ✅ Policy boundaries (Students cannot access Instructor/Admin areas; Instructors cannot edit other instructors' courses)
- ✅ Safe enrollment rules (No duplicate enrollments; Instructors cannot enroll in own course)
- ✅ Server-side lesson progress calculation and auto-certificate generation upon 100% completion
- ✅ Server-side quiz scoring & answer secrecy
- ✅ Public certificate verification API & page rendering

---

## 📁 Project Structure

```
eduhub/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Public, Student, Instructor, Admin Controllers
│   │   └── Middleware/           # Inertia shared data & Role verification
│   ├── Models/                   # User, Course, Module, Lesson, Quiz, Certificate, etc.
│   ├── Policies/                 # Strict Laravel authorization policies
│   └── Services/                 # Clean domain business logic
├── database/
│   ├── migrations/               # Normalized relational schema
│   └── seeders/                  # Production-grade seed data (categories, courses, reviews)
├── resources/
│   ├── js/
│   │   ├── Components/
│   │   │   ├── Domain/           # CourseCard, CurriculumAccordion, ReviewModal, etc.
│   │   │   └── UI/               # Reusable Button, Input, Modal, Table, Rating, etc.
│   │   ├── Composables/          # useTheme, useToast
│   │   ├── Layouts/              # AppLayout, GuestLayout, InstructorLayout, AdminLayout, LearningLayout
│   │   ├── Pages/                # Auth, Public, Student, Instructor, Admin pages
│   │   ├── types/                # Strict TypeScript interfaces & definitions
│   │   └── app.ts                # Inertia app initialization
│   └── views/
│       └── app.blade.php         # Root HTML layout with font imports
└── routes/
    ├── web.php                   # All student, instructor, admin, and public routes
    └── auth.php                  # Authentication routes
```

---

## 🔒 Security & Best Practices

- **Server-Side Authorization**: Every state change (updating course, approving curriculum, grading quiz, issuing certificate) is protected by Laravel Policies and Form Requests.
- **Quiz Integrity**: Correct answers are filtered out before sending quiz questions to the client. Scoring is strictly calculated on the server.
- **Inertia Protocol**: Fast single-page application feel with zero state desynchronization and CSRF protection on every request.
- **SQL & XSS Prevention**: Built-in Eloquent query parameterization and Vue template auto-escaping.

---

## 📄 License

The EduHub LMS project is open-sourced software licensed under the **[MIT license](LICENSE)**.
