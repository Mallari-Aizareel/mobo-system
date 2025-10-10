<?php

namespace App\Http\Controllers\Tesda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobRecommendation;
use App\Models\JobPost;
use App\Models\EnrolledTrainee;
use App\Models\UserCertificate;
use Carbon\Carbon;


class TesdaNotificationController extends Controller
{
public function index(Request $request)
{
    $userId = auth()->id();
    $filter = $request->query('filter');

    // AI recommendations
    $recommendations = JobRecommendation::with('jobPost.agency')
        ->where('user_id', $userId)
        ->whereNotNull('resume_path')      
        ->whereNotNull('match_score')             
        ->where('match_score', '>=', 60)   
        ->when($filter, function($q) use ($filter) {
            if ($filter === 'new') {
                $q->whereDate('created_at', now());
            } elseif ($filter === 'yesterday') {
                $q->whereDate('created_at', now()->subDay());
            } elseif ($filter === '1_week') {
                $q->where('created_at', '>=', now()->subWeek());
            } elseif ($filter === '2_weeks') {
                $q->where('created_at', '>=', now()->subWeeks(2));
            } elseif ($filter === '1_month') {
                $q->where('created_at', '>=', now()->subMonth());
            }
        })
        ->get()
        ->map(function($rec) {
            return [
                'type' => 'recommendation',
                'icon' => 'fas fa-robot text-info',
                'text' => 'Recommended you for <strong>'.$rec->jobPost->job_position.'</strong> from '.$rec->jobPost->agency->name,
                'created_at' => $rec->created_at,
            ];
        });

    // Agency posts
    $jobPosts = JobPost::with('agency')
        ->when($filter, function($q) use ($filter) {
            if ($filter === 'new') {
                $q->whereDate('created_at', now());
            } elseif ($filter === 'yesterday') {
                $q->whereDate('created_at', now()->subDay());
            } elseif ($filter === '1_week') {
                $q->where('created_at', '>=', now()->subWeek());
            } elseif ($filter === '2_weeks') {
                $q->where('created_at', '>=', now()->subWeeks(2));
            } elseif ($filter === '1_month') {
                $q->where('created_at', '>=', now()->subMonth());
            }
        })
        ->get()
        ->map(function($post) {
            return [
                'type' => 'new_post',
                'icon' => 'fas fa-bell text-primary',
                'text' => 'New post from <strong>'.$post->agency->name.'</strong>: '.$post->job_position,
                'created_at' => $post->created_at,
            ];
        });

    // Dropped out notifications
    $dropped = EnrolledTrainee::with('course') // eager load course
    ->where('user_id', $userId)
    ->where('status_id', 3) // dropped
    ->when($filter, function($q) use ($filter) {
        if ($filter === 'new') {
            $q->whereDate('updated_at', now());
        } elseif ($filter === 'yesterday') {
            $q->whereDate('updated_at', now()->subDay());
        } elseif ($filter === '1_week') {
            $q->where('updated_at', '>=', now()->subWeek());
        } elseif ($filter === '2_weeks') {
            $q->where('updated_at', '>=', now()->subWeeks(2));
        } elseif ($filter === '1_month') {
            $q->where('updated_at', '>=', now()->subMonth());
        }
    })
    ->get()
    ->map(function($drop) {
        return [
            'type' => 'dropped',
            'icon' => 'fas fa-user-slash text-danger',
            'text' => 'You have been dropped from the course <strong>'.$drop->course->name.'</strong>. Reason: <strong>'.$drop->reason.'</strong>',
            'created_at' => $drop->updated_at,
        ];
    });

    $user = auth()->user();

    $expiringCertificates = $user->notifications()
        ->where('type', 'App\Notifications\CertificateExpiringNotification')
        ->when($filter, function($q) use ($filter) {
            if ($filter === 'new') {
                $q->whereDate('created_at', now());
            } elseif ($filter === 'yesterday') {
                $q->whereDate('created_at', now()->subDay());
            } elseif ($filter === '1_week') {
                $q->where('created_at', '>=', now()->subWeek());
            } elseif ($filter === '2_weeks') {
                $q->where('created_at', '>=', now()->subWeeks(2));
            } elseif ($filter === '1_month') {
                $q->where('created_at', '>=', now()->subMonth());
            }
        })
        ->orderByDesc('created_at')
        ->get()
        ->map(function($notif) {
            return [
                'type' => 'certificate_expiring',
                'icon' => 'fas fa-exclamation-triangle text-warning',
                'text' => $notif->data['message'], // <-- your stored message
                'created_at' => $notif->created_at,
            ];
        });

        $expiredCerts = UserCertificate::with('user', 'course')
        ->where('status', 'expired')
        ->when($filter, function($q) use ($filter) {
            if ($filter === 'new') {
                $q->whereDate('updated_at', now());
            } elseif ($filter === 'yesterday') {
                $q->whereDate('updated_at', now()->subDay());
            } elseif ($filter === '1_week') {
                $q->where('updated_at', '>=', now()->subWeek());
            } elseif ($filter === '2_weeks') {
                $q->where('updated_at', '>=', now()->subWeeks(2));
            } elseif ($filter === '1_month') {
                $q->where('updated_at', '>=', now()->subMonth());
            }
        })
        ->get()
        ->map(function($cert) {
            return [
                'type' => 'expired_certificate',
                'icon' => 'fas fa-certificate text-danger',
               'text' => 'Your certificate for <strong>' . ($cert->course->name ?? 'a course') . '</strong> has <strong style="color:red">expired</strong>.',
                'created_at' => $cert->updated_at,
            ];
        });





    // Merge all notifications
    $notifications = $recommendations->merge($jobPosts)->merge($dropped)->merge($expiredCerts)->merge($expiringCertificates)->sortByDesc('created_at');

    return view('tesda.notifications', compact('notifications', 'filter'));
}
}
