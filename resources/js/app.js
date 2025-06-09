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
