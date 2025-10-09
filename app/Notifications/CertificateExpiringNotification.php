<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage; // optional
use App\Models\UserCertificate;

class CertificateExpiringNotification extends Notification
{
    use Queueable;

    protected $certificate;

    public function __construct(UserCertificate $certificate)
    {
        $this->certificate = $certificate;
    }

    // Specify the channels
    public function via($notifiable)
    {
        return ['database']; // this ensures it goes into the notifications table
    }

    // What is saved in the database
    public function toDatabase($notifiable)
    {
        return [
            'certificate_id' => $this->certificate->id, // track which certificate
            'title' => 'Certificate Expiring Soon',
            'message' => 'Your certificate for <strong>'.$this->certificate->course->name.'</strong> will expire on <strong>'.\Carbon\Carbon::parse($this->certificate->expiration_date)->format('F d, Y').'</strong>.',
            'url' => null,
        ];
    }


    // Optional: if you also want email, add toMail() here
}
