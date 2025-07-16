<div class="min-h-screen bg-gray-100 py-12 font-vazir" dir="rtl">
    <x-slot name="header">
        <div class="fixed top-0 left-0 w-full bg-white shadow-md z-10">
            <div class="flex items-center justify-center w-full py-4">
                <img src="{{ asset('images/khodnevis-bg.png') }}" alt="Logo" class="h-14 text-bold">
                <div class="absolute left-14 top-6">
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button class="font-vazir inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                
                                <div class="ms-1">
                                    <svg class="font-vazir fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate class="font-vazir">
                                {{ __('پروفایل') }}
                            </x-dropdown-link>
                
                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link class="font-vazir">
                                    {{ __('خروج') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>                
            </div>
        </div>
    </x-slot>
    
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        @if($errorMessage)
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ $errorMessage }}
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Content Column -->
            <div class="w-full lg:w-3/5 bg-white shadow-lg rounded-lg p-6 overflow-y-auto" style="max-height: 80vh;">
                @if ($showForm)
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-700">صدای خود را به جزوه تبدیل کنید</h3>
                        <p class="text-gray-500 mt-2">
                            فایل صوتی خود را بارگذاری کنید (MP3, WAV, AAC, OGG, FLAC) و دستورالعمل‌های دلخواه خود را وارد کنید.
                        </p>
                    </div>

                    <div class="mt-8 space-y-6">
                        <!-- File Upload -->
                        <div>
                            <x-input-label for="audioFile" :value="'انتخاب فایل صوتی (حداکثر 25MB)'" />
                            <x-text-input wire:model="audioFile" id="audioFile" name="audioFile" type="file"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right mt-1" />
                            <x-input-error :messages="$errors->get('audioFile')" class="mt-2" />
                            @if($audioFile)
                                <p class="text-sm text-gray-500 mt-1">
                                    فایل انتخاب شده: {{ $audioFile->getClientOriginalName() }} ({{ round($audioFile->getSize() / 1024 / 1024, 2) }}MB)
                                </p>
                            @endif
                        </div>

                        <!-- Custom Prompt -->
                        <div>
                            <x-input-label for="customPrompt" :value="'دستورالعمل‌های دلخواه (اختیاری)'" />
                            <textarea wire:model="customPrompt" id="customPrompt" name="customPrompt"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-right"
                                rows="4" placeholder="مثال: این متن یک سخنرانی دانشگاهی است. لطفاً نکات کلیدی را برجسته کنید..."></textarea>
                            <x-input-error :messages="$errors->get('customPrompt')" class="mt-2" />
                        </div>

                        <!-- Processing State -->
                        @if($isProcessing)
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <div class="flex items-center justify-center space-x-2 text-blue-logical-order: 600">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>{{ $processingStep }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                    <div class="bg-blue-600 h-2.5 rounded-full animate-pulse" style="width: 45%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="text-center pt-4">
                            <button wire:click="transcribeAndGenerate" wire:loading.attr="disabled"
                                class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                ایجاد جزوه
                            </button>
                        </div>
                    </div>
                @else
                    <!-- View/Edit Mode -->
                    <div class="space-y-4">
                        <button wire:click="goBackToForm" class="flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                            </svg>
                            بازگشت
                        </button>

                        @if ($selectedPamphlet)
                            <div class="border-b pb-4">
                                <h3 class="text-2xl font-bold">{{ $selectedPamphlet->title }}</h3>
                                <p class="text-gray-400 text-sm">
                                    ایجاد شده در {{ $selectedPamphlet->created_at->locale('fa_IR')->isoFormat('YYYY/M/D HH:mm') }}
                                    ({{ $selectedPamphlet->created_at->locale('fa')->diffForhumans() }})
                                </p>
                            </div>
                            
                            <div class="mt-4 prose max-w-none text-right">
                                {!! $selectedPamphlet->html_content !!}
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button wire:click="deletePamphlet({{ $selectedPamphlet->id }})" onclick="return confirm('آیا از حذف این جزوه مطمئن هستید؟')"
                                    class="px-4 py-2 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2 transition ease-in-out duration-150">
                                    حذف جزوه
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- History Column -->
            <div class="w-full lg:w-2/5 bg-white shadow-lg rounded-lg p-6 overflow-y-auto" style="max-height: 80vh;">
                <h3 class="text-xl font-bold text-gray-700 border-b pb-3">تاریخچه جزوات</h3>
                
                @if($pamphlets->isEmpty())
                    <div class="text-center py-8 text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2">هنوز جزوه‌ای ایجاد نکرده‌اید</p>
                    </div>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($pamphlets as $pamphlet)
                            <div class="p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200
                                {{ $selectedPamphlet && $selectedPamphlet->id === $pamphlet->id ? 'bg-blue-50 border-blue-200' : '' }}"
                                wire:click="showPamphlet({{ $pamphlet->id }})">
                                <div class="flex items-start">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-semibold text-gray-700 truncate">{{ $pamphlet->title }}</h4>
                                        <p class="text-gray-500 text-sm mt-1 line-clamp-2">
                                            {{ Str::limit(strip_tags($pamphlet->html_content), 150) }}
                                        </p>
                                        <div class="flex justify-between items-center mt-2">
                                            <p class="text-gray-400 text-xs">
                                                {{ $pamphlet->created_at->locale('fa')->diffForHumans() }}
                                            </p>
                                            @if($pamphlet->audio_path)
                                                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full whitespace-nowrap">
                                                    دارای صوت
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>