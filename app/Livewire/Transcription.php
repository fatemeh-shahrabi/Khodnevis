<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Service\TranscriptionService;

class Transcription extends Component
{
    use WithFileUploads;

    public $audio_file;
    public $transcribed_text;

    protected $rules = [
        'audio_file' => 'required|file|mimes:mp3,wav|max:10240',
    ];

    public function transcribe(TranscriptionService $transcriptionService)
    {
        $this->validate();

        $prompt = "رونویسی دقیق و واضح فارسی با رعایت نکات زیر: "
        . "۱. حفظ کامل کلمات فارسی، انگلیسی و عربی؛ جلوگیری از ترجمه یا تغییر کلمات اصلی. "
        . "۲. کلمات انگلیسی با حروف اصلی انگلیسی نوشته شود، مانند 'MVP' و 'Product'. "
        . "۳. اعداد به صورت عددی نوشته شود، مانند ۱۲۳۴. "
        . "۴. علائم نگارشی نظیر نقطه، ویرگول، علامت سؤال، و غیره به درستی استفاده شود. "
        . "۵. کلمات عربی به شکل صحیح نوشته شود، مانند قرآن، سلام، محمد، و الله. "
        . "۶. از غلط‌های املایی اجتناب شود؛ تمام کلمات با دقت بررسی و ضبط گردد. "
        . "۷. جملات با رعایت قواعد دستوری و ساختار معنایی باشد. "
        . "۸. عبارات نامفهوم با استفاده از علامت [...] نشان داده شود. "
        . "۹. در تلفظ اسامی خاص مانند افراد، مکان‌ها و اصطلاحات تخصصی، دقت کافی داشته باشید. ";

        $filePath = $this->audio_file->store('uploads', 'public');
        $fullFilePath = storage_path("app/public/{$filePath}");

        $this->transcribed_text = $transcriptionService->transcribe($fullFilePath, $prompt);
    }

    public function render()
    {
        return view('livewire.transcription');
    }
}
