<?php

namespace App\Livewire\Pamphlet;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Service\TranscriptionService;
use App\Service\MetisClient;
use App\Models\Pamphlet;
use Illuminate\Support\Facades\Storage;
use Parsedown;

class AudioToPamphlet extends Component
{
    use WithFileUploads;

    public $audioFile;
    public $customPrompt = '';
    public $transcribedText = '';
    public $correctedHtml = '';

    public function transcribeAndGenerate()
    {
        $this->validate([
            'audioFile' => 'required|file|mimes:mp3,wav',
            'customPrompt' => 'nullable|string',
        ]);
    
        $filePath = $this->audioFile->store('audio_files');
    
        $transcriptionService = new TranscriptionService();
        $this->transcribedText = $transcriptionService->transcribe(Storage::path($filePath));
    
        if ($this->transcribedText) {
            $combinedText = $this->transcribedText;
            if (!empty($this->customPrompt)) {
                $combinedText .= "\n\n" . $this->customPrompt;
            }
    
            $client = MetisClient::getClient();
            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "من متنی دارم و می‌خواهم آن را به یک جزوه تبدیل کنم. جزوه باید دارای ساختار مشخص باشد و به بخش‌های مختلف تقسیم شود، شامل: مقدمه، بدنه اصلی با جزئیات کلیدی، و نتیجه‌گیری یا خلاصه. هر بخش باید با سرخط‌های واضح و جذاب مشخص شود و نکات مهم با استفاده از بولت‌پوینت یا قالب‌بندی مناسب برجسته شوند. محتوا باید شفاف، قابل فهم و برای طیف گسترده‌ای از مخاطبان مناسب باشد. لحن باید رسمی و حرفه‌ای باشد و از جملات کوتاه و پاراگراف‌های مختصر استفاده شود. طراحی باید ساده، شیک و خوانا باشد. لطفاً اطمینان حاصل کنید که متن تولید‌شده ساختارمند و قابل‌استفاده برای مقاصد عمومی است. همچنین، عنوان جزوه باید در اولین خط آمده باشد."
                    ],
                    ['role' => 'user', 'content' => $combinedText],
                ],
            ]);
    
            $generatedContent = $response['choices'][0]['message']['content'] ?? 'No content generated.';
            $this->correctedHtml = $this->markdownToHtml($generatedContent);
    
            $aiGeneratedTitle = $this->extractTitleFromMarkdown($generatedContent);
    
            if (!$aiGeneratedTitle) {
                $aiGeneratedTitle = 'جزوه بدون نام';
            }
    
            Pamphlet::create([
                'user_id' => auth()->id(),
                'html_content' => $this->correctedHtml,
                'title' => $aiGeneratedTitle,
            ]);
        } else {
            $this->correctedHtml = 'Transcription failed.';
        }
    }
    
    private function extractTitleFromMarkdown($markdownContent)
    {
        if (preg_match('/^# (.+)$/m', $markdownContent, $matches)) {
            return trim($matches[1]);
        }
    
        return null;
    }
    
    private function markdownToHtml($markdownText)
    {
        $parsedown = new Parsedown();
        return $parsedown->text($markdownText);
    }

    public function render()
    {
        return view('livewire.pamphlet.audio-to-pamphlet');
    }
}
