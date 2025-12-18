// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// DOM Elements
const messagesContainer = document.getElementById('messagesContainer');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
const charCount = document.getElementById('charCount');
const quickSuggestions = document.getElementById('quickSuggestions');

// State
let isLoading = false;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    autoResizeTextarea();
});

// Setup Event Listeners
function setupEventListeners() {
    // Form submit
    chatForm.addEventListener('submit', handleSubmit);

    // Textarea auto-resize and char count
    messageInput.addEventListener('input', function() {
        autoResizeTextarea();
        updateCharCount();
    });

    // Quick suggestions
    document.querySelectorAll('.suggestion-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const message = this.getAttribute('data-message');
            messageInput.value = message;
            messageInput.focus();
            autoResizeTextarea();
            updateCharCount();
        });
    });

    // Enter to send (Shift+Enter for new line)
    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!isLoading && messageInput.value.trim()) {
                chatForm.dispatchEvent(new Event('submit'));
            }
        }
    });
}

// Handle form submit
async function handleSubmit(e) {
    e.preventDefault();

    const message = messageInput.value.trim();
    
    if (!message || isLoading) return;

    // Hide quick suggestions after first message
    if (quickSuggestions) {
        quickSuggestions.style.display = 'none';
    }

    // Add user message to chat
    addMessage(message, 'user');

    // Clear input
    messageInput.value = '';
    updateCharCount();
    autoResizeTextarea();

    // Show typing indicator
    const typingId = showTypingIndicator();

    // Set loading state
    setLoading(true);

    try {
        // Send message to API
        const response = await fetch('/api/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        });

        const data = await response.json();

        // Remove typing indicator
        removeTypingIndicator(typingId);

        if (data.success) {
            // Add bot response
            addMessage(data.message, 'bot');
        } else {
            // Add error message
            addMessage(data.message || 'Maaf, terjadi kesalahan. Silakan coba lagi.', 'bot', true);
        }

    } catch (error) {
        console.error('Error:', error);
        removeTypingIndicator(typingId);
        addMessage('Maaf, terjadi kesalahan koneksi. Silakan coba lagi.', 'bot', true);
    } finally {
        setLoading(false);
    }
}

// Add message to chat
function addMessage(text, sender = 'bot', isError = false) {
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${sender}-message ${isError ? 'error-message' : ''}`;

    const currentTime = new Date().toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });

    const avatarSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
        </svg>
    `;

    messageDiv.innerHTML = `
        <div class="message-avatar">
            ${avatarSVG}
        </div>
        <div class="message-content">
            <div class="message-bubble">
                ${formatMessage(text)}
            </div>
            <div class="message-time">${currentTime}</div>
        </div>
    `;

    messagesContainer.appendChild(messageDiv);
    scrollToBottom();
}

// Format message text (convert line breaks to paragraphs)
function formatMessage(text) {
    // Split by double line breaks for paragraphs
    const paragraphs = text.split('\n\n').filter(p => p.trim());
    
    if (paragraphs.length > 1) {
        return paragraphs.map(p => `<p>${escapeHtml(p.trim())}</p>`).join('');
    } else {
        // Split by single line breaks
        const lines = text.split('\n').filter(l => l.trim());
        return lines.map(l => `<p>${escapeHtml(l.trim())}</p>`).join('');
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Show typing indicator
function showTypingIndicator() {
    const typingDiv = document.createElement('div');
    const typingId = 'typing-' + Date.now();
    typingDiv.id = typingId;
    typingDiv.className = 'message bot-message';

    typingDiv.innerHTML = `
        <div class="message-avatar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
            </svg>
        </div>
        <div class="message-content">
            <div class="message-bubble">
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>
    `;

    messagesContainer.appendChild(typingDiv);
    scrollToBottom();

    return typingId;
}

// Remove typing indicator
function removeTypingIndicator(typingId) {
    const typingDiv = document.getElementById(typingId);
    if (typingDiv) {
        typingDiv.remove();
    }
}

// Auto-resize textarea
function autoResizeTextarea() {
    messageInput.style.height = 'auto';
    messageInput.style.height = messageInput.scrollHeight + 'px';
}

// Update character count
function updateCharCount() {
    const count = messageInput.value.length;
    charCount.textContent = count;

    if (count > 900) {
        charCount.style.color = '#f5576c';
    } else {
        charCount.style.color = 'var(--text-muted)';
    }
}

// Scroll to bottom
function scrollToBottom() {
    setTimeout(() => {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }, 100);
}

// Set loading state
function setLoading(loading) {
    isLoading = loading;
    sendBtn.disabled = loading;
    messageInput.disabled = loading;

    if (loading) {
        sendBtn.classList.add('loading');
    } else {
        sendBtn.classList.remove('loading');
        messageInput.focus();
    }
}
