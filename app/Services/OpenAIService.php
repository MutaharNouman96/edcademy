<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->apiKey = env('OPEN_AI_KEY');
        $this->url = 'https://api.openai.com/v1/chat/completions';
    }

    public function generateCourseTitleAndDescription(array $formData)
    {
        // Construct the prompt based on form data
        $prompt = $this->buildPrompt($formData);

        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are a helpful assistant that generates course titles and descriptions.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7
        ];

        $ch = curl_init($this->url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            throw new \Exception('OpenAI API error: ' . $result['error']['message']);
        }

        $content = $result['choices'][0]['message']['content'] ?? 'No response';

        // Parse the response to extract title and description
        // Assuming the response is in a format like "Title: Something\nDescription: Something"
        $lines = explode("\n", $content);
        $title = '';
        $description = '';

        foreach ($lines as $line) {
            if (stripos($line, 'Title:') === 0) {
                $title = trim(str_replace('Title:', '', $line));
            } elseif (stripos($line, 'Description:') === 0) {
                $description = trim(str_replace('Description:', '', $line));
            }
        }

        return [
            'title' => $title,
            'description' => $description
        ];
    }

    private function buildPrompt(array $formData)
    {
        $prompt = "Generate a compelling course title and description based on the following information:\n\n";

        if (!empty($formData['course_category_name'])) {
            $prompt .= "Category: " . $formData['course_category_name'] . "\n";
        }
        if (!empty($formData['subject'])) {
            $prompt .= "Subject: " . $formData['subject'] . "\n";
        }
        if (!empty($formData['level'])) {
            $prompt .= "Level: " . $formData['level'] . "\n";
        }
        if (!empty($formData['language'])) {
            $prompt .= "Language: " . $formData['language'] . "\n";
        }
        if (!empty($formData['difficulty'])) {
            $prompt .= "Difficulty: " . $formData['difficulty'] . "\n";
        }
        if (!empty($formData['type'])) {
            $prompt .= "Type: " . $formData['type'] . "\n";
        }
        if (!empty($formData['duration'])) {
            $prompt .= "Duration: " . $formData['duration'] . "\n";
        }
        // Add more fields as needed

        $prompt .= "\nPlease provide the title on one line starting with 'Title:', and the description starting with 'Description:'.";

        return $prompt;
    }
}