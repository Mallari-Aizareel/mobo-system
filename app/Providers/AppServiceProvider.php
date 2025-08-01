<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       View::composer('*', function ($view) {
        $menu = [];

        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role_id == 1) { // Admin
                $menu = [
                    ['header' => 'main_navigation'],
        [
            'text' => 'Dashboard',
            'url'  => 'home',
            'icon' => 'fas fa-tachometer-alt',
        ],
        [
            'text' => 'List of Graduates',
            'url'  => 'admin/tesda-graduates',
            'icon' => 'fas fa-user-graduate',
        ],
        [
            'text' => 'Enrolled Trainees',
            'url'  => 'admin/manage-enrolled-trainees',
            'icon' => 'fas fa-users',
        ],
        [
            'text' => 'Agencies (list)',
            'url'  => 'admin/agencies',
            'icon' => 'fas fa-building',
        ],
        [
            'text' => 'Inbox',
            'url'  => 'admin/dashboard',
            'icon' => 'fas fa-inbox',
        ],
        [
            'text' => 'Create Class',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-plus-circle',
        ],
        [
            'text' => 'Classes',
            'url'  => 'admin/dashboard',
            'icon' => 'fas fa-chalkboard-teacher',
        ],
                [
            'text' => 'Courses',
            'url'  => 'admin/courses',
            'icon' => 'fas fa-chalkboard-teacher',
        ],
        [
            'text' => 'List of Training Centers',
            'url'  => 'admin/training-centers',
            'icon' => 'fas fa-school',
        ],
        [
            'text' => 'Drafted',
            'url'  => 'admin/drafted-trainees',
            'icon' => 'fas fa-file-alt',
        ],
                ];
            } elseif ($user->role_id == 2) { // TESDA
               $menu = [
                ['text' => 'Home', 'url' => 'tesda/home', 'icon' => 'fas fa-home'],
                ['text' => 'Dashboard', 'url' => 'tesda/dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ['text' => 'Notifications', 'url' => 'tesda/notifications', 'icon' => 'fas fa-bell'],
                ['text' => 'Inbox', 'url' => 'tesda/inbox', 'icon' => 'fas fa-inbox'],
                ['text' => 'Enroll Courses', 'url' => 'tesda/enroll-courses', 'icon' => 'fas fa-book-open'],
                ['text' => 'FAQs', 'url' => 'tesda/faqs', 'icon' => 'fas fa-question-circle'],
            ];
            } elseif ($user->role_id == 3) { // Agency
                $menu = [
                    ['text' => 'Home', 'url' => 'agency/home', 'icon' => 'fas fa-home'],
                    ['text' => 'Dashboard', 'url' => 'agency/dashboard', 'icon' => 'fas fa-tachometer-alt'],
                ];
            }
        }
        config(['adminlte.menu' => $menu]);
    });
    }
}
