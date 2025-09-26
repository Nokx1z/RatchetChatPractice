# RatchetChatPractice 💬⚡

**Práctica educativa** - Sistema de chat en tiempo real implementado con PHP y WebSockets usando la librería Ratchet.

## 📚 Propósito de esta Práctica

Esta práctica tiene como objetivo demostrar:
- Implementación de WebSockets con PHP puro
- Uso de la librería Ratchet para aplicaciones en tiempo real
- Comunicación bidireccional cliente-servidor
- Manejo de conexiones persistentes

## 🚀 Características Implementadas

- **Chat en tiempo real** con WebSockets

## 🛠️ Tecnologías Utilizadas

- **PHP 7.4+** - Lenguaje del lado del servidor
- **Ratchet** - Librería WebSocket para PHP
- **HTML5 & CSS3** - Interfaz de usuario
- **XAMPP** - Entorno de desarrollo local
- **Composer** - Gestor de dependencias PHP

## 📋 Requisitos Previos

- [XAMPP](https://www.apachefriends.org/es/index.html) instalado
- PHP 7.4 o superior
- Composer (para gestionar dependencias)
- Navegador web moderno con soporte para WebSockets

## 🚀 Instalación y Configuración

### 1. Clonar/Descargar el Proyecto
Coloca los archivos del proyecto en la carpeta `htdocs` de XAMPP:
```
C:\xampp\htdocs\RatchetChatPractice\
```

### 2. Instalar Dependencias
Abre la terminal/CMD en la carpeta del proyecto y ejecuta:
```bash
composer install
```

### 3. Configurar XAMPP
- Inicia **Apache** desde el panel de control de XAMPP
- Asegúrate de que Apache esté escuchando en el puerto 80

### 4. Ejecutar el Servidor WebSocket
Abre una nueva terminal/CMD y ejecuta:
```bash
php server.php
```

**⚠️ IMPORTANTE:** Mantén esta terminal abierta mientras uses el chat.

### 5. Acceder al Chat
Abre tu navegador y visita:
```
http://localhost/RatchetChatPractice/public/
```

## 📁 Estructura del Proyecto

```
RatchetChatPractice/
├── server.php              # Servidor WebSocket principal
├── composer.json           # Configuración de dependencias
├── README.md              # Este archivo
├── public/
│   ├── index.html         # Interfaz principal del chat
└── vendor/                # Dependencias de Composer (se genera automáticamente)
```

## 🎯 Uso del Sistema

1. **Enviar mensajes**: Escribe en el campo de texto y presiona Enter o haz clic en "Enviar"

## 🐛 Solución de Problemas Comunes

### Error: "Puerto 8080 ya en uso"
```bash
# En Windows encontrar proceso usando el puerto:
netstat -ano | findstr :8080

# Luego terminar el proceso (cambiar PID por el número correspondiente):
taskkill /PID [PID] /F
```

### Error de Conexión WebSocket
- Verifica que el servidor WebSocket esté ejecutándose
- Asegúrate de que el firewall no bloquee el puerto 8080
- Revisa la consola del navegador (F12) para mensajes de error

## 💡 Conceptos Aprendidos en esta Práctica

### Backend (PHP/Ratchet)
- Creación de servidores WebSocket
- Manejo de conexiones persistentes
- Implementación del patrón Observer
- Broadcast de mensajes a múltiples clientes
- Gestión de estado de usuarios conectados

**Desarrollado como práctica de WebSockets con PHP y Ratchet**
