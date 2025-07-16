<div class="container mx-auto p-6 bg-gray-100 min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-lg mx-auto">
        <h2 class="text-center text-3xl font-bold text-gray-800 mb-6">Pamphlet Maker</h2>

        <form wire:submit.prevent="generatePamphlet" class="space-y-6">
            <div>
                <textarea 
                    wire:model="userInput" 
                    class="block w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                    rows="6" 
                    placeholder="Type or paste your text here..." 
                    required
                ></textarea>
                @error('userInput') 
                    <span class="text-sm text-red-600">{{ $message }}</span> 
                @enderror
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
            >
                Generate Pamphlet
            </button>
        </form>
    </div>

    @if($correctedHtml)
        <div class="mt-12 bg-white p-8 rounded-lg shadow-md max-w-2xl mx-auto">
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Generated Pamphlet</h3>
            <div class="text-gray-700 whitespace-pre-line p-4 bg-gray-100 rounded-md border border-gray-300">
                {!! $correctedHtml !!}
            </div>
        </div>
    @endif
</div>
