<section class="chat">
  <aside class="chat-sidebar">
    <div class="user">Conectado como <strong><?= htmlspecialchars($authUser['name']) ?></strong></div>
    <div id="notifications" class="notifications"></div>
  </aside>
  <section class="chat-main">
    <div id="messages" class="messages"></div>
    <form id="messageForm" class="message-form">
      <input id="messageInput" type="text" placeholder="Escribe un mensaje" autocomplete="off" required>
      <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
  </section>
</section>
<script>
  window.CHAT_WS_URL = <?= json_encode($wsUrl) ?>;
</script>
