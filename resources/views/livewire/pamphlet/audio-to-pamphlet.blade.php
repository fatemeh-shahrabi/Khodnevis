<div class="min-h-screen bg-gray-100 py-12 font-vazir" dir="rtl">
    <x-slot name="header">
        <h2 class="font-vazir text-2xl text-gray-800 leading-tight text-center">
            خودنویس
        </h2>
    </x-slot>

    <div class="container mx-auto px-6 sm:px-8 lg:px-16">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <div class="text-center">
                    <h3 class="text-2xl font-bold text-gray-700">صدای خود را به جزوه تبدیل کنید</h3>
                    <p class="text-gray-500 mt-2">
                        فایل صوتی خود را بارگذاری کنید و دستورالعمل‌های دلخواه خود را وارد کنید.
                    </p>
                </div>

                <div class="mt-8">
                    <div class="mb-6">
                        <x-input-label for="audioFile" :value="'انتخاب فایل صوتی'" />
                        <x-text-input wire:model="audioFile" id="audioFile" name="audioFile" type="file"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right" />
                        <x-input-error :messages="$errors->get('audioFile')" class="mt-2" />
                    </div>

                    <div class="mb-6">
                        <x-input-label for="customPrompt" :value="'دستورالعمل‌های دلخواه (اختیاری)'" />
                        <textarea wire:model="customPrompt" id="customPrompt" name="customPrompt"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right"
                            rows="4" placeholder="دستورالعمل‌های دلخواه خود را وارد کنید..."></textarea>
                        <x-input-error :messages="$errors->get('customPrompt')" class="mt-2" />
                    </div>

                    <div wire:loading class="text-center loading-state text-blue-600 font-semibold">
                        در حال پردازش درخواست شما...
                    </div>

                    <div class="text-center">
                        <button wire:click="transcribeAndGenerate"
                            class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition ease-in-out duration-150">
                            ایجاد جزوه
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if ($correctedHtml)
            <div class="mt-12 bg-gray-50 p-8 rounded-lg shadow-lg">
                <div class="mt-4 prose max-w-none text-right">
                    {!! $correctedHtml !!}
                </div>
            </div>
        @endif
    </div>
</div>
