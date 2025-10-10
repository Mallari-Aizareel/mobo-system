<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCertificate;
use Carbon\Carbon;

class UpdateExpiredCertificates extends Command
{
    protected $signature = 'certificates:update-expired';
    protected $description = 'Update certificates status to expired if past expiration_date';

    public function handle()
    {
        $today = Carbon::today();

        $expiredCertificates = UserCertificate::where('expiration_date', '<', $today)
            ->where('status', 'active') 
            ->get();

        foreach ($expiredCertificates as $certificate) {
            $certificate->update([
                
                'status' => 'expired',
                'remarks' => 'expired',
            ]);

            $this->info("Certificate ID {$certificate->id} marked as expired.");
        }

        $this->info('Expired certificates updated successfully.');
    }
}
