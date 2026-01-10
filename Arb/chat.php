<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_phone'])) { header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>ARB Support</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; overflow: hidden; font-family: -apple-system, sans-serif; background:#f0f2f5; }
        body { display: flex; flex-direction: column; }
        
        /* Header Restored with Logo */
        .header { 
            background:#fff; padding: 10px 15px; border-bottom:1px solid #eee; 
            display:flex; align-items:center; justify-content: space-between; 
            position: fixed; top: 0; width: 100%; z-index: 100; height: 60px;
        }
        .arb-logo-img { width:35px; height:35px; object-fit: contain; }

        /* Chat Window */
        #chat-win { 
            flex: 1; overflow-y: auto; padding: 75px 15px 85px 15px; 
            display: flex; flex-direction: column; gap: 12px; background-color: #e5ddd5;
            scroll-behavior: smooth;
        }

        /* Bot Options Styling */
        #bot-options {
            display: flex; flex-direction: column; gap: 10px; padding: 15px;
            background: #fff; border-radius: 12px; border: 1px solid #ddd;
            margin-bottom: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .opt-btn {
            background: #f8f9fa; border: 1px solid #f9c21b; color: #333;
            padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer;
            text-align: left; font-size: 14px;
        }

        /* Message Bubbles */
        .msg-container { display: flex; flex-direction: column; width: 100%; }
        .msg-container.agent { align-items: flex-start; }
        .msg-container.user { align-items: flex-end; }
        .msg { 
            padding: 10px 14px; border-radius: 15px; font-size: 14px; 
            max-width: 85%; word-wrap: break-word; position: relative;
        }
        .user .msg { background:#f9c21b; color:#000; border-bottom-right-radius: 2px; }
        .agent .msg { background:#fff; color:#333; border-bottom-left-radius: 2px; border: 1px solid #ddd; }
        .time { font-size: 9px; opacity: 0.5; margin-top: 4px; display: block; text-align: right; }

        /* Bottom Input */
        .input-bar { 
            position: fixed; bottom: 0; width: 100%; background:#fff; 
            padding: 10px; display:flex; align-items:center; gap:8px; 
            border-top:1px solid #eee; z-index: 100;
        }
        #mInp { flex:1; padding: 12px 15px; border: 1px solid #ddd; border-radius:25px; outline:none; font-size: 16px; }
        .send-btn { background:#f9c21b; border:none; width:45px; height:45px; border-radius:50%; cursor:pointer; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

<div class="header">
    <div style="display:flex; align-items:center; gap:10px;">
        <a href="https://arbpay.me/#/home"><img src="https://arbpay.me/img/logo.png" class="arb-logo-img"></a>
        <div>
            <b style="font-size:14px; display:block;">ARB Support Team ✔</b>
            <span style="font-size:11px; color:#28a745; font-weight:bold;">● Online</span>
           <a href="buy.php" style="display:inline-block;margin-top:4px;font-size:11px;background:#f9c21b;color:#000;padding:3px 10px;border-radius:12px;font-weight:600;text-decoration:none;">💰 Buy / OTP-UPI</a>
        </div>
    </div>
    <a href="https://arbpay.me/#/home" style="font-size:11px; color:#f9c21b; text-decoration:none; border:1px solid #f9c21b; padding:4px 10px; border-radius:15px;">Official Site</a>
</div>

<div id="chat-win">
    <div id="bot-options" style="display:none;">
        <button class="opt-btn" onclick="quickAction('Account unfreeze request')">❄️ Account Unfreeze Problem</button>
        <button class="opt-btn" onclick="quickAction('Withdrawal problem')">💸 Withdrawal Problem</button>
        <button class="opt-btn" onclick="quickAction('Deposit problem')">💳 Deposit Problem</button>
        <button class="opt-btn" onclick="quickAction('Talk with agent')">🎧 Talk with Agent</button>
    </div>
</div>

<div class="input-bar">
    <label style="font-size:24px; cursor:pointer;">
        📎<input type="file" id="fInp" style="display:none" onchange="uploadFile(this)" accept="image/*">
    </label>
    <input type="text" id="mInp" placeholder="Type message..." autocomplete="off">
    <button class="send-btn" onclick="sendT()">➤</button>
</div>

<script>
    const win = document.getElementById('chat-win');
    const msgInput = document.getElementById('mInp');
    const botOptions = document.getElementById('bot-options');
    let lastCount = 0;

    // Instant UI Logic
    function appendInstant(text, sender) {
        const container = document.createElement('div');
        container.className = `msg-container ${sender}`;
        const now = new Date();
        const time = now.getHours() + ":" + String(now.getMinutes()).padStart(2, '0');
        container.innerHTML = `<div class="msg">${text}<span class="time">${time}</span></div>`;
        win.appendChild(container);
        win.scrollTop = win.scrollHeight;
    }

    function syncChat() {
        fetch('chat_api.php?action=fetch')
        .then(r => r.json())
        .then(data => {
            if (data.length === 0) {
                botOptions.style.display = 'flex';
            } else {
                botOptions.style.display = 'none';
                if(data.length > lastCount) {
                    renderMessages(data);
                    lastCount = data.length;
                }
            }
        });
    }

    function renderMessages(data) {
        const currentMsgs = win.querySelectorAll('.msg-container');
        currentMsgs.forEach(m => m.remove());
        data.forEach(m => {
            const container = document.createElement('div');
            container.className = `msg-container ${m.sender}`;
            const body = m.type === 'image' ? `<img src="${m.url}" style="max-width:100%; border-radius:10px;">` : m.text;
            container.innerHTML = `<div class="msg">${body}<span class="time">${m.time}</span></div>`;
            win.appendChild(container);
        });
        win.scrollTop = win.scrollHeight;
    }

    function quickAction(txt) {
        botOptions.style.display = 'none';
        appendInstant(txt, 'user'); // Instant appearance
        const fd = new FormData();
        fd.append('msg', txt);
        fetch('chat_api.php?action=send', { method: 'POST', body: fd }).then(() => syncChat());
    }

    function sendT() {
        const text = msgInput.value.trim();
        if(!text) return;
        msgInput.value = '';
        appendInstant(text, 'user'); // Instant appearance
        const fd = new FormData();
        fd.append('msg', text);
        fetch('chat_api.php?action=send', { method: 'POST', body: fd }).then(() => syncChat());
    }

    function uploadFile(input) {
        if(input.files[0]) {
            const fd = new FormData();
            fd.append('image', input.files[0]);
            fetch('chat_api.php?action=upload', { method:'POST', body:fd }).then(() => syncChat());
        }
    }

    msgInput.addEventListener("keypress", (e) => { if(e.key === "Enter") sendT(); });
    setInterval(syncChat, 2000);
    syncChat();
</script>
</body>
</html>
