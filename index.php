<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat en Tiempo Real</title>
    <link rel="stylesheet" href="./public/assets/styles/styles.css">
</head>
<body>
    <div id="app">
        <h1>Chat en Tiempo Real</h1>
        
        <div id="login">
            <label for="username">Ingresa tu nombre:</label>
            <input type="text" id="username" placeholder="Ej. Luis" required>
            <button onclick="login()">Conectar</button>
            <p>Conéctate para unirte al chat en tiempo real.</p>
        </div>
        
        <div id="chat">
            <div id="chat-header">
                <h3>Usuarios conectados</h3>
                <ul id="users"></ul>
            </div>
            
            <div id="messages"></div>
            
            <div id="chat-input">
                <input type="text" id="messageInput" placeholder="Escribe un mensaje..." onkeypress="if(event.keyCode==13) sendMessage();">
                <button onclick="sendMessage()">Enviar</button>
                <button id="disconnectBtn" onclick="disconnect()">Desconectar</button>
            </div>
        </div>
    </div>

    <script src="js/websocket.js"></script>
    <script src="./public/assets/js/chat.js"></script>
</body>
</html>
