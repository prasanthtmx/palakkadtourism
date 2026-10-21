# Gemini chatbot (Ask Palakkad)

Homepage visitor guide powered by Google Gemini. It answers **Palakkad district** questions only (places, temples, travel, festivals, hospitals). Other topics are refused.

The widget is PHP + JavaScript. The API key stays on the server and is never sent to the browser.

This is separate from [Facebook Messenger](MESSENGER_CHAT_SETUP.md). On the homepage the Gemini bubble replaces Messenger so the two do not overlap. Other pages still use Messenger.

## Where it appears

- **Home page only** (`index.php`) — green **Ask Palakkad** button, bottom-right
- Suggested chips: How to reach, Two-day plan, Silent Valley
- Endpoint: `chatbot-api.php` (blocked in `robots.txt`, `noindex`)

## Setup

### 1. Get a Gemini API key

1. Open [Google AI Studio](https://aistudio.google.com/apikey)
2. Create an API key
3. Keep the key private

### 2. Put the key on the server

1. Copy the example file (if `gemini.local.php` is not already there):

```bash
cp config/gemini.local.example.php config/gemini.local.php
```

2. Edit `config/gemini.local.php`:

```php
define('GEMINI_API_KEY', 'your_real_key_here');
define('GEMINI_MODEL', 'gemini-3.8-flash');
```

3. Do **not** commit `config/gemini.local.php`. It is gitignored, same as the database password.

### 3. Test

1. Open the homepage
2. Click **Ask Palakkad**
3. Ask “How do I reach Palakkad?” — you should get a travel answer
4. Ask something off-topic (for example coding or crypto) — the bot should refuse and steer back to Palakkad

If the key is missing, on-topic questions say the guide is not connected yet. Off-topic refusals still work without a key.

## What it will and will not answer

**Yes:** Palakkad / Palghat places, temples, churches, mosques, Jain temple, dams, Silent Valley, Parambikulam, Nelliyampathy, Malampuzha, festivals, food, how to reach, hospitals, heritage, culture, trip planning in the district.

**No:** other cities (unless a route *into* Palakkad), general knowledge, politics, medical advice, bookings, invented prices or phone numbers, programming.

When a question matches a listing in MySQL (temples, places, festivals, hospitals, and so on), a short snippet is sent to Gemini so the reply can use site data.

## Answer cache

Same or very similar questions reuse a stored reply instead of calling Gemini again.

| Rule | Behaviour |
|---|---|
| Same wording (punctuation / case ignored) | Cache hit |
| Close wording (“Tell me about Palakkad Fort” / “What is Palakkad Fort?”) | Cache hit |
| Different place (“How to reach Palakkad” vs “How to reach Silent Valley”) | New Gemini call |
| Vague follow-up (“what about the timings?”) | Goes to Gemini with chat history |
| Off-topic refusal | Not stored (already a fixed reply) |
| Failed Gemini call | Not stored |

- Stored under `logs/chat_cache/` (not in Git; folder is not web-readable via `logs/.htaccess`)
- Default lifetime: **14 days**
- Default size: **400** questions
- Cache hits do **not** count against the hourly Gemini limit
- The web server must be able to write to `logs/chat_cache/` (create it if needed; `chmod 777 logs/chat_cache` on a typical Apache setup)

## Configuration

Set these in `config/gemini.local.php` (optional values have defaults in `config/gemini.php`):

| Constant | Default | Meaning |
|---|---|---|
| `GEMINI_API_KEY` | *(required)* | Google AI Studio key |
| `GEMINI_MODEL` | `gemini-3.8-flash` | Model name from AI Studio |
| `GEMINI_MAX_OUTPUT_TOKENS` | `512` | Max reply length |
| `GEMINI_RATE_PER_HOUR` | `20` | Live Gemini calls per visitor IP per hour |
| `GEMINI_CACHE_TTL` | `14 * 86400` | Cache lifetime in seconds |
| `GEMINI_CACHE_MAX` | `400` | Max stored questions |

Example:

```php
define('GEMINI_API_KEY', 'your_real_key_here');
define('GEMINI_MODEL', 'gemini-3.8-flash');
define('GEMINI_CACHE_TTL', 14 * 86400);
```

If Google retires a model, the API returns “model is no longer available”. Change `GEMINI_MODEL` to the name shown in that error (or in AI Studio).

## Files

```
config/gemini.php                 # Loads defaults + local key
config/gemini.local.example.php   # Template (safe to commit)
config/gemini.local.php           # Real key (gitignored)
chatbot-api.php                   # JSON endpoint called by the widget
includes/gemini_chat.php          # Palakkad rules, Gemini call, cache
includes/chatbot_widget.php       # Homepage bubble markup
assets/js/chatbot.js
assets/css/chatbot.css
logs/chat_cache/                  # Cached answers (not in Git)
```

Homepage wiring:

- `index.php` sets `$showGeminiChat = true` **before** the header
- `includes/header.php` loads `chatbot.css` when that flag is set
- `includes/footer.php` shows the Gemini widget on the homepage and Messenger on other pages

## Security

- Key is used only in PHP; JavaScript never sees it
- Endpoint accepts POST from this site only (same origin / referer)
- Rate limit: 20 Gemini calls per IP per hour, plus a 2-second gap between live calls
- Replies are shown as plain text (not HTML)
- Do not put the key in Git, screenshots, or chat logs

## Troubleshooting

| Symptom | What to check |
|---|---|
| “Guide is not connected yet” | `config/gemini.local.php` exists and `GEMINI_API_KEY` is not the placeholder |
| “Guide is busy” / model error | Update `GEMINI_MODEL` (old names such as `gemini-2.0-flash` may be retired) |
| Off-topic answers about Palakkad | Expected — the bot is scoped to the district |
| Similar questions still call Gemini | Confirm `logs/chat_cache/` exists and is writable by Apache |
| Widget missing | Confirm you are on the homepage, not an inner page |
| Two chat bubbles | Gemini is homepage-only; Messenger is on other pages |

PHP errors: `logs/php_errors.log`.

## Showing the widget on more pages

Set `$showGeminiChat = true` at the top of that page **before** `includes/header.php`, the same way `index.php` does. Messenger will be hidden on that page.
