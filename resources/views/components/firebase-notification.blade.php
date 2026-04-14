<!-- Firebase Push Notification -->
<x-firebase-meta />
<script type="module">
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js';
    import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging.js';

    const getMeta = (name) => document.querySelector(`meta[name="${name}"]`)?.content;

    const firebaseConfig = {
        apiKey:            getMeta('firebase-api-key'),
        authDomain:        getMeta('firebase-auth-domain'),
        projectId:         getMeta('firebase-project-id'),
        storageBucket:     getMeta('firebase-storage-bucket'),
        messagingSenderId: getMeta('firebase-messaging-sender-id'),
        appId:             getMeta('firebase-app-id'),
    };

    const vapidKey = getMeta('firebase-vapid-key');

    const app = initializeApp(firebaseConfig);
    const messaging = getMessaging(app);

    async function registerFCMToken() {
        try {
            const registration = await navigator.serviceWorker.ready;
            const token = await getToken(messaging, { vapidKey, serviceWorkerRegistration: registration });
            if (token) {
                await fetch('/api/fcm-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ fcm_token: token })
                });
            }
        } catch (error) {
            console.error('Notification error:', error);
        }
    }

    // Auto request permission after 2 seconds
    setTimeout(async () => {
        if (Notification.permission === 'default') {
            const permission = await Notification.requestPermission();
            if (permission === 'granted') await registerFCMToken();
        } else if (Notification.permission === 'granted') {
            await registerFCMToken();
        }
    }, 2000);

    // Handle foreground messages
    onMessage(messaging, (payload) => {
        if (document.hidden && Notification.permission === 'granted' && 'serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then((registration) => {
                registration.showNotification(payload.notification.title, {
                    body: payload.notification.body,
                    icon: '/images/LMS.png',
                    badge: '/images/LMS.png',
                    tag: payload.data?.notification_id || 'lms-' + Date.now(),
                    renotify: false,
                    requireInteraction: false,
                    vibrate: [200, 100, 200]
                });
            });
        }
    });

    // Register service worker
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/firebase-messaging-sw.js')
            .catch(err => console.error('Service Worker error:', err));
    }
</script>
