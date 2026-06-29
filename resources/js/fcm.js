import { initializeApp } from "firebase/app";
import { getMessaging, getToken } from "firebase/messaging";

const config = JSON.parse(document.getElementById("firebase-config").textContent);
const app = initializeApp(config);
const messaging = getMessaging(app);

export async function requestFcmToken() {
    try {
        const token = await getToken(messaging, { vapidKey: config.vapidKey });
        if (token) {
            await fetch("/api/fcm/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ token }),
            });
        }
    } catch (err) {
        console.error("FCM token error:", err);
    }
}
