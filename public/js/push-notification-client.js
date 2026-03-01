/**
 * Push Notification Client
 * Handles subscription flow with proper error handling and state management
 */
class PushNotificationClient {
    constructor() {
        this.vapidPublicKey = document.head.querySelector('meta[name="vapid-public-key"]')?.content || '';
        this.serviceWorkerPath = '/service-worker.js';
        this.isSupported = this.checkSupport();
        this.subscription = null;
        this.manager = new PushNotificationManager();
    }

    /**
     * Check if push notifications are supported
     */
    checkSupport() {
        return 'serviceWorker' in navigator && 
               'PushManager' in window && 
               'Notification' in window &&
               this.vapidPublicKey;
    }

    /**
     * Initialize and setup all push notification buttons
     */
    async initialize() {
        if (!this.isSupported) {
            console.warn('Push notifications not supported');
            this.updateAllButtons('disabled');
            return;
        }

        try {
            // Register service worker
            await this.registerServiceWorker();
            
            // Check current subscription status
            const isSubscribed = await this.manager.isEnabled();
            
            // Update UI based on status
            this.updateAllButtons(isSubscribed ? 'subscribed' : 'ready');
            
            // Attach click handlers
            this.setupButtonHandlers();
            
        } catch (error) {
            console.error('Push notification initialization failed:', error);
            this.updateAllButtons('error');
        }
    }

    /**
     * Register service worker
     */
    async registerServiceWorker() {
        if (!('serviceWorker' in navigator)) {
            throw new Error('Service Worker not supported');
        }

        try {
            const registration = await navigator.serviceWorker.register(this.serviceWorkerPath, {
                scope: '/',
            });
            console.log('✓ Service Worker registered:', registration);
            return registration;
        } catch (error) {
            console.error('Service Worker registration failed:', error);
            throw error;
        }
    }

    /**
     * Setup click handlers for all subscribe buttons
     */
    setupButtonHandlers() {
        const buttons = document.querySelectorAll('[data-push-subscribe-btn], .push-subscribe-btn');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => this.handleSubscribeClick(e, button));
        });
    }

    /**
     * Handle subscribe button click
     */
    async handleSubscribeClick(e, button) {
        e.preventDefault();
        e.stopPropagation();

        if (!this.isSupported) {
            this.showError(button, 'আপনার ব্রাউজার সাপোর্ট করে না');
            return;
        }

        const isCurrentlySubscribed = button.dataset.subscribed === 'true';

        if (isCurrentlySubscribed) {
            // Unsubscribe
            await this.unsubscribe(button);
        } else {
            // Subscribe
            await this.subscribe(button);
        }
    }

    /**
     * Subscribe to push notifications
     */
    async subscribe(button) {
        try {
            this.setButtonLoading(button);

            // Request permission if needed
            if (Notification.permission === 'default') {
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    this.showError(button, 'অনুমতি প্রয়োজন');
                    return;
                }
            } else if (Notification.permission === 'denied') {
                this.showError(button, 'বিজ্ঞপ্তি অক্ষম করা হয়েছে');
                return;
            }

            // Subscribe using manager
            const result = await this.manager.subscribe();

            if (result.success) {
                this.setButtonSubscribed(button);
                this.showSuccess(button, 'সফলভাবে সাবস্ক্রাইব হয়েছেন!');
            } else {
                this.showError(button, result.message || 'সাবস্ক্রিপশন ব্যর্থ');
            }
        } catch (error) {
            console.error('Subscribe error:', error);
            this.showError(button, 'পুনরায় চেষ্টা করুন');
        }
    }

    /**
     * Unsubscribe from push notifications
     */
    async unsubscribe(button) {
        try {
            this.setButtonLoading(button);

            const result = await this.manager.unsubscribe();

            if (result.success) {
                this.setButtonUnsubscribed(button);
                this.showSuccess(button, 'সাবস্ক্রিপশন বাতিল করা হয়েছে');
            } else {
                this.showError(button, result.message || 'সাবস্ক্রিপশন বাতিল ব্যর্থ');
            }
        } catch (error) {
            console.error('Unsubscribe error:', error);
            this.showError(button, 'পুনরায় চেষ্টা করুন');
        }
    }

    /**
     * Update button UI - Loading state
     */
    setButtonLoading(button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> অপেক্ষা করছি...';
        button.dataset.loading = 'true';
    }

    /**
     * Update button UI - Subscribed state
     */
    setButtonSubscribed(button) {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-check-circle"></i> ✓ সক্ষম';
        button.style.background = '#4caf50';
        button.style.color = 'white';
        button.dataset.subscribed = 'true';
        button.dataset.loading = 'false';
    }

    /**
     * Update button UI - Unsubscribed state
     */
    setButtonUnsubscribed(button) {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-bell"></i> চালু করুন';
        button.style.background = 'white';
        button.style.color = '#667eea';
        button.dataset.subscribed = 'false';
        button.dataset.loading = 'false';
    }

    /**
     * Show success message
     */
    showSuccess(button, message) {
        this.showToast(message, 'success');
    }

    /**
     * Show error message
     */
    showError(button, message) {
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-bell"></i> চালু করুন';
        this.showToast(message, 'error');
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `push-notification-toast push-notification-toast-${type}`;
        toast.innerHTML = `
            <div class="push-notification-toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(toast);
        
        // Add animation
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    /**
     * Update all buttons to a specific state
     */
    updateAllButtons(state) {
        const buttons = document.querySelectorAll('[data-push-subscribe-btn], .push-subscribe-btn');
        buttons.forEach(button => {
            switch (state) {
                case 'subscribed':
                    this.setButtonSubscribed(button);
                    break;
                case 'disabled':
                    button.disabled = true;
                    button.innerHTML = '⛔ সাপোর্ট নেই';
                    break;
                case 'error':
                    button.disabled = true;
                    button.innerHTML = '⚠️ ত্রুটি';
                    break;
                case 'ready':
                default:
                    this.setButtonUnsubscribed(button);
                    break;
            }
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        const client = new PushNotificationClient();
        client.initialize().catch(console.error);
        window.pushNotificationClient = client;
    });
} else {
    const client = new PushNotificationClient();
    client.initialize().catch(console.error);
    window.pushNotificationClient = client;
}
