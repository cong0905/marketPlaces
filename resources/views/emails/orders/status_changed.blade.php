<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật Đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f5; padding: 20px; }
        .container { max-w-xl; margin: 0 auto; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #10b981; margin: 0; }
        .status-box { background: #ecfdf5; border: 1px solid #10b981; padding: 15px; border-radius: 6px; text-align: center; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background: #10b981; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; mt-30px;}
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Amber Marketplace</h1>
        </div>
        <div>
            <h2>Trạng thái đơn hàng của bạn đã thay đổi!</h2>
            <p>Xin chào {{ $order->buyer->name }},</p>
            <p>Đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn vừa được cập nhật trạng thái mới.</p>
            
            <div class="status-box">
                <h3 style="margin: 0; color: #047857;">Trạng thái hiện tại: {{ $order->status->label() }}</h3>
            </div>

            <p>Cảm ơn bạn đã tin tưởng mua sắm trên nền tảng của chúng tôi!</p>

            <div style="text-align: center; margin-top: 30px; margin-bottom: 30px;">
                <a href="{{ url('/dashboard?tab=purchases') }}" class="btn">Kiểm tra lộ trình đơn hàng</a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Amber Marketplace. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
