<?php

namespace App\Livewire\Pamphlet;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Service\TranscriptionService;
use App\Service\MetisClient;
use App\Models\Pamphlet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Parsedown;

/**
 * Livewire component for creating and managing pamphlets from audio files.
 */
class SinglePagePamphlet extends Component
{
    use WithFileUploads;

    public $audioFile;
    public $customPrompt = '';
    public $transcribedText = '';
    public $correctedHtml = '';
    public $pamphlets;
    public $newPamphlet = null;
    public $selectedPamphlet = null;
    public $showForm = true;
    public $errorMessage = '';
    public $isProcessing = false;
    public $processingStep = '';

    /**
     * Initialize the component and load user pamphlets.
     */
    public function mount()
    {
        $this->loadPamphlets();
    }

    /**
     * Load pamphlets for the authenticated user.
     */
    public function loadPamphlets()
    {
        $this->pamphlets = Pamphlet::where('user_id', auth()->id())
            ->select('id', 'title', 'html_content', 'created_at', 'audio_path')
            ->latest()
            ->get();
        $this->selectedPamphlet = null;
    }

    /**
     * Transcribe audio file and generate structured pamphlet.
     */
    public function transcribeAndGenerate()
    {
        $this->reset(['errorMessage', 'transcribedText', 'correctedHtml']);
        $this->validate([
            'audioFile' => 'required|file|mimes:mp3,wav,aac,ogg,flac|max:25600',
            'customPrompt' => 'nullable|string|max:1000',
        ]);

        try {
            $this->isProcessing = true;
            $this->processingStep = 'در حال بارگذاری فایل صوتی...';

            $filePath = $this->audioFile->store('audio_files');
            $absoluteFilePath = Storage::path($filePath);

            $this->processingStep = 'در حال رونویسی صوت...';
            $transcriptionService = new TranscriptionService();
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

            $this->transcribedText = $transcriptionService->transcribe($absoluteFilePath, $prompt);

            if (str_contains($this->transcribedText, 'failed')) {
                throw new \Exception('خطا در رونویسی صوت. لطفاً دوباره تلاش کنید.');
            }

            $this->processingStep = 'در حال تولید جزوه...';
            $combinedText = $this->transcribedText;
            if (!empty($this->customPrompt)) {
                $combinedText .= "\n\nدستورالعمل کاربر: " . $this->customPrompt;
            }

            $client = MetisClient::getClient();
            $response = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "من یک متن دارم که می‌خواهم آن را به یک جزوه ساختاریافته تبدیل کنم. این جزوه باید شامل سه بخش اصلی باشد:

                        1. **مقدمه**: به طور خلاصه زمینه را فراهم کنید، هدف را توضیح دهید و مواردی که خوانندگان می‌توانند انتظار داشته باشند را بیان کنید.
                        2. **متن اصلی**: جزئیات اصلی را به صورت منطقی و با زیرمجموعه‌های واضح ارائه دهید، از نقاط یا لیست‌های شماره‌گذاری شده برای تأکید بر نکات مهم استفاده کنید.
                        3. **نتیجه‌گیری/خلاصه**: نکات اصلی را خلاصه کرده و خواننده را با یک نکته روشن ترک کنید.
                        
                        هر بخش باید با یک عنوان واضح و جذاب شروع شود و نکات مهم با نقاط یا فرمت مناسب برای تأکید بهتر برجسته شوند. محتوا باید به وضوح و به طور مختصر نوشته شود تا برای مخاطب گسترده‌ای قابل فهم باشد.
                        
                        لحن باید رسمی و حرفه‌ای باقی بماند، با جملات کوتاه و پاراگراف‌های مختصر. طراحی باید ساده و در عین حال زیبا باشد، به نحوی که اولویت با خوانایی باشد. از سبک‌ها و اندازه‌های فونت متوالی برای عناوین، زیرعناوین و متن اصلی استفاده کنید.
                        
                        اطمینان حاصل کنید که عنوان در اولین خط جزوه به صورت برجسته قرار گیرد و موضوع را به وضوح منعکس کند. پس از عنوان، بلافاصله متن جزوه را شروع کنید.
                        
                        اطمینان حاصل کنید که چیزی به آن اضافه نمی‌کنید و فقط بر اساس متن ارائه‌شده پایه‌گذاری کنید.
                        
                        مطمئن شوید که نتیجه نهایی به فارسی ارائه می‌دهید."
                    ],
                    ['role' => 'user', 'content' => $combinedText],
                ],
            ]);

            $generatedContent = $response['choices'][0]['message']['content'] ?? '';
            if (empty($generatedContent)) {
                throw new \Exception('خطا در تولید محتوا. لطفاً دوباره تلاش کنید.');
            }

            $parsedown = new Parsedown();
            $this->correctedHtml = $parsedown->text($generatedContent);

            $aiGeneratedTitle = $this->extractTitleFromMarkdown($generatedContent) ?? 'جزوه بدون نام';

            $pamphlet = Pamphlet::create([
                'user_id' => auth()->id(),
                'html_content' => $this->correctedHtml,
                'title' => $aiGeneratedTitle,
                'audio_path' => $filePath,
            ]);

            $this->loadPamphlets();
            $this->selectedPamphlet = $pamphlet;
            $this->showForm = false;
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        } finally {
            $this->isProcessing = false;
            $this->processingStep = '';
        }
    }

    /**
     * Extract title from markdown content.
     *
     * @param string $markdownContent
     * @return string|null
     */
    private function extractTitleFromMarkdown(string $markdownContent): ?string
    {
        if (preg_match('/^#\s(.+)/m', $markdownContent, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * Display a specific pamphlet.
     *
     * @param int $pamphletId
     */
    public function showPamphlet($pamphletId)
    {
        $this->selectedPamphlet = Pamphlet::findOrFail($pamphletId);
        $this->showForm = false;
    }

    /**
     * Return to the form view.
     */
    public function goBackToForm()
    {
        $this->showForm = true;
        $this->selectedPamphlet = null;
        $this->reset(['errorMessage', 'transcribedText', 'correctedHtml']);
    }

    /**
     * Delete a pamphlet.
     *
     * @param int $pamphletId
     */
    public function deletePamphlet($pamphletId)
    {
        try {
            $pamphlet = Pamphlet::findOrFail($pamphletId);
            if ($pamphlet->audio_path) {
                Storage::delete($pamphlet->audio_path);
            }
            $pamphlet->delete();
            $this->loadPamphlets();
            $this->selectedPamphlet = null;
        } catch (\Exception $e) {
            $this->errorMessage = 'خطا در حذف جزوه. لطفاً دوباره تلاش کنید.';
        }
    }

    /**
     * Log out the authenticated user and redirect to welcome page.
     */
    public function logout()
    {
        \Log::info('Logout method triggered for user: ' . auth()->id());
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        \Log::info('User logged out, redirecting to welcome');
        $this->redirectRoute('welcome', navigate: true);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.pamphlet.single-page-pamphlet');
    }
}