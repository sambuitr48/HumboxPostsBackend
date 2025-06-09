import './bootstrap';

import Echo from 'laravel-echo';
window.io = require('socket.io-client');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'local',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

// Escuchar el evento específico
window.Echo.channel('posts')
    .listen('.post.created', (data) => {
        console.log('Nuevo post recibido:', data);
        
        // Mostrar notificación en la interfaz
        const notification = new Notification('Nuevo post creado', {
            body: data.post.title
        });
        
        // O actualizar el DOM
        document.body.insertAdjacentHTML('beforeend', 
            `<div class="alert">Nuevo post: ${data.post.title}</div>`);
    });