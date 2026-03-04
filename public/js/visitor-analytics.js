/**
 * Visitor Analytics Tracking
 * Tracks user engagement metrics and sends them to the server
 */

class VisitorAnalytics {
    constructor(newsId) {
        this.newsId = newsId;
        this.startTime = Date.now();
        this.lastScrollPercentage = 0;
        this.completedReading = false;
        this.trackingData = {
            time_spent: 0,
            scroll_percentage: 0,
            completed_reading: false,
            screen_resolution: `${window.screen.width}x${window.screen.height}`
        };

        this.init();
    }

    init() {
        // Track scroll depth
        window.addEventListener('scroll', () => this.trackScroll());

        // Track page unload
        window.addEventListener('beforeunload', () => this.trackVisit());
        window.addEventListener('unload', () => this.trackVisit());

        // Track exit intents (for desktop)
        document.addEventListener('mouseleave', () => this.trackVisit());

        // Track exit on page blur
        window.addEventListener('blur', () => this.trackVisit());

        // Periodic tracking (every 30 seconds)
        this.trackingInterval = setInterval(() => this.trackVisit(), 30000);

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (this.trackingInterval) {
                clearInterval(this.trackingInterval);
            }
        });
    }

    trackScroll() {
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const scrollTop = window.scrollY || document.documentElement.scrollTop;

        // Calculate scroll percentage
        const totalScrollableHeight = documentHeight - windowHeight;
        const scrollPercentage = totalScrollableHeight > 0 
            ? Math.round((scrollTop + windowHeight) / documentHeight * 100) 
            : 0;

        this.trackingData.scroll_percentage = Math.min(scrollPercentage, 100);

        // Mark as completed reading if user scrolled 80% and spent at least 10 seconds
        if (scrollPercentage >= 80 && (Date.now() - this.startTime) >= 10000) {
            this.completedReading = true;
            this.trackingData.completed_reading = true;
        }
    }

    trackVisit() {
        // Prevent duplicate tracking
        if (this.tracked) {
            return;
        }

        // Calculate time spent in seconds
        this.trackingData.time_spent = Math.round((Date.now() - this.startTime) / 1000);

        // Minimum 3 seconds to track
        if (this.trackingData.time_spent < 3) {
            return;
        }

        // Mark as tracked to prevent duplicate requests
        this.tracked = true;

        // Clear interval to prevent duplicate tracking
        if (this.trackingInterval) {
            clearInterval(this.trackingInterval);
        }

        // Send tracking data to server
        this.sendTrackingData();
    }

    sendTrackingData() {
        const url = '/api/tracking/visitor';
        const data = {
            news_id: this.newsId,
            time_spent: this.trackingData.time_spent,
            scroll_percentage: this.trackingData.scroll_percentage,
            completed_reading: this.trackingData.completed_reading,
            screen_resolution: this.trackingData.screen_resolution
        };

        // Use sendBeacon for more reliable delivery on page unload
        try {
            if (navigator.sendBeacon) {
                const formData = new FormData();
                Object.keys(data).forEach(key => {
                    formData.append(key, data[key]);
                });
                // SendBeacon sends as form data
                navigator.sendBeacon(url, formData);
            } else {
                // Fallback to fetch with keepalive
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.getCsrfToken(),
                    },
                    body: JSON.stringify(data),
                    keepalive: true
                }).catch(error => console.debug('Tracking sent (error silent)', error));
            }
        } catch (error) {
            console.debug('Visitor tracking completed');
        }
    }

    getCsrfToken() {
        // Get CSRF token from meta tag or document
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }
}

// Initialize tracking when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get news ID from data attribute or URL
    const newsId = document.querySelector('[data-news-id]')?.getAttribute('data-news-id') 
                || getNewsIdFromUrl();

    if (newsId) {
        window.visitorAnalytics = new VisitorAnalytics(newsId);
    }
});

function getNewsIdFromUrl() {
    // Try to extract from various URL patterns
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get('news_id') || getNewsIdFromPath();
}

function getNewsIdFromPath() {
    // You can customize this based on your URL structure
    const pathParts = window.location.pathname.split('/');
    // If news ID is stored in a data attribute on body or article element
    const article = document.querySelector('article[data-news-id]');
    return article ? article.getAttribute('data-news-id') : null;
}
