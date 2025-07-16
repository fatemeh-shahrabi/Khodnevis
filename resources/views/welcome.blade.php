<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خودنویس - تبدیل صوت به جزوه</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Vazir&display=swap" rel="stylesheet">
</head>
<style>
    @font-face {
        font-family: 'Vazirmatn';
        font-weight: 400;
        font-style: normal;
        src: url('/fonts/Vazirmatn-Regular.woff2') format('woff2');
    }

    .font-vazir {
        font-family: 'Vazirmatn', sans-serif;
    }
</style>
<body class="font-vazir bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="fixed top-0 left-0 w-full bg-white shadow-md z-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
            <img src="{{ asset('images/khodnevis-bg.png') }}" alt="Khodnevis Logo" class="h-14">
            <div class="flex items-center space-x-4 space-x-reverse">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-500 font-medium">ورود</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">ثبت‌نام</a>
                @else
                    <a href="{{ route('khodnevis.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">رفتن به خودنویس</a>
                @endguest
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 lg:px-8 mt-24 py-12 flex-grow">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">خودنویس: تبدیل صوت به جزوه‌های ساختاریافته</h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
                با خودنویس، فایل‌های صوتی خود را به جزوه‌های حرفه‌ای و منظم تبدیل کنید. از فناوری هوش مصنوعی (Whisper و GPT) برای رونویسی دقیق و تولید محتوای ساختاریافته به زبان فارسی استفاده کنید.
            </p>
            <a href="{{ route('register') }}" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-600">
                شروع کنید
            </a>
        </div>

        <!-- Features Section -->
        <section class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-3">رونویسی دقیق</h3>
                <p class="text-gray-500">
                    پشتیبانی از فایل‌های صوتی MP3، WAV، AAC، OGG و FLAC با رونویسی دقیق به زبان فارسی، انگلیسی و عربی.
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-3">جزوه‌های ساختاریافته</h3>
                <p class="text-gray-500">
                    تولید جزوه با بخش‌های مقدمه، متن اصلی و نتیجه‌گیری، با فرمت حرفه‌ای و خوانا.
                </p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-3">دستورالعمل‌های دلخواه</h3>
                <p class="text-gray-500">
                    با وارد کردن دستورالعمل‌های سفارشی، خروجی را مطابق نیاز خود تنظیم کنید.
                </p>
            </div>
        </section>
    </main>

</body>
</html>