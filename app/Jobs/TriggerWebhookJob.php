<?php

namespace App\Jobs;

use App\Models\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TriggerWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $log;

    /**
     * Create a new job instance.
     *
     * @param Log $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $storedLink = $this->log->stored_link;
        $oldValues = $this->log->old_values;
        $newValues = $this->log->new_values;

        // Prepare data for POST request
        $postData = [
            'old_values' => $oldValues,
            'new_values' => $newValues,
        ];

        // Make cURL request
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $storedLink);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_exec($ch);

        if (curl_errno($ch)) {
            curl_error($ch);
            curl_close($ch);
            return;
        }

        curl_close($ch);
    }
}
