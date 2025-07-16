<div class="min-h-screen bg-gray-100 py-12 font-vazir" dir="rtl">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-vazir text-xl text-gray-800 leading-tight">
                نمایش جزوه
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-8">
                    <h3 class="text-3xl font-bold text-gray-700">
                        {{ $pamphlet->title ?? 'بدون عنوان' }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        <strong>تاریخ ایجاد:</strong>  {{ \Carbon\Carbon::parse($pamphlet->created_at)->locale('fa')->diffForHumans() }}
                    </p>
                </div>

                <div class="prose max-w-none text-right mb-8">
                    {!! $pamphlet->html_content !!}
                </div>

                <div class="flex justify-between mb-4">
                    <div>
                        <a href="{{ route('pamphlet.history') }}">
                            <x-secondary-button>
                                بازگشت به تاریخچه جزوات
                            </x-secondary-button>
                        </a>
                    </div>
                    <div>
                        <x-danger-button wire:click="deletePamphlet({{ $pamphlet->id }})">
                            حذف جزوه
                        </x-danger-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
