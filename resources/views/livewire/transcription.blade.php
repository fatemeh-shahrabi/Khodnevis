<div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-6">Audio Transcription</h2>
        <form wire:submit.prevent="transcribe" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="audio_file" class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload Audio File
                </label>
                <input 
                    type="file" 
                    wire:model="audio_file" 
                    id="audio_file" 
                    class="block w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500"
                >
                @error('audio_file') 
                    <span class="text-sm text-red-600">{{ $message }}</span> 
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
            >
                Transcribe
            </button>
        </form>
    </div>

    @if ($transcribed_text)
        <div class="mt-12 bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Transcription Result</h3>
            <div class="text-gray-700 whitespace-pre-line p-4 bg-gray-100 rounded-md border border-gray-300">
                {{ $transcribed_text }}
            </div>
        </div>
    @endif
</div>
