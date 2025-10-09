<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCertificate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CertificateExpiringNotification;

class NotifyExpiringCertificates extends Command
{
    protected $signature = 'notify:expiring-certificates';
    protected $description = 'Notify users if their certificate is expiring in 2 weeks';

    public function handle()
    {
        $now = Carbon::now()->startOfDay();
        $twoWeeks = Carbon::now()->addWeeks(2)->endOfDay();

        $certificates = UserCertificate::with('user', 'course')
            ->whereBetween('expiration_date', [$now, $twoWeeks])
            ->get();

        foreach ($certificates as $cert) {
            $alreadyNotified = \DB::table('notifications')
                ->where('notifiable_id', $cert->user_id)
                ->where('notifiable_type', get_class($cert->user))
                ->where('type', \App\Notifications\CertificateExpiringNotification::class)
                ->whereJsonContains('data->certificate_id', $cert->id)
                ->exists();

            if (!$alreadyNotified) {
                $cert->user->notify(new CertificateExpiringNotification($cert));
            }
        }


        $this->info('Notifications sent for certificates expiring in 2 weeks.');
    }
}
