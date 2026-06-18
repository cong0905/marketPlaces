<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Services\VNPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        protected VNPayService $vnpayService
    ) {}

    /**
     * Xử lý URL Return sau khi khách hàng thanh toán trên VNPay
     */
    public function vnpayReturn(Request $request)
    {
        $result = $this->vnpayService->verifyReturn($request);

        if (!$result['isValid']) {
            return redirect()->route('home')->with('error', 'Chữ ký không hợp lệ! Giao dịch có thể đã bị can thiệp.');
        }

        $order = Order::find($result['orderId']);
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Không tìm thấy đơn hàng tương ứng.');
        }

        if ($result['isSuccess']) {
            // Update payment record
            $payment = $order->payments()->where('transaction_id', $result['transactionId'])->first();
            if ($payment && $payment->status->value === 'pending') {
                $payment->update([
                    'status' => PaymentStatus::SUCCESS,
                    'gateway_response' => json_encode($request->all())
                ]);
            }
            
            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Thanh toán trực tuyến VNPay thành công!');
        } else {
            // Thanh toán thất bại hoặc bị hủy
            $payment = $order->payments()->where('transaction_id', $result['transactionId'])->first();
            if ($payment && $payment->status->value === 'pending') {
                $payment->update([
                    'status' => PaymentStatus::FAILED,
                    'gateway_response' => json_encode($request->all())
                ]);
            }
            
            return redirect()->route('checkout.success', $order->id)
                ->with('error', 'Thanh toán đã bị hủy hoặc thất bại. Vui lòng thanh toán lại qua thẻ ngân hàng hoặc chọn phương thức COD.');
        }
    }

    /**
     * Xử lý IPN (Server-to-Server)
     */
    public function vnpayIpn(Request $request)
    {
        try {
            $result = $this->vnpayService->verifyReturn($request);
            
            if (!$result['isValid']) {
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            $order = Order::find($result['orderId']);
            if (!$order) {
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            $payment = $order->payments()->where('transaction_id', $result['transactionId'])->first();
            if (!$payment) {
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            // Check if payment amount matches (vnp_Amount / 100)
            $vnpAmount = $request->vnp_Amount / 100;
            if ($payment->amount != $vnpAmount) {
                return response()->json(['RspCode' => '04', 'Message' => 'invalid amount']);
            }

            // Check if already updated
            if ($payment->status->value !== 'pending') {
                return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
            }

            // Update status
            if ($result['isSuccess']) {
                $payment->update([
                    'status' => PaymentStatus::SUCCESS,
                    'gateway_response' => json_encode($request->all())
                ]);
            } else {
                $payment->update([
                    'status' => PaymentStatus::FAILED,
                    'gateway_response' => json_encode($request->all())
                ]);
            }

            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);

        } catch (\Exception $e) {
            Log::error('VNPay IPN Error: ' . $e->getMessage());
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
        }
    }
}
