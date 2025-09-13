<?php

namespace App\Jobs;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessChatMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(): void
    {
        // If there's no file, just create the text message directly.
        if (empty($this->data['temp_file_path'])) {
            if (!empty($this->data['message'])) {
                $this->createMessageAndDispatch(null);
            }
            return;
        }

        $tempFilePath = storage_path('app/' . $this->data['temp_file_path']);

        try {
            // 1. Send the file to VirusTotal for scanning
            $response = Http::withHeaders([
                'x-apikey' => config('services.virustotal.api_key'),
            ])
            ->attach('file', file_get_contents($tempFilePath), basename($tempFilePath))
            ->post('https://www.virustotal.com/api/v3/files');

            // 2. Throw an exception if the upload fails (e.g., bad API key)
            $response->throw();
            
            Log::info('VirusTotal scan initiated for file: ' . $tempFilePath);

            // 3. If the scan is initiated, move the file and create the message
            $this->moveFileAndCreateMessage();

        } catch (\Exception $e) {
            // 4. If the scan fails, log the error and delete the malicious file.
            Log::error('VirusTotal scan failed for file ' . $tempFilePath . ': ' . $e->getMessage());
            Storage::delete($this->data['temp_file_path']);
        }
    }

    private function moveFileAndCreateMessage(): void
    {
        $finalPath = 'chat_files/' . basename($this->data['temp_file_path']);
        Storage::disk('public')->move($this->data['temp_file_path'], $finalPath);
        $this->createMessageAndDispatch($finalPath);
    }

    private function createMessageAndDispatch(?string $filePath): void
    {
        $message = Message::create([
            'sender_id'   => $this->data['sender_id'],
            'receiver_id' => $this->data['receiver_id'],
            'message'     => $this->data['message'],
            'image_path'  => $filePath,
        ]);

        MessageSent::dispatch($message);
    }
}