// borra el localstorage cuando se hace reload
// se va a quitar para producción
window.addEventListener('beforeunload', function (e) {
    localStorage.clear();
});

document.addEventListener('DOMContentLoaded', () => {
    const chatWindow = document.getElementById('chat-window');
    const toggleButton = document.getElementById('toggle-chat');
    const closeButton = document.getElementById('close-chat');
    const chatBody = document.getElementById('chat-body');
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-btn');

    const WEBHOOK_URL = 'https://hook.us2.make.com/wwnuytvyfi4m371nthturigm2e4s7eua';

    // User ID stored in localStorage
    let iduser = localStorage.getItem('chat_iduser');

    async function initNewChat() {
        try {
            const response = await fetch(`${WEBHOOK_URL}?Acción=newchat`);
            if (response.ok) {
                const newId = await response.text();
                iduser = newId.replace(/['"]+/g, '').trim();
                localStorage.setItem('chat_iduser', iduser);
                console.log('New User ID assigned:', iduser);
            } else {
                console.error('Failed to initialize chat:', response.status);
            }
        } catch (error) {
            console.error('Error initializing chat:', error);
        }
    }

    // Toggle Chat Window - Initialize chat if no userId exists
    toggleButton.addEventListener('click', async () => {
        const isHidden = chatWindow.style.display === 'none' || chatWindow.style.display === '';

        if (isHidden) {
            // If opening chat and no userId, initialize newchat
            if (!iduser) {
                await initNewChat();
            }
            chatWindow.style.display = 'flex';
            if (chatInput) chatInput.focus();
        } else {
            chatWindow.style.display = 'none';
        }
    });

    closeButton.addEventListener('click', () => {
        chatWindow.style.display = 'none';
    });

    // Send Message Logic
    async function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;

        // Display User Message
        addMessage(message, 'user');
        chatInput.value = '';

        // Show Typing Indicator
        const loadingId = addLoadingIndicator();

        try {
            // Ensure we have a userId
            if (!iduser) {
                await initNewChat(); // Try to get one if missing
                if (!iduser) throw new Error('No user ID available');
            }

            // 2. Chat Interaction (Action=prevchat)
            const params = new URLSearchParams({
                Acción: 'prevchat',
                iduser: iduser,
                Mensaje: message
            });

            const response = await fetch(`${WEBHOOK_URL}?${params.toString()}`);

            // Remove typing indicator
            removeLoadingIndicator(loadingId);

            if (response.ok) {
                const botResponse = await response.text();
                addMessage(botResponse, 'bot');
            } else {
                addMessage('Lo siento, hubo un error al conectar con el servidor.', 'bot');
            }

        } catch (error) {
            removeLoadingIndicator(loadingId);
            console.error('Error sending message:', error);
            addMessage('Error de conexión. Por favor intenta de nuevo.', 'bot');
        }
    }

    sendButton.addEventListener('click', sendMessage);

    chatInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') sendMessage();
    });

    // Validations & UI Helpers
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', sender);
        messageDiv.innerText = text;
        chatBody.appendChild(messageDiv);
        scrollToBottom();
    }

    function addLoadingIndicator() {
        const id = 'loading-' + Date.now();
        const loaderDiv = document.createElement('div');
        loaderDiv.classList.add('message', 'bot', 'loading');
        loaderDiv.id = id;
        loaderDiv.innerText = 'Escribiendo...';
        chatBody.appendChild(loaderDiv);
        scrollToBottom();
        return id;
    }

    function removeLoadingIndicator(id) {
        const loader = document.getElementById(id);
        if (loader) loader.remove();
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }
});
