<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f5; padding: 20px; }
        .container { max-w-xl; margin: 0 auto; background: #fff; border-radius: 8px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { text-align: center; border-bottom: 2px solid #f59e0b; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #f59e0b; margin: 0; }
        .content { margin-bottom: 30px; }
        .order-details { background: #fdf6e3; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background: #f59e0b; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .footer { text-align: center; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Amber Marketplace</h1>
        </div>
        <div class="content">
            @if($recipientType === 'buyer')
                <h2>Cảm ơn bạn đã đặt hàng!</h2>
                <p>Xin chào {{ $order->buyer->name }},</p>
                <p>Đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn đã được ghi nhận thành công và đang chờ người bán xác nhận.</p>
            @else
                <h2>Bạn có đơn hàng mới!</h2>
                <p>Xin chào {{ $order->seller->name }},</p>
                <p>Khách hàng <strong>{{ $order->buyer->name }}</strong> vừa đặt mua một sản phẩm của bạn. Mã đơn: <strong>#{{ $order->order_number }}</strong>.</p>
            @endif

            <div class="order-details">
                <p><strong>Tổng thanh toán:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} ₫</p>
                <p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
                <p><strong>Người nhận:</strong> {{ $order->shipping_name }} - {{ $order->shipping_phone }}</p>
            </div>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/dashboard') }}" class="btn">Xem chi tiết đơn hàng</a>
            </div>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Amber Marketplace. Mọi quyền được bảo lưu.</p>
        </div>
    </div>
</body>
</html>
