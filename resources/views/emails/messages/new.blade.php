<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bạn có tin nhắn mới</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f5; padding: 20px; }
        .container { max-w-xl; margin: 0 auto; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; }
        .message-box { background: #eef2ff; border-left: 4px solid #4f46e5; padding: 15px; margin-bottom: 20px; font-style: italic;}
        .btn { display: inline-block; padding: 12px 24px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; mt-30px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tin nhắn mới!</h1>
        </div>
        <div>
            <p>Xin chào,</p>
            <p>Bạn vừa nhận được một tin nhắn mới từ <strong>{{ $messageData->sender->name }}</strong> trên Amber Marketplace.</p>
            
            <div class="message-box">
                "{{ Str::limit($messageData->body, 100) }}"
            </div>

            <div style="text-align: center; margin-top: 30px; margin-bottom: 30px;">
                <a href="{{ url('/chat/' . $messageData->conversation_id) }}" class="btn">Trả lời ngay</a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Amber Marketplace. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
