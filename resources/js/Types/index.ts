export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string | null;
    bio?: string | null;
    role: 'admin' | 'instructor' | 'student';
    created_at?: string;
}

export interface Category {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
    image?: string | null;
    is_active: boolean;
    courses_count?: number;
}

export interface Course {
    id: number;
    instructor_id: number;
    category_id: number;
    title: string;
    slug: string;
    short_description?: string | null;
    description?: string | null;
    thumbnail?: string | null;
    preview_video?: string | null;
    level: 'beginner' | 'intermediate' | 'advanced';
    language: string;
    price: number | string;
    discount_price?: number | string | null;
    duration_minutes: number;
    status: 'draft' | 'pending' | 'published' | 'archived';
    published_at?: string | null;
    created_at?: string;
    updated_at?: string;
    instructor?: User;
    category?: Category;
    modules?: CourseModule[];
    lessons?: Lesson[];
    reviews?: Review[];
    reviews_count?: number;
    reviews_avg_rating?: number;
    enrollments_count?: number;
    is_enrolled?: boolean;
    progress?: CourseProgress;
}

export interface CourseModule {
    id: number;
    course_id: number;
    title: string;
    description?: string | null;
    sort_order: number;
    lessons?: Lesson[];
}

export interface Lesson {
    id: number;
    module_id: number;
    title: string;
    slug: string;
    type: 'video' | 'article' | 'quiz';
    video_url?: string | null;
    content?: string | null;
    duration_minutes: number;
    sort_order: number;
    is_preview: boolean;
    resources?: LessonResource[];
    quiz?: Quiz;
    module?: CourseModule;
    is_completed?: boolean;
}

export interface LessonResource {
    id: number;
    lesson_id: number;
    name: string;
    file_path: string;
    file_type?: string | null;
    file_size: number;
    formatted_file_size?: string;
}

export interface Enrollment {
    id: number;
    user_id: number;
    course_id: number;
    status: 'active' | 'completed' | 'cancelled';
    enrolled_at: string;
    completed_at?: string | null;
    course?: Course;
    user?: User;
    progress_percentage?: number;
    completed_lessons_count?: number;
    total_lessons_count?: number;
}

export interface CourseProgress {
    total_lessons: number;
    completed_lessons: number;
    progress_percentage: number;
    completed_lesson_ids: number[];
    is_completed: boolean;
}

export interface Quiz {
    id: number;
    lesson_id: number;
    title: string;
    description?: string | null;
    passing_score: number;
    time_limit_minutes?: number | null;
    questions?: QuizQuestion[];
    lesson?: Lesson;
}

export interface QuizQuestion {
    id: number;
    quiz_id: number;
    question: string;
    explanation?: string | null;
    sort_order: number;
    options: QuizOption[];
}

export interface QuizOption {
    id: number;
    question_id: number;
    option_text: string;
    is_correct?: boolean; // sanitized on student views
}

export interface QuizAttempt {
    id: number;
    quiz_id: number;
    user_id: number;
    score: number;
    passed: boolean;
    started_at: string;
    completed_at?: string | null;
    quiz?: Quiz;
    answers?: QuizAnswer[];
}

export interface QuizAnswer {
    id: number;
    attempt_id: number;
    question_id: number;
    option_id?: number | null;
    is_correct: boolean;
    question?: QuizQuestion;
    option?: QuizOption;
}

export interface Review {
    id: number;
    course_id: number;
    user_id: number;
    rating: number;
    comment?: string | null;
    created_at: string;
    user?: User;
    course?: Course;
}

export interface Certificate {
    id: number;
    user_id: number;
    course_id: number;
    enrollment_id: number;
    certificate_number: string;
    issued_at: string;
    user?: User;
    course?: Course;
}

export interface NotificationItem {
    id: number;
    user_id: number;
    type: string;
    title: string;
    message: string;
    read_at?: string | null;
    created_at: string;
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    first_page_url: string;
    from: number | null;
    last_page: number;
    last_page_url: string;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number | null;
    total: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

export interface SharedProps {
    appName: string;
    auth: {
        user: User | null;
        unreadNotificationsCount: number;
    };
    flash: {
        success?: string | null;
        error?: string | null;
        warning?: string | null;
        info?: string | null;
    };
}
