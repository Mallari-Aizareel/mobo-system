<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobRecommendation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateResumeMatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resumes:update-matches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Poll Sharp API and update pending resume matches';

    /**
     * Execute the console command.
     */
     public function handle()
    {
        $pending = JobRecommendation::where('match_score', 0)
            ->whereNotNull('status_url')
            ->get();

        foreach ($pending as $rec) {
            $statusResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.sharpapi.key'),
            ])->get($rec->status_url);

            $statusData = $statusResponse->json();

            Log::info('Status response', $statusData);

            if (in_array($statusData['data']['attributes']['status'] ?? '', ['success', 'completed']))  {
                $resultJson = $statusData['data']['attributes']['result'] ?? '{}';
                $resultData = json_decode($resultJson, true);

                if (is_string($resultData)) {
                    $resultData = json_decode($resultData, true);
                }

                $overallMatch = $resultData['match_scores']['overall_match'] ?? 0;

                $rec->update([
                    'match_score' => $overallMatch,
                    'details' => json_encode($resultData['match_scores'] ?? [])
                ]);


                Log::info('Updated recommendation', [
                    'job_id' => $rec->job_post_id,
                    'user_id' => $rec->user_id,
                    'resume_path' => $rec->resume_path,
                    'match_score' => $overallMatch,
                ]);
            }
        }

        $this->info('Resume matches updated.');
    }
}
