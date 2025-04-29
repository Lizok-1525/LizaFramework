const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const admin = require('firebase-admin');
const path = require('path');

// Inicializar Firebase Admin SDK

const serviceAccount = require('./firebase-key.json');
admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
    databaseURL: "https://mi-pagina-web-46841-default-rtdb.asia-southeast1.firebasedatabase.app"
});

const db = admin.database();
const app = express();
const server = http.createServer(app);
const io = socketIo(server);

app.use(express.static(path.join(__dirname, 'public')));

// Cuando un cliente se conecta
io.on('connection', (socket) => {
    console.log('Nuevo usuario conectado');

    // Leer mensajes anteriores de Firebase
    const messagesRef = db.ref('messages');
    messagesRef.limitToLast(20).on('child_added', (snapshot) => {
        socket.emit('chat message', snapshot.val());
    });

    // Recibir mensaje y guardar en Firebase
    socket.on('chat message', (msg) => {
        messagesRef.push(msg);
    });

    socket.on('disconnect', () => {
        console.log('Usuario desconectado');
    });
});

server.listen(3000, () => {
    console.log('Servidor escuchando en http://localhost:3000');
});
