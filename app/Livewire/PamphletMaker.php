<?php

namespace App\Livewire;

use Livewire\Component;
use App\Service\MetisClient;

class PamphletMaker extends Component
{
    public $userInput = '';
    public $correctedHtml = '';

    public function generatePamphlet()
    {
        $this->validate([
            'userInput' => ['required', 'string', 'min:2'],
        ]);

        try {
            $client = MetisClient::getClient();
            $result = $client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "I have a text and I want to turn it into a pamphlet. The pamphlet should be divided into sections with clear headings, easy-to-read bullet points, and a clean design. The content should be well-structured and concise, with important information highlighted. The pamphlet should include an introduction, key details in the body, and a conclusion or summary at the end. The design should be simple and professional, suitable for a scientific or educational audience. Please use appropriate sections and formatting (e.g., bold headings, short paragraphs, and bullet points) to make the pamphlet visually appealing and easy to follow."
                    ],
                    ['role' => 'user', 'content' => $this->userInput],
                ],
            ]);

            $this->correctedHtml = $result['choices'][0]['message']['content'] ?? 'No content generated.';
        } catch (\Exception $e) {
            $this->correctedHtml = 'Something went wrong, please try again.';
            logger()->error('Error generating pamphlet: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pamphlet-maker');
    }
}
