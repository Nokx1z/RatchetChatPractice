<?php ob_start(); ?>
<h1>Chat en tiempo real</h1>
<input type="text" id="username" placeholder="Tu nombre">
<input type="text" id="message" placeholder="Escribe un mensaje">
<button id="send">Enviar</button>

<ul id="chat-box"></ul>

<!-- Tu JS y CSS ahora están en assets -->
<link rel="stylesheet" href="/assets/styles/style.css">
<script src="/assets/js/chat.js"></script>
<?php $content = ob_get_clean(); include 'layout.php'; ?>