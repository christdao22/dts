import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: process.env.MIX_PUSHER_HOST ? process.env.MIX_PUSHER_HOST : `ws-${process.env.MIX_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: process.env.MIX_PUSHER_PORT ?? 80,
    wssPort: process.env.MIX_PUSHER_PORT ?? 443,
    forceTLS: (process.env.MIX_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
