<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="test">
    <meta name="vapid-public-key" content="{{ $vapidPublicKey }}">
    <title>পুশ নোটিফিকেশন টেস্ট</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Shurjo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 12px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        h1 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
            font-size: 24px;
        }

        .info-section {
            background: #f5f5f5;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #667eea;
        }

        .info-section h3 {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .info-section p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .status {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 6px;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        .status.error {
            background: #ffebee;
            color: #c62828;
        }

        .status.pending {
            background: #e3f2fd;
            color: #1565c0;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-subscribe {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-subscribe:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-subscribe:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .btn-unsubscribe {
            background: #f44336;
            color: white;
        }

        .btn-unsubscribe:hover {
            background: #d32f2f;
        }

        .btn-unsubscribe:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-check {
            background: #2196f3;
            color: white;
        }

        .btn-check:hover {
            background: #1976d2;
        }

        .logs {
            background: #fafafa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            max-height: 200px;
            overflow-y: auto;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            color: #333;
        }

        .logs .log-entry {
            padding: 5px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .logs .log-entry:last-child {
            border-bottom: none;
        }

        .log-time {
            color: #999;
            margin-right: 5px;
        }

        .log-success {
            color: #4caf50;
        }

        .log-error {
            color: #f44336;
        }

        .log-info {
            color: #2196f3;
        }

        hr {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-bell"></i> পুশ নোটিফিকেশন টেস্ট</h1>

        <div class="info-section">
            <h3>সিস্টেম স্ট্যাটাস</h3>
            <div id="browserSupport" class="status pending">
                <i class="fas fa-spinner fa-spin"></i>
                <span>পরীক্ষা করছি...</span>
            </div>
            <div id="vapidStatus" class="status pending">
                <i class="fas fa-spinner fa-spin"></i>
                <span>VAPID কী খুঁজছি...</span>
            </div>
            <div id="subscriptionStatus" class="status pending">
                <i class="fas fa-spinner fa-spin"></i>
                <span>সাবস্ক্রিপশন স্ট্যাটাস পরীক্ষা করছি...</span>
            </div>
        </div>

        <div class="button-group">
            <button class="btn-subscribe" id="subscribeBtn">
                <i class="fas fa-plus-circle"></i> <span id="subscribeBtnText">সাবস্ক্রাইব করুন</span>
            </button>
            <button class="btn-unsubscribe" id="unsubscribeBtn" disabled>
                <i class="fas fa-minus-circle"></i> বাতিল করুন
            </button>
        </div>

        <button class="btn-check" style="width: 100%; margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i> আরও তথ্য
        </button>

        <hr>

        <h3 style="margin-bottom: 10px; color: #333;">লগ</h3>
        <div class="logs" id="logs">
            <div class="log-entry log-info">
                <span class="log-time">[শুরু]</span>
                সিস্টেম শুরু হচ্ছে...
            </div>
        </div>
    </div>

    <!-- Push Notification Scripts -->
    <script src="/js/push-notification-manager.js"></script>
    <script>
        class TestManager {
            constructor() {
                this.logs = [];
                this.manager = new PushNotificationManager();
                this.init();
            }

            init() {
                this.log('সিস্টেম শুরু হচ্ছে...', 'info');
                this.updateStatuses();
                this.setupEventListeners();
            }

            log(message, type = 'info') {
                const time = new Date().toLocaleTimeString('bn-BD');
                this.logs.push({ message, type, time });
                this.updateLogs();
                console.log(`[${type.toUpperCase()}] ${message}`);
            }

            updateLogs() {
                const logsEl = document.getElementById('logs');
                logsEl.innerHTML = this.logs.map(log => `
                    <div class="log-entry log-${log.type}">
                        <span class="log-time">[${log.time}]</span>
                        ${log.message}
                    </div>
                `).join('');
                logsEl.scrollTop = logsEl.scrollHeight;
            }

            async updateStatuses() {
                // Browser support
                const isSupported = 'serviceWorker' in navigator && 
                                   'PushManager' in window && 
                                   'Notification' in window;
                const browserEl = document.getElementById('browserSupport');
                if (isSupported) {
                    browserEl.className = 'status';
                    browserEl.innerHTML = '<i class="fas fa-check-circle"></i> <span>ব্রাউজার সাপোর্ট করে</span>';
                    this.log('✓ ব্রাউজার সাপোর্ট করে', 'success');
                } else {
                    browserEl.className = 'status error';
                    browserEl.innerHTML = '<i class="fas fa-times-circle"></i> <span>ব্রাউজার সাপোর্ট করে না</span>';
                    this.log('✗ ব্রাউজার সাপোর্ট করে না', 'error');
                    return;
                }

                // VAPID key
                const vapidKey = document.head.querySelector('meta[name="vapid-public-key"]')?.content;
                const vapidEl = document.getElementById('vapidStatus');
                if (vapidKey) {
                    vapidEl.className = 'status';
                    vapidEl.innerHTML = '<i class="fas fa-check-circle"></i> <span>VAPID কী সেট করা আছে</span>';
                    this.log('✓ VAPID কী মিলেছে', 'success');
                } else {
                    vapidEl.className = 'status error';
                    vapidEl.innerHTML = '<i class="fas fa-times-circle"></i> <span>VAPID কী নেই</span>';
                    this.log('✗ VAPID কী সেট করা নেই', 'error');
                }

                // Subscription status
                try {
                    const isEnabled = await this.manager.isEnabled();
                    const subEl = document.getElementById('subscriptionStatus');
                    if (isEnabled) {
                        subEl.className = 'status';
                        subEl.innerHTML = '<i class="fas fa-check-circle"></i> <span>সক্রিয় সাবস্ক্রিপশন আছে</span>';
                        this.log('✓ সক্রিয় সাবস্ক্রিপশন আছে', 'success');
                        this.setSubscribed(true);
                    } else {
                        subEl.className = 'status pending';
                        subEl.innerHTML = '<i class="fas fa-circle"></i> <span>সাবস্ক্রাইব করা নেই</span>';
                        this.log('সাবস্ক্রাইব করা নেই', 'info');
                        this.setSubscribed(false);
                    }
                } catch (error) {
                    this.log(`স্ট্যাটাস চেক ব্যর্থ: ${error.message}`, 'error');
                }
            }

            setSubscribed(subscribed) {
                const subBtn = document.getElementById('subscribeBtn');
                const unsubBtn = document.getElementById('unsubscribeBtn');
                
                if (subscribed) {
                    subBtn.disabled = true;
                    unsubBtn.disabled = false;
                } else {
                    subBtn.disabled = false;
                    unsubBtn.disabled = true;
                }
            }

            setupEventListeners() {
                document.getElementById('subscribeBtn').addEventListener('click', () => this.subscribe());
                document.getElementById('unsubscribeBtn').addEventListener('click', () => this.unsubscribe());
            }

            async subscribe() {
                this.log('সাবস্ক্রাইব শুরু...', 'info');
                const btn = document.getElementById('subscribeBtn');
                btn.disabled = true;

                try {
                    if (Notification.permission === 'default') {
                        this.log('নোটিফিকেশন অনুমতি চাওয়া হচ্ছে...', 'info');
                        const permission = await Notification.requestPermission();
                        if (permission !== 'granted') {
                            this.log('নোটিফিকেশন অনুমতি অস্বীকৃত', 'error');
                            btn.disabled = false;
                            return;
                        }
                        this.log('✓ নোটিফিকেশন অনুমতি প্রদান করা হয়েছে', 'success');
                    }

                    const result = await this.manager.subscribe();
                    if (result.success) {
                        this.log('✓ সফলভাবে সাবস্ক্রাইব হয়েছেন', 'success');
                        this.setSubscribed(true);
                    } else {
                        this.log(`সাবস্ক্রাইব ব্যর্থ: ${result.message}`, 'error');
                        btn.disabled = false;
                    }
                } catch (error) {
                    this.log(`ত্রুটি: ${error.message}`, 'error');
                    btn.disabled = false;
                }
            }

            async unsubscribe() {
                this.log('সাবস্ক্রিপশন বাতিল শুরু...', 'info');
                const btn = document.getElementById('unsubscribeBtn');
                btn.disabled = true;

                try {
                    const result = await this.manager.unsubscribe();
                    if (result.success) {
                        this.log('✓ সাবস্ক্রিপশন বাতিল হয়েছে', 'success');
                        this.setSubscribed(false);
                    } else {
                        this.log(`বাতিল ব্যর্থ: ${result.message}`, 'error');
                        btn.disabled = false;
                    }
                } catch (error) {
                    this.log(`ত্রুটি: ${error.message}`, 'error');
                    btn.disabled = false;
                }
            }
        }

        // Start test
        window.testManager = new TestManager();
    </script>
</body>
</html>
