let conn = new WebSocket("ws://localhost:8080");
let chatBox = document.getElementById("chat-box");

document.getElementById("send").onclick = () => {
    let username = document.getElementById("username").value;
    let message = document.getElementById("message").value;
    conn.send(username + ": " + message);
};

conn.onmessage = (e) => {
    let li = document.createElement("li");
    li.textContent = e.data;
    chatBox.appendChild(li);
};