// Firebase Push Notification Handler
import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js';
import { getMessaging, getToken, onMessage } from 'https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging.js';

// Read Firebase config from meta tags injected by Laravel
const firebaseConfig = {
    apiKey:            document.querySelector('meta[name="firebase-api-key"]')?.content,
    authDomain:        document.querySelector('meta[name="firebase-auth-domain"]')?.content,
    projectId:         document.querySelector('meta[name="firebase-project-id"]')?.content,
    storageBucket:     document.querySelector('meta[name="firebase-storage-bucket"]')?.content,
    messagingSenderId: document.querySelector('meta[name="firebase-messaging-sender-id"]')?.content,
    appId:             document.querySelector('meta[name="firebase-app-id"]')?.content,
};

const vapidKey = document.querySelector('meta[name="firebase-vapid-key"]')?.content;

// Initialize Firebase
const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Request permission and get token
export async function requestNotificationPermission() {
    try {
        const permission = await Notification.requestPermission();

        if (permission === 'granted') {
            const registration = await navigator.serviceWorker.ready;
            const token = await getToken(messaging, {
                vapidKey,
                serviceWorkerRegistration: registration
            });

            if (token) {
                await saveFCMToken(token);
                return token;
            }
        }
    } catch (error) {
        console.error('Error getting notification permission:', error);
    }
}

// Save FCM token to server
async function saveFCMToken(token) {
    try {
        await fetch('/api/fcm-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ fcm_token: token })
        });
    } catch (error) {
        console.error('Error saving FCM token:', error);
    }
}

// Handle foreground messages
onMessage(messaging, (payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/LMS.png',
        badge: '/images/LMS.png',
        tag: 'lms-notification',
        requireInteraction: false
    };

    if (Notification.permission === 'granted' && 'serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then((registration) => {
            registration.showNotification(notificationTitle, notificationOptions);
        });
    }
});

// Register service worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .catch((error) => {
            console.error('Service Worker registration failed:', error);
        });
}
