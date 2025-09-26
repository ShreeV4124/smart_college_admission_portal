let chatbot_btn = document.querySelector(".chatbot-pop");

chatbot_btn.addEventListener('click', function () {

    // Check if popup already exists
    let popup = document.querySelector(".chatbox-container");
    if (!popup) {
        // Inject Chatbot Popup into Body
        fetch('html/chatbot.html')  // path to your chatbot.html
            .then(response => response.text())
            .then(html => {
                // Inject HTML into the page
                document.body.insertAdjacentHTML('beforeend', html);

                let userInput = document.getElementById('user-input');
    let sendBtn = document.getElementById('send-btn');
    let chatBox = document.querySelector('.chat-box');
    let closeBtn = document.getElementById('close-chatbot');

    // Close button just hides popup
    closeBtn.addEventListener('click', () => {
        console.log("closing chatbot container");
        document.querySelector('.chatbox-container').classList.add('hidden');
    });

    // Function to add bot message
    function addBotMessage(message) {
        const messageElement = document.createElement("div");
        messageElement.classList.add("bot-message");
        messageElement.textContent = "Bot : " + message;
        chatBox.appendChild(messageElement);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Enter key triggers send
    userInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            sendBtn.click();
        }
    });

    // Send button logic
    sendBtn.addEventListener('click', function () {
        let message = userInput.value.trim();
        if (message === '') return;

        // Display user message
        let userMsg = document.createElement('div');
        userMsg.className = 'user-message';
        userMsg.textContent = "You : " + message;
        chatBox.appendChild(userMsg);

        // Send to Gemini API via PHP backend
        fetch('chatbot/chatbot.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(
`${message}`
            )
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                addBotMessage("Error: " + data.message);
            } else {
                const reply = data.candidates[0].content.parts[0].text;
                addBotMessage(reply);
            }
        })
        .catch(error => {
            addBotMessage("Something went wrong.");
            console.error(error);
        });

        userInput.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;
    });
            })
            .catch(err => console.error("Error loading chatbot:", err));

            
    } else {
        popup.classList.toggle('hidden');
    }



});
