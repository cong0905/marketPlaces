<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;

class VNPayService
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $url;

    public function __construct()
    {
        $this->tmnCode = config('services.vnpay.tmn_code', '');
        $this->hashSecret = config('services.vnpay.hash_secret', '');
        $this->url = config('services.vnpay.url', '');
    }

    /**
     * Tạo URL thanh toán VNPay
     */
    public function generatePaymentUrl(Order $order, Request $request): string
    {
        $vnp_Returnurl = route('payment.vnpay.return');
        $vnp_TmnCode = $this->tmnCode;
        $vnp_HashSecret = $this->hashSecret;
        $vnp_Url = $this->url;
        
        $vnp_TxnRef = $order->id . '_' . time(); // Mã giao dịch duy nhất
        $vnp_OrderInfo = "Thanh toan don hang " . $order->id;
        $vnp_OrderType = 'other';
        $vnp_Amount = $order->total_amount * 100; // VNPay nhân 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = ''; // Để trống để người dùng tự chọn trên cổng
        $vnp_IpAddr = $request->ip();

        // Lưu txn_ref vào order payment để sau đối soát
        $payment = $order->payments()->where('method', 'vnpay')->first();
        if ($payment) {
            $payment->update(['transaction_id' => $vnp_TxnRef]);
        }

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return $vnp_Url;
    }

    /**
     * Xác thực dữ liệu trả về từ VNPay
     */
    public function verifyReturn(Request $request): array
    {
        $vnp_SecureHash = $request->vnp_SecureHash;
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']); // Có thể có ở một số version

        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);
        
        if ($secureHash === $vnp_SecureHash) {
            return [
                'isValid' => true,
                'isSuccess' => $request->vnp_ResponseCode == '00',
                'orderId' => explode('_', $request->vnp_TxnRef)[0],
                'transactionId' => $request->vnp_TxnRef,
                'vnpayTranId' => $request->vnp_TransactionNo
            ];
        }

        return [
            'isValid' => false,
            'isSuccess' => false,
            'orderId' => null
        ];
    }
}
