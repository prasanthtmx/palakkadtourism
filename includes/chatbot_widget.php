<?php
/**
 * Homepage Gemini chat bubble. Shown only when $showGeminiChat is set.
 */
$chatApiUrl = BASE_PATH . '/chatbot-api.php';
?>
<div id="pkd-chat" class="pkd-chat" data-api="<?php echo htmlspecialchars($chatApiUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="button" class="pkd-chat-toggle" aria-expanded="false" aria-controls="pkd-chat-panel" id="pkd-chat-toggle">
        <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
        <span class="pkd-chat-toggle-label">Ask Palakkad</span>
    </button>
    <section id="pkd-chat-panel" class="pkd-chat-panel" hidden>
        <header class="pkd-chat-head">
            <div>
                <p class="pkd-chat-kicker">Google Gemini</p>
                <h2 class="pkd-chat-title">Palakkad travel guide</h2>
            </div>
            <button type="button" class="pkd-chat-close" aria-label="Close chat">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </header>
        <div class="pkd-chat-log" id="pkd-chat-log" role="log" aria-live="polite">
            <div class="pkd-chat-msg pkd-chat-msg--bot">
                <p>Hi — ask me about Palakkad district only: places, temples, how to reach, festivals, or hospitals. I will not answer other topics.</p>
            </div>
        </div>
        <div class="pkd-chat-chips" aria-label="Suggested questions">
            <button type="button" class="pkd-chat-chip" data-q="How do I reach Palakkad?">How to reach</button>
            <button type="button" class="pkd-chat-chip" data-q="What should I visit in Palakkad in two days?">Two-day plan</button>
            <button type="button" class="pkd-chat-chip" data-q="Tell me about Silent Valley National Park.">Silent Valley</button>
        </div>
        <form class="pkd-chat-form" id="pkd-chat-form">
            <label class="visually-hidden" for="pkd-chat-input">Your question about Palakkad</label>
            <input type="text" id="pkd-chat-input" name="message" maxlength="500" autocomplete="off" placeholder="Ask about Palakkad…">
            <button type="submit" class="pkd-chat-send" aria-label="Send">
                <i class="bi bi-send-fill" aria-hidden="true"></i>
            </button>
        </form>
    </section>
</div>
<script src="<?php echo BASE_PATH; ?>/assets/js/chatbot.js?v=1" defer></script>
