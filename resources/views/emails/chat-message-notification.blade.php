<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Chat Message</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background-color: #f4f7fa; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); padding: 40px 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .notification-message { font-size: 18px; margin-bottom: 20px; color: #6f42c1; font-weight: 600; }
        .message-card { background: linear-gradient(135deg, #f8f9ff 0%, #fff5f8 100%); border: 1px solid #e9ecef; border-radius: 12px; padding: 25px; margin: 20px 0; }
        .sender-info { display: flex; align-items: center; margin-bottom: 20px; }
        .sender-avatar { width: 50px; height: 50px; background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; font-weight: bold; margin-right: 15px; }
        .sender-details h3 { margin: 0; color: #333; font-size: 18px; }
        .sender-details p { margin: 5px 0 0 0; color: #666; font-size: 14px; }
        .message-content { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #6f42c1; }
        .message-text { font-size: 16px; line-height: 1.6; color: #333; margin-bottom: 15px; }
        .message-meta { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #666; }
        .message-type { background: #f8f9fa; padding: 5px 10px; border-radius: 15px; font-size: 12px; color: #666; }
        .quick-reply { background-color: #f0f8ff; border: 1px solid #b3d9ff; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .reply-title { font-weight: 600; color: #0056b3; margin-bottom: 10px; }
        .chat-settings { background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .settings-title { font-weight: 600; color: #333; margin-bottom: 15px; }
        .setting-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #e9ecef; }
        .setting-item:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .setting-label { color: #666; }
        .setting-value { font-weight: 500; color: #333; }
        .cta-button { display: inline-block; background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%); color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: 600; margin: 20px 0; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; color: #666666; font-size: 14px; }
        .footer a { color: #6f42c1; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 New Message</h1>
        </div>

        <div class="content">
            <div class="notification-message">
                Hi {{ $receiver->full_name }}!
            </div>

            <p>You have received a new message in your Ed-Cademy chat. Stay connected with your students and educators!</p>

            <div class="message-card">
                <div class="sender-info">
                    <div class="sender-avatar">{{ substr($sender->first_name, 0, 1) }}{{ substr($sender->last_name, 0, 1) }}</div>
                    <div class="sender-details">
                        <h3>{{ $sender->full_name }}</h3>
                        <p>{{ $sender->isEducator() ? 'Educator' : 'Student' }}</p>
                    </div>
                </div>

                <div class="message-content">
                    @if($message->type === 'file')
                        <div class="message-text">
                            📎 <strong>File shared:</strong> {{ basename($message->file) }}
                        </div>
                    @else
                        <div class="message-text">
                            "{{ Str::limit($message->message, 200) }}"
                        </div>
                    @endif

                    <div class="message-meta">
                        <span>Sent {{ $message->created_at->diffForHumans() }}</span>
                        <span class="message-type">{{ ucfirst($message->type) }}</span>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('chat.index') }}?chat={{ $chat->id }}" class="cta-button">Reply to Message</a>
            </div>

            <div class="quick-reply">
                <div class="reply-title">💭 Quick Reply Options</div>
                <p>Need to respond quickly? Here are some common responses:</p>
                <ul>
                    <li>"Thank you for your message! I'll get back to you soon."</li>
                    <li>"I'm currently offline but will respond within 24 hours."</li>
                    <li>"Let me check that for you and get back to you."</li>
                </ul>
            </div>

            <div class="chat-settings">
                <div class="settings-title">⚙️ Chat Preferences</div>
                <div class="setting-item">
                    <span class="setting-label">Email Notifications:</span>
                    <span class="setting-value">Enabled</span>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Message Frequency:</span>
                    <span class="setting-value">Real-time</span>
                </div>
                <div class="setting-item">
                    <span class="setting-label">Notification Sound:</span>
                    <span class="setting-value">Enabled</span>
                </div>
            </div>

            <p><strong>💡 Chat Best Practices:</strong></p>
            <ul>
                <li>Respond to messages within 24 hours for best student experience</li>
                <li>Use clear, professional language in all communications</li>
                <li>Keep sensitive information discussions for scheduled sessions</li>
                <li>Save important details or agreements in writing</li>
            </ul>

            <p><strong>Need help with chat?</strong> Visit your dashboard to manage chat settings or contact support for assistance.</p>

            <p><strong>The Ed-Cademy Communication Team</strong></p>
        </div>

        <div class="footer">
            <p>
                This email was sent because you received a new chat message on Ed-Cademy.
            </p>
            <p>
                <a href="{{ route('web.privacy.policy') }}">Privacy Policy</a> |
                <a href="{{ route('web.terms.and.conditions') }}">Terms of Service</a> |
                <a href="{{ route('web.contact.us') }}">Contact Support</a>
            </p>
        </div>
    </div>
</body>
</html>