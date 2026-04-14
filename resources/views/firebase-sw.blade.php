importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey:            "{{ config('services.firebase.api_key') }}",
    authDomain:        "{{ config('services.firebase.auth_domain') }}",
    projectId:         "{{ config('services.firebase.project_id') }}",
    storageBucket:     "{{ config('services.firebase.storage_bucket') }}",
    messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
    appId:             "{{ config('services.firebase.app_id') }}",
};

firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/images/LMS.png',
        badge: '/images/LMS.png',
        tag: payload.data?.notification_id || 'lms-notification-' + Date.now(),
        renotify: false,
        requireInteraction: false,
        vibrate: [200, 100, 200]
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});
