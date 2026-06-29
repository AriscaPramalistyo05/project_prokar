importScripts("https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js");
importScripts("https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js");

firebase.initializeApp({
  apiKey: "{{ $apiKey }}",
  projectId: "{{ $projectId }}",
  messagingSenderId: "{{ $messagingSenderId }}",
  appId: "{{ $appId }}",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  const { title, body } = payload.notification;
  self.registration.showNotification(title, { body, icon: "/images/logo.png" });
});
