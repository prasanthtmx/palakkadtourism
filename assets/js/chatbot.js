(function () {
    var root = document.getElementById('pkd-chat');
    if (!root) {
        return;
    }

    var api = root.getAttribute('data-api') || '';
    var toggle = document.getElementById('pkd-chat-toggle');
    var panel = document.getElementById('pkd-chat-panel');
    var closeBtn = root.querySelector('.pkd-chat-close');
    var form = document.getElementById('pkd-chat-form');
    var input = document.getElementById('pkd-chat-input');
    var log = document.getElementById('pkd-chat-log');
    var history = [];
    var busy = false;

    function setOpen(open) {
        panel.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        root.classList.toggle('pkd-chat--open', open);
        if (open && input) {
            input.focus();
        }
    }

    function appendMessage(role, text) {
        var wrap = document.createElement('div');
        wrap.className = 'pkd-chat-msg pkd-chat-msg--' + (role === 'user' ? 'user' : 'bot');
        var p = document.createElement('p');
        p.textContent = text;
        wrap.appendChild(p);
        log.appendChild(wrap);
        log.scrollTop = log.scrollHeight;
    }

    function setBusy(on) {
        busy = on;
        root.classList.toggle('pkd-chat--busy', on);
        if (form) {
            form.querySelector('.pkd-chat-send').disabled = on;
        }
    }

    function send(text) {
        var message = (text || '').trim();
        if (!message || busy) {
            return;
        }
        appendMessage('user', message);
        if (input) {
            input.value = '';
        }
        setBusy(true);
        var thinking = document.createElement('div');
        thinking.className = 'pkd-chat-msg pkd-chat-msg--bot pkd-chat-msg--wait';
        thinking.innerHTML = '<p>Looking up Palakkad…</p>';
        log.appendChild(thinking);
        log.scrollTop = log.scrollHeight;

        fetch(api, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: message, history: history })
        })
            .then(function (res) {
                return res.json().then(function (data) {
                    return { okHttp: res.ok, data: data };
                });
            })
            .then(function (result) {
                thinking.remove();
                var reply = (result.data && result.data.reply) ? String(result.data.reply) : 'Please try again.';
                appendMessage('bot', reply);
                if (result.data && result.data.ok) {
                    history.push({ role: 'user', text: message });
                    history.push({ role: 'model', text: reply });
                    if (history.length > 8) {
                        history = history.slice(-8);
                    }
                }
            })
            .catch(function () {
                thinking.remove();
                appendMessage('bot', 'The guide could not connect. Please try again.');
            })
            .then(function () {
                setBusy(false);
            });
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            setOpen(panel.hidden);
        });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            setOpen(false);
            toggle.focus();
        });
    }
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            send(input.value);
        });
    }
    root.querySelectorAll('.pkd-chat-chip').forEach(function (chip) {
        chip.addEventListener('click', function () {
            send(chip.getAttribute('data-q') || chip.textContent);
        });
    });
})();
