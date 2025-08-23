// Import Firebase scripts required for the service worker
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in the messagingSenderId
firebase.initializeApp({
    apiKey: "AIzaSyCyiHlr63OsMLCthKpgcpGj8Lrj128wTRI",
    authDomain: "laravel-dashboard-c6fde.firebaseapp.com",
    projectId: "laravel-dashboard-c6fde",
    storageBucket: "laravel-dashboard-c6fde.appspot.com",
    messagingSenderId: "632904193840",
    appId: "1:632904193840:web:0cd227dd66f1b8d0509af5",
    measurementId: "G-E8CTH2GHTY"
});

// Retrieve an instance of Firebase Messaging so that it can handle background messages
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message', payload);

    // Send a message to ALL active clients (tabs)
    self.clients.matchAll({ includeUncontrolled: true }).then((clients) => {
        clients.forEach((client) => {
            client.postMessage({
                type: 'UPDATE_NOTIFICATION_COUNT',
                increment: 1 // Tell clients to increment count
            });
        });
    });

    // Show the notification
    const { title, body, icon } = payload.notification;
    self.registration.showNotification(title, { body, icon });
});
