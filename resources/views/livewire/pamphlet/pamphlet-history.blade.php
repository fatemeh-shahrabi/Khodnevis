<div class="min-h-screen bg-gray-100 py-12 font-vazir" dir="rtl">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-vazir text-xl text-gray-800 leading-tight">
                تاریخچه جزوات
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-5">
                <a href="{{ route('pamphlet.create') }}">
                    <x-primary-button>
                        ایجاد جزوه جدید
                    </x-primary-button>
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col divide-y divide-zinc-200">

                    @if ($pamphlets->count() === 0)
                        <div class="bg-amber-500 p-6 rounded-lg">
                            <strong>هشدار:</strong>
                            <p>
                                هنوز هیچ جزوه‌ای ایجاد نشده است.
                            </p>
                        </div>
                    @endif

                    @foreach ($pamphlets as $pamphlet)
                        <div class="flex items-center justify-between py-4">
                            <div>
                                <span class="block text-lg font-bold">
                                    {{ $pamphlet->title ?? 'بدون عنوان' }}
                                </span>
                                <small>
                                    {{ \Carbon\Carbon::parse($pamphlet->created_at)->locale('fa')->diffForHumans() }}
                                </small>
                            </div>
                            <div class="flex items-center">
                                <a href="{{ route('pamphlet.show', $pamphlet->id) }}">
                                    <x-secondary-button>
                                        مشاهده جزوه
                                    </x-secondary-button>
                                </a>

                                <x-danger-button wire:click="deletePamphlet({{ $pamphlet->id }})">
                                    حذف جزوه
                                </x-danger-button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
