<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
        .footer { background: #f0f0f0; padding: 15px; text-align: center; font-size: 12px; color: #666; border-radius: 0 0 5px 5px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #333; display: block; margin-bottom: 5px; }
        .value { background: white; padding: 10px; border-radius: 3px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>নতুন যোগাযোগ বার্তা</h2>
            <p>Sajeb NEWS থেকে</p>
        </div>

        <div class="content">
            <div class="field">
                <span class="label">নাম:</span>
                <div class="value">{{ $data['name'] ?? 'N/A' }}</div>
            </div>

            <div class="field">
                <span class="label">ইমেইল:</span>
                <div class="value">{{ $data['email'] ?? 'N/A' }}</div>
            </div>

            @if(!empty($data['phone']))
            <div class="field">
                <span class="label">ফোন:</span>
                <div class="value">{{ $data['phone'] }}</div>
            </div>
            @endif

            <div class="field">
                <span class="label">বিষয়:</span>
                <div class="value">{{ $data['subject'] ?? 'N/A' }}</div>
            </div>

            <div class="field">
                <span class="label">বার্তা:</span>
                <div class="value" style="white-space: pre-wrap;">{{ $data['message'] ?? 'N/A' }}</div>
            </div>
        </div>

        <div class="footer">
            <p>এটি একটি স্বয়ংক্রিয় ইমেইল। অনুগ্রহ করে এতে সরাসরি উত্তর দিবেন না।</p>
            <p>&copy; {{ date('Y') }} Sajeb NEWS. সর্বাধিকার সংরক্ষিত।</p>
        </div>
    </div>
</body>
</html>
