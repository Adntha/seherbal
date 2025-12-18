<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot Tanaman Herbal - SeHerbal</title>
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="chatbot-container">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="header-content">
                <div class="bot-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </div>
                <div class="header-text">
                    <h1>Asisten Herbal AI</h1>
                    <p class="status">
                        <span class="status-dot"></span>
                        Online
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container" id="messagesContainer">
            <!-- Welcome Message -->
            <div class="message bot-message">
                <div class="message-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                    </svg>
                </div>
                <div class="message-content">
                    <div class="message-bubble">
                        <p>Halo! 👋 Saya adalah asisten AI yang siap membantu Anda mempelajari tanaman herbal Indonesia.</p>
                        <p>Silakan tanyakan apa saja tentang khasiat, cara penggunaan, atau informasi tanaman herbal lainnya!</p>
                    </div>
                    <div class="message-time">{{ now()->format('H:i') }}</div>
                </div>
            </div>

            <!-- Quick Suggestions -->
            <div class="quick-suggestions" id="quickSuggestions">
                <p class="suggestions-label">Pertanyaan yang sering ditanyakan:</p>
                <div class="suggestions-list">
                    <button class="suggestion-btn" data-message="Apa khasiat temulawak?">
                        Apa khasiat temulawak?
                    </button>
                    <button class="suggestion-btn" data-message="Tanaman apa yang bagus untuk batuk?">
                        Tanaman apa yang bagus untuk batuk?
                    </button>
                    <button class="suggestion-btn" data-message="Bagaimana cara mengolah jahe untuk obat?">
                        Bagaimana cara mengolah jahe untuk obat?
                    </button>
                    <button class="suggestion-btn" data-message="Tanaman herbal untuk menurunkan darah tinggi?">
                        Tanaman herbal untuk menurunkan darah tinggi?
                    </button>
                </div>
            </div>
        </div>

        <!-- Input Container -->
        <div class="input-container">
            <form id="chatForm" class="chat-form">
                <div class="input-wrapper">
                    <textarea 
                        id="messageInput" 
                        class="message-input" 
                        placeholder="Ketik pertanyaan Anda di sini..."
                        rows="1"
                        maxlength="1000"
                    ></textarea>
                    <button type="submit" class="send-btn" id="sendBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </div>
                <div class="input-footer">
                    <span class="char-count">
                        <span id="charCount">0</span>/1000
                    </span>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>
