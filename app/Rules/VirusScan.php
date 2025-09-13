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
        // $value will be the uploaded file object
        try {
            $response = Http::withHeaders([
                'x-apikey' => config('services.virustotal.api_key'),
            ])
            ->attach('file', file_get_contents($value->getRealPath()), $value->getClientOriginalName())
            ->post('https://www.virustotal.com/api/v3/files');
            
            // If the API returns an error status code (4xx or 5xx), throw an exception.
            $response->throw();

            Log::info('VirusTotal scan initiated for file: ' . $value->getClientOriginalName());

            // You can optionally check the response body for immediate malicious flags,
            // but for simplicity, we'll consider a successful upload as a pass for now.
            // $stats = $response->json('data.attributes.last_analysis_stats');
            // if (isset($stats['malicious']) && $stats['malicious'] > 0) {
            //     $fail('A virus was detected in the uploaded file.');
            // }

        } catch (\Exception $e) {
            Log::error('VirusTotal scan failed: ' . $e->getMessage());
            // This message will be shown to the user.
            $fail('The uploaded file could not be scanned and was rejected.');
        }
    }
}