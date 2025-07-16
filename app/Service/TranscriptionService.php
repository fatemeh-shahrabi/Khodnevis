<?php

// namespace App\Service;

// class TranscriptionService
// {
//     public function transcribe($audioFilePath)
//     {
//         $pythonPath = "C:\\Users\\shahr\\AppData\\Local\\Programs\\Python\\Python39\\python.exe";
//         $scriptPath = base_path("whisper_transcribe.py");

//         $command = "\"$pythonPath\" \"$scriptPath\" \"$audioFilePath\"";

//         $result = shell_exec($command . " 2>&1");

//         return $result ?: 'An error occurred during transcription.';
//     }
// }


namespace App\Service;

use Illuminate\Support\Facades\Http;

class TranscriptionService
{
    public function transcribe($audioFilePath, $prompt = null)
    {
        $apiKey = env('OPENAI_API_KEY');
        $endpoint = 'https://api.metisai.ir/openai/v1/audio/transcriptions';

        if (!file_exists($audioFilePath)) {
            return 'Audio file not found.';
        }

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
        ])->attach(
            'file', file_get_contents($audioFilePath), basename($audioFilePath)
        )->post($endpoint, [
            'model' => 'whisper-1',
            'prompt' => $prompt,
        ]);

        if ($response->successful()) {
            $text = $response->json()['text'] ?? '';
            return $this->removeRepetitions($text);
        }

        return 'Transcription failed.';
    }

    private function removeRepetitions($text)
    {
        $lines = array_unique(explode("\n", $text));
        return implode("\n", array_filter($lines));
    }
}
