<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Plant;

class ChatbotService
{
    private $apiKey;
    private $apiUrl;
    private $dataset;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        // Using the correct Gemini API endpoint
        $this->apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';
        $this->loadDataset();
    }

    /**
     * Load herbal plants dataset from database
     */
    private function loadDataset()
    {
        try {
            // Ambil semua tanaman dari database MySQL
            $this->dataset = Plant::all()->toArray();
            
            if (empty($this->dataset)) {
                Log::warning('Tidak ada data tanaman herbal di database');
            }
        } catch (\Exception $e) {
            $this->dataset = [];
            Log::error('Error loading dataset from database: ' . $e->getMessage());
        }
    }

    /**
     * Build context from dataset for Gemini AI
     */
    private function buildContext()
    {
        if (empty($this->dataset)) {
            return "Maaf, data tanaman herbal tidak tersedia saat ini.";
        }

        $context = "Kamu adalah asisten AI yang ahli dalam tanaman herbal Indonesia. Berikut adalah database tanaman herbal yang kamu ketahui:\n\n";
        
        foreach ($this->dataset as $plant) {
            $context .= "---\n";
            $context .= "Nama: {$plant['name']}\n";
            $context .= "Nama Latin: {$plant['latin_name']}\n";
            $context .= "Famili: {$plant['family']}\n";
            $context .= "Bagian Digunakan: {$plant['part_used']}\n";
            $context .= "Khasiat: {$plant['benefits']}\n";
            
            if (!empty($plant['keywords'])) {
                $context .= "Kata Kunci: {$plant['keywords']}\n";
            }
            
            $context .= "Deskripsi: {$plant['description']}\n";
            $context .= "Cara Penggunaan: {$plant['processing']}\n";
            
            if (!empty($plant['side_effects'])) {
                $context .= "Peringatan: {$plant['side_effects']}\n";
            }
            
            $context .= "\n";
        }

        $context .= "\nInstruksi:\n";
        $context .= "1. Jawab pertanyaan pengguna dengan ramah dan informatif dalam Bahasa Indonesia\n";
        $context .= "2. Gunakan informasi dari database di atas untuk memberikan jawaban yang akurat\n";
        $context .= "3. Jika ditanya tentang tanaman yang tidak ada di database, katakan dengan sopan bahwa informasi tersebut belum tersedia\n";
        $context .= "4. Berikan saran penggunaan yang aman dan selalu ingatkan untuk berkonsultasi dengan ahli kesehatan\n";
        $context .= "5. Gunakan format yang mudah dibaca dengan paragraf yang jelas\n";
        $context .= "6. Jika relevan, sebutkan beberapa tanaman alternatif dengan khasiat serupa\n\n";

        return $context;
    }

    /**
     * Send message to Gemini AI and get response
     */
    public function sendMessage($userMessage)
    {
        try {
            // Validate API key
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'message' => 'API Key Gemini belum dikonfigurasi. Silakan tambahkan GEMINI_API_KEY di file .env'
                ];
            }

            // Build full prompt with context
            $context = $this->buildContext();
            $fullPrompt = $context . "Pertanyaan Pengguna: " . $userMessage;

            // Prepare request payload for Gemini API
            $payload = [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => $fullPrompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 8192,
                ],
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_HARASSMENT',
                        'threshold' => 'BLOCK_NONE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_HATE_SPEECH',
                        'threshold' => 'BLOCK_NONE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                        'threshold' => 'BLOCK_NONE'
                    ],
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_NONE'
                    ]
                ]
            ];

            // Send request to Gemini API
            $response = Http::withoutVerifying()
                ->timeout(90)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

            // Check if request was successful
            if ($response->successful()) {
                $data = $response->json();
                
                // Extract text from response
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'];
                    
                    return [
                        'success' => true,
                        'message' => $aiResponse
                    ];
                } else {
                    Log::error('Gemini API response format unexpected', ['response' => $data]);
                    return [
                        'success' => false,
                        'message' => 'Format respons dari AI tidak sesuai. Silakan coba lagi.'
                    ];
                }
            } else {
                // Handle API errors
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Terjadi kesalahan saat menghubungi AI';
                
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'error' => $errorData
                ]);

                return [
                    'success' => false,
                    'message' => 'Maaf, terjadi kesalahan: ' . $errorMessage
                ];
            }

        } catch (\Exception $e) {
            Log::error('Chatbot Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem. Silakan coba lagi nanti.'
            ];
        }
    }

    /**
     * Get quick suggestions based on dataset
     */
    public function getQuickSuggestions()
    {
        $suggestions = [
            "Apa khasiat temulawak?",
            "Tanaman apa yang bagus untuk batuk?",
            "Bagaimana cara mengolah jahe untuk obat?",
            "Tanaman herbal untuk menurunkan darah tinggi?",
            "Apa efek samping kunyit?"
        ];

        return $suggestions;
    }
}
