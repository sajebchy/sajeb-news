// Web Push Notification Manager
class PushNotificationManager {
    constructor() {
        this.vapidPublicKey = document.head.querySelector('meta[name="vapid-public-key"]')?.content || '';
        this.serviceWorkerPath = '/service-worker.js';
        this.isSupported = 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
    }

    /**
     * Check if push notifications are supported
     */
    isSupported() {
        return this.isSupported;
    }

    /**
     * Check if notifications are enabled
     */
    async isEnabled() {
        if (!this.isSupported) return false;

        const permission = Notification.permission;
        if (permission === 'granted') return true;
        if (permission === 'denied') return false;

        // Check if we have an active subscription
        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.getSubscription();
            return subscription !== null;
        } catch (error) {
            console.error('Error checking push notification status:', error);
            return false;
        }
    }

    /**
     * Request notification permission and subscribe
     */
    async subscribe() {
        if (!this.isSupported) {
            console.error('Push notifications not supported in this browser');
            return { success: false, message: 'আপনার ব্রাউজার push notifications সাপোর্ট করে না।' };
        }

        try {
            // Request permission
            if (Notification.permission === 'default') {
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    return { success: false, message: 'সিস্টেম বিজ্ঞপ্তি অনুমতি প্রয়োজন।' };
                }
            } else if (Notification.permission === 'denied') {
                return { success: false, message: 'সিস্টেম বিজ্ঞপ্তি অক্ষম করা হয়েছে।' };
            }

            // Register service worker
            const registration = await navigator.serviceWorker.register(this.serviceWorkerPath, {
                scope: '/',
            });

            // Subscribe to push
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlBase64ToUint8Array(this.vapidPublicKey),
            });

            // Send subscription to server
            return await this.sendSubscriptionToServer(subscription);

        } catch (error) {
            console.error('Subscription error:', error);
            return { success: false, message: 'সাবস্ক্রিপশন ব্যর্থ হয়েছে।' };
        }
    }

    /**
     * Unsubscribe from push notifications
     */
    async unsubscribe() {
        if (!this.isSupported) return { success: false };

        try {
            const registration = await navigator.serviceWorker.ready;
            const subscription = await registration.pushManager.getSubscription();

            if (!subscription) {
                return { success: false, message: 'কোনো সক্রিয় সাবস্ক্রিপশন নেই।' };
            }

            // Send unsubscribe to server
            await this.sendUnsubscribeToServer(subscription);

            // Unsubscribe from push manager
            await subscription.unsubscribe();

            return { success: true, message: 'সাবস্ক্রিপশন বাতিল হয়েছে।' };

        } catch (error) {
            console.error('Unsubscription error:', error);
            return { success: false, message: 'সাবস্ক্রিপশন বাতিল ব্যর্থ।' };
        }
    }

    /**
     * Send subscription to server
     */
    async sendSubscriptionToServer(subscription) {
        try {
            const response = await fetch('/api/push/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                    publicKey: this.arrayBufferToBase64(subscription.getKey('p256dh')),
                    authToken: this.arrayBufferToBase64(subscription.getKey('auth')),
                }),
            });

            const data = await response.json();
            return data;

        } catch (error) {
            console.error('Server communication error:', error);
            throw error;
        }
    }

    /**
     * Send unsubscribe to server
     */
    async sendUnsubscribeToServer(subscription) {
        try {
            const response = await fetch('/api/push/unsubscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                }),
            });

            return await response.json();

        } catch (error) {
            console.error('Server communication error:', error);
            throw error;
        }
    }

    /**
     * Convert VAPID key from base64 to Uint8Array
     */
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }

        return outputArray;
    }

    /**
     * Convert ArrayBuffer to Base64 string
     */
    arrayBufferToBase64(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        for (let i = 0; i < bytes.byteLength; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    }
}

// Export for global use
window.PushNotificationManager = PushNotificationManager;
