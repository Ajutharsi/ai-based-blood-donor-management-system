<style>
  /* CHAT TOGGLE BUTTON */
  .chat-toggle{
    position:fixed;bottom:1.5rem;right:1.5rem;
    width:52px;height:52px;border-radius:50%;
    background:var(--primary, #1D4ED8);border:none;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;z-index:1000;
    box-shadow:0 4px 20px rgba(29,78,216,0.4);
    transition:all 0.2s;
  }
  .chat-toggle:hover{transform:scale(1.08);background:#1E3A8A;}
  .chat-toggle svg{width:24px;height:24px;stroke:white;fill:none;stroke-width:2;}

  /* CHAT WINDOW */
  .chat-window{
    position:fixed;bottom:5rem;right:1.5rem;
    width:340px;height:480px;
    background:white;border-radius:16px;
    border:1px solid rgba(29,78,216,0.15);
    box-shadow:0 8px 40px rgba(0,0,0,0.12);
    display:flex;flex-direction:column;
    z-index:999;
    transform:scale(0.95) translateY(10px);
    opacity:0;pointer-events:none;
    transition:all 0.25s ease;
  }
  .chat-window.open{
    transform:scale(1) translateY(0);
    opacity:1;pointer-events:all;
  }

  /* CHAT HEADER */
  .chat-header{
    background:#1E3A8A;border-radius:16px 16px 0 0;
    padding:1rem 1.25rem;
    display:flex;align-items:center;gap:10px;
    flex-shrink:0;
  }
  .chat-header-icon{
    width:34px;height:34px;border-radius:50%;
    background:rgba(255,255,255,0.15);
    display:flex;align-items:center;justify-content:center;
  }
  .chat-header-icon svg{width:18px;height:18px;fill:white;}
  .chat-header-text{flex:1;}
  .chat-header-name{font-size:0.875rem;font-weight:500;color:white;}
  .chat-header-status{font-size:0.7rem;color:rgba(255,255,255,0.65);display:flex;align-items:center;gap:4px;}
  .status-dot{width:5px;height:5px;border-radius:50%;background:#86EFAC;}
  .chat-close{background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.7);padding:0;display:flex;}
  .chat-close:hover{color:white;}
  .chat-close svg{width:18px;height:18px;stroke:currentColor;fill:none;stroke-width:2;}

  /* MESSAGES */
  .chat-messages{
    flex:1;overflow-y:auto;padding:1rem;
    display:flex;flex-direction:column;gap:10px;
  }
  .chat-messages::-webkit-scrollbar{width:4px;}
  .chat-messages::-webkit-scrollbar-track{background:transparent;}
  .chat-messages::-webkit-scrollbar-thumb{background:#E4DEDE;border-radius:4px;}

  .msg{display:flex;gap:8px;align-items:flex-end;}
  .msg.user{flex-direction:row-reverse;}
  .msg-avatar{width:28px;height:28px;border-radius:50%;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:500;}
  .msg-avatar.bot{background:#EFF6FF;color:#1E3A8A;}
  .msg-avatar.user-av{background:#1D4ED8;color:white;}
  .msg-bubble{
    max-width:80%;padding:0.6rem 0.875rem;border-radius:12px;
    font-size:0.825rem;line-height:1.55;
  }
  .msg.bot .msg-bubble{background:#F4F1F1;color:#1A0A0B;border-radius:12px 12px 12px 3px;}
  .msg.user .msg-bubble{background:#1D4ED8;color:white;border-radius:12px 12px 3px 12px;}

  /* TYPING INDICATOR */
  .typing{display:flex;gap:4px;padding:0.6rem 0.875rem;background:#F4F1F1;border-radius:12px 12px 12px 3px;width:fit-content;}
  .typing span{width:6px;height:6px;border-radius:50%;background:#6B3B40;animation:bounce 1.2s infinite;}
  .typing span:nth-child(2){animation-delay:0.2s;}
  .typing span:nth-child(3){animation-delay:0.4s;}
  @keyframes bounce{0%,60%,100%{transform:translateY(0);}30%{transform:translateY(-6px);}}

  /* INPUT */
  .chat-input-wrap{
    padding:0.75rem;border-top:1px solid rgba(29,78,216,0.1);
    display:flex;gap:8px;align-items:center;flex-shrink:0;
  }
  .chat-input{
    flex:1;padding:0.6rem 0.875rem;
    border:1px solid #E4DEDE;border-radius:20px;
    font-family:'DM Sans',sans-serif;font-size:0.825rem;
    outline:none;transition:border 0.2s;background:#F4F1F1;
  }
  .chat-input:focus{border-color:#1D4ED8;background:white;}
  .chat-send{
    width:34px;height:34px;border-radius:50%;
    background:#1D4ED8;border:none;
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;flex-shrink:0;transition:all 0.2s;
  }
  .chat-send:hover{background:#1E3A8A;}
  .chat-send svg{width:15px;height:15px;stroke:white;fill:none;stroke-width:2;}
  .chat-send:disabled{background:#E4DEDE;cursor:not-allowed;}

  /* QUICK REPLIES */
  .quick-replies{padding:0 1rem 0.5rem;display:flex;gap:6px;flex-wrap:wrap;}
  .qr-btn{
    font-size:0.72rem;padding:0.3rem 0.8rem;
    border-radius:20px;border:1px solid rgba(29,78,216,0.2);
    background:#EFF6FF;color:#1E3A8A;
    cursor:pointer;transition:all 0.15s;white-space:nowrap;
    font-family:'DM Sans',sans-serif;
  }
  .qr-btn:hover{background:#1D4ED8;color:white;border-color:#1D4ED8;}
</style>

{{-- TOGGLE BUTTON --}}
<button class="chat-toggle" onclick="toggleChat()" id="chatToggleBtn">
  <svg viewBox="0 0 24 24" id="chatIcon">
    <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
  </svg>
</button>

{{-- CHAT WINDOW --}}
<div class="chat-window" id="chatWindow">
  <div class="chat-header">
    <div class="chat-header-icon">
      <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
    </div>
    <div class="chat-header-text">
      <div class="chat-header-name">LifeLink Assistant</div>
      <div class="chat-header-status"><span class="status-dot"></span>Online · Powered by Claude AI</div>
    </div>
    <button class="chat-close" onclick="toggleChat()">
      <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  </div>

  <div class="chat-messages" id="chatMessages">
    <div class="msg bot">
      <div class="msg-avatar bot">AI</div>
      <div class="msg-bubble">
        Hi! I'm LifeLink Assistant 👋 I can help you with blood donation eligibility, finding donors, and anything about the LifeLink system. How can I help you today?
      </div>
    </div>
  </div>

  <div class="quick-replies" id="quickReplies">
    <button class="qr-btn" onclick="sendQuick('Am I eligible to donate?')">Am I eligible?</button>
    <button class="qr-btn" onclick="sendQuick('What is my blood group?')">My blood group</button>
    <button class="qr-btn" onclick="sendQuick('How does LifeLink work?')">How it works</button>
    <button class="qr-btn" onclick="sendQuick('What are the donation requirements?')">Requirements</button>
  </div>

  <div class="chat-input-wrap">
    <input type="text" class="chat-input" id="chatInput"
           placeholder="Type a message..."
           onkeydown="if(event.key==='Enter') sendMessage()">
    <button class="chat-send" id="sendBtn" onclick="sendMessage()">
      <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
    </button>
  </div>
</div>

<script>
let chatOpen = false;

function toggleChat() {
  chatOpen = !chatOpen;
  document.getElementById('chatWindow').classList.toggle('open', chatOpen);
}

function sendQuick(text) {
  document.getElementById('chatInput').value = text;
  document.getElementById('quickReplies').style.display = 'none';
  sendMessage();
}

async function sendMessage() {
  const input   = document.getElementById('chatInput');
  const message = input.value.trim();
  if (!message) return;

  // Add user message
  appendMessage(message, 'user');
  input.value = '';
  document.getElementById('sendBtn').disabled = true;

  // Show typing
  const typing = appendTyping();

  try {
    const response = await fetch('{{ route("chat") }}', {
      method:  'POST',
      headers: {
        'Content-Type':     'application/json',
        'X-CSRF-TOKEN':     '{{ csrf_token() }}',
        'Accept':           'application/json',
      },
      body: JSON.stringify({ message }),
    });

    const data = await response.json();
    typing.remove();
    appendMessage(data.reply, 'bot');

  } catch (error) {
    typing.remove();
    appendMessage('Sorry, something went wrong. Please try again.', 'bot');
  }

  document.getElementById('sendBtn').disabled = false;
  input.focus();
}

function appendMessage(text, role) {
  const messages = document.getElementById('chatMessages');
  const div = document.createElement('div');
  div.className = `msg ${role}`;
  div.innerHTML = `
    <div class="msg-avatar ${role === 'bot' ? 'bot' : 'user-av'}">${role === 'bot' ? 'AI' : 'Me'}</div>
    <div class="msg-bubble">${text.replace(/\n/g, '<br>')}</div>
  `;
  messages.appendChild(div);
  messages.scrollTop = messages.scrollHeight;
  return div;
}

function appendTyping() {
  const messages = document.getElementById('chatMessages');
  const div = document.createElement('div');
  div.className = 'msg bot';
  div.innerHTML = `
    <div class="msg-avatar bot">AI</div>
    <div class="typing"><span></span><span></span><span></span></div>
  `;
  messages.appendChild(div);
  messages.scrollTop = messages.scrollHeight;
  return div;
}
</script>