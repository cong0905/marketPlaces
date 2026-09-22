import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

const isSecure = window.location.protocol === 'https:';

// Get config from window.EchoConfig (production) or Vite env (local dev)
const config = window.EchoConfig || {
    broadcaster: import.meta.env.VITE_BROADCAST_CONNECTION || 'reverb',
    reverbKey: import.meta.env.VITE_REVERB_APP_KEY,
    reverbHost: import.meta.env.VITE_REVERB_HOST,
    reverbPort: import.meta.env.VITE_REVERB_PORT,
    reverbScheme: import.meta.env.VITE_REVERB_SCHEME,
    pusherKey: import.meta.env.VITE_PUSHER_APP_KEY,
    pusherCluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    pusherHost: import.meta.env.VITE_PUSHER_HOST,
    pusherPort: import.meta.env.VITE_PUSHER_PORT,
    pusherScheme: import.meta.env.VITE_PUSHER_SCHEME,
};

if (config.broadcaster === 'pusher' || (!config.reverbKey && config.pusherKey)) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: config.pusherKey,
        cluster: config.pusherCluster || 'ap1',
        wsHost: config.pusherHost ? config.pusherHost : `ws-${config.pusherCluster || 'ap1'}.pusher.com`,
        wsPort: config.pusherPort || 80,
        wssPort: config.pusherPort || 443,
        forceTLS: isSecure || (config.pusherScheme || 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} else if (config.reverbKey) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: config.reverbKey,
        wsHost: config.reverbHost,
        wsPort: config.reverbPort || 80,
        wssPort: config.reverbPort || 443,
        forceTLS: isSecure || (config.reverbScheme || 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
