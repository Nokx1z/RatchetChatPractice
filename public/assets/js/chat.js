(function(){
  if (!window.CHAT_WS_URL) return;
  const messagesEl = document.getElementById('messages');
  const notiEl = document.getElementById('notifications');
  const form = document.getElementById('messageForm');
  const input = document.getElementById('messageInput');

  function addMessage(msg){
    const el = document.createElement('div');
    el.className = 'message';
    const meta = document.createElement('div');
    meta.className = 'meta';
    meta.textContent = (msg.from?.name || 'Sistema') + ' · ' + new Date(msg.created_at || Date.now()).toLocaleTimeString();
    const body = document.createElement('div');
    body.className = 'body';
    body.textContent = msg.body || msg.message || '';
    el.appendChild(meta); el.appendChild(body);
    messagesEl.appendChild(el);
    messagesEl.scrollTop = messagesEl.scrollHeight;
  }

  function notify(n){
    // Notificación en sidebar con auto-dismis a los 5s
    const side = document.createElement('div');
    side.textContent = (n.from?.name || 'Alguien') + ' envió: ' + (n.preview || n.body || '');
    notiEl.prepend(side);
    setTimeout(() => side.remove(), 5000);
    // También al feed de mensajes
    addMessage({ from: { name: 'Notificación' }, body: side.textContent, created_at: n.created_at });
  }

  let ws;
  function connect(){
    ws = new WebSocket(window.CHAT_WS_URL);
    ws.addEventListener('open', () => {
      addMessage({from:{name:'Sistema'}, body:'Conectado al servidor.'});
    });
    ws.addEventListener('message', (e) => {
      try{
        const data = JSON.parse(e.data);
        if (data.type === 'message') addMessage(data);
        else if (data.type === 'notification') notify(data);
        else if (data.type === 'history') (data.messages||[]).forEach(addMessage);
        else if (data.type === 'system') addMessage({from:{name:'Sistema'}, body:data.message});
      }catch(err){ console.error(err); }
    });
    ws.addEventListener('close', () => {
      addMessage({from:{name:'Sistema'}, body:'Desconectado. Reintentando en 2s...'});
      setTimeout(connect, 2000);
    });
  }
  connect();

  form?.addEventListener('submit', (e) => {
    e.preventDefault();
    const body = input.value.trim();
    if (!body) return;
    if (ws && ws.readyState === WebSocket.OPEN) {
      ws.send(JSON.stringify({type:'message', to:null, body}));
    } else {
      addMessage({from:{name:'Sistema'}, body:'No conectado. Intentando reconectar...'});
    }
    input.value = '';
  });
})();
