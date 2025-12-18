<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Handle incoming chatbot messages
     */
    public function sendMessage(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:1000'
        ], [
            'message.required' => 'Pesan tidak boleh kosong',
            'message.max' => 'Pesan terlalu panjang (maksimal 1000 karakter)'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Get user message
        $userMessage = $request->input('message');

        // Send to chatbot service
        $result = $this->chatbotService->sendMessage($userMessage);

        // Return response
        return response()->json($result);
    }

    /**
     * Get quick suggestion questions
     */
    public function getSuggestions()
    {
        $suggestions = $this->chatbotService->getQuickSuggestions();
        
        return response()->json([
            'success' => true,
            'suggestions' => $suggestions
        ]);
    }

    /**
     * Display chatbot page
     */
    public function index()
    {
        return view('chatbot');
    }
}
