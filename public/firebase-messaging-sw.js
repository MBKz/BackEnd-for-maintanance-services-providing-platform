importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyCEaKkPD_KxPg7tR3D1qv9V3hPXXXYqFdM",
    projectId: "khalea-alena-7c7d4",
    messagingSenderId: "26948665860",
    appId: "1:26948665860:web:d9638a61574a4b95902b51",
});

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function({data:{title,body,icon}}) {
    return self.registration.showNotification(title,{body,icon});
});
