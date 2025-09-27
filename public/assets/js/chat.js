let ws;
        let username;

        function connect() {
            ws = new WebSocket('ws://localhost:8081');
            
            ws.onopen = function() {
                console.log('Conectado al servidor WebSocket');
                // Envía login DESPUÉS de que se abra la conexión
                ws.send(JSON.stringify({
                    type: 'login',
                    username: username
                }));
                // Oculta login y muestra chat en su lugar
                document.getElementById('login').style.display = 'none';
                document.getElementById('chat').style.display = 'flex';
                addMessage('Conectado como ' + username + ' (' + new Date().toLocaleString('es-ES') + ')', 'system');
            };

            ws.onmessage = function(event) {
                const data = JSON.parse(event.data);

                switch(data.type) {
                    case 'login':
                        if (data.username !== username) { // No mostrar propio login
                            addMessage(data.message + ' (' + data.timestamp + ')', 'system');
                        }
                        updateUsers(data.users || []);
                        break;
                    case 'message':
                        addMessage(`${data.username} (${data.timestamp}): ${data.message}`, 'user');
                        break;
                    case 'disconnect':
                        addMessage(data.message + ' (' + data.timestamp + ')', 'system');
                        updateUsers(data.users || []);
                        break;
                    case 'echo':
                        addMessage(`Tú (${data.timestamp}): ${data.message}`, 'self');
                        break;
                }
            };

            ws.onclose = function() {
                addMessage('Conexión cerrada. (' + new Date().toLocaleString('es-ES') + ')', 'system');
                document.getElementById('chat').style.display = 'none';
                document.getElementById('login').style.display = 'flex';
                document.getElementById('username').value = ''; // Limpia input
            };

            ws.onerror = function(error) {
                console.error('Error WebSocket:', error);
                addMessage('Error en la conexión. Verifica que el servidor esté corriendo. (' + new Date().toLocaleString('es-ES') + ')', 'system');
            };
        }

        function login() {
            username = document.getElementById('username').value.trim();
            if (!username) {
                alert('Ingresa un nombre válido.');
                return;
            }
            // Solo inicia la conexión, el login se envía en onopen
            connect();
        }

        function sendMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            if (message && ws && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({
                    type: 'message',
                    message: message // No envíes username, el servidor lo maneja
                }));
                input.value = '';
            } else {
                addMessage('No conectado o mensaje vacío. (' + new Date().toLocaleString('es-ES') + ')', 'system');
            }
        }

        function disconnect() {
            if (ws) ws.close();
        }

        function addMessage(text, type) {
            const messages = document.getElementById('messages');
            const div = document.createElement('div');
            div.className = `message ${type}`;
            div.textContent = text;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function updateUsers(users) {
            const usersList = document.getElementById('users');
            usersList.innerHTML = '';
            if (users.length === 0) {
                const li = document.createElement('li');
                li.textContent = 'Ninguno';
                usersList.appendChild(li);
                return;
            }
            users.forEach(user => {
                const li = document.createElement('li');
                li.textContent = user;
                usersList.appendChild(li);
            });
        }