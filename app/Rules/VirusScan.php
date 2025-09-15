<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VirusScan implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $value is the uploaded file object we’re validating
        try {
            $response = Http::withHeaders([
                'x-apikey' => config('services.virustotal.api_key'),
            ])
            ->attach('file', file_get_contents($value->getRealPath()), $value->getClientOriginalName())
            ->post('https://www.virustotal.com/api/v3/files');
            
            // If VirusTotal responds with an error (4xx/5xx), surface it immediately
            $response->throw();

            Log::info('VirusTotal scan initiated for file: ' . $value->getClientOriginalName());

            // Optional: Inspect response for immediate “malicious” flags.
            // For now, a successful upload is considered a pass.
            // $stats = $response->json('data.attributes.last_analysis_stats');
            // if (isset($stats['malicious']) && $stats['malicious'] > 0) {
            //     $fail('A virus was detected in the uploaded file.');
            // }

        } catch (\Exception $e) {
            Log::error('VirusTotal scan failed: ' . $e->getMessage());
            // A friendly message shown to the user when scanning fails
            $fail('The uploaded file could not be scanned and was rejected.');
        }
    }
}
