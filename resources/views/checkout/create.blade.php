<x-app-layout>
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-xl" x-data="{ paymentMethod: 'cod' }">
        
        <div class="mb-lg flex flex-col gap-xs">
            <h1 class="text-headline-lg font-headline-lg text-on-surface">Thanh Toán & Đặt Hàng</h1>
            <p class="text-body-md text-on-surface-variant">Vui lòng kiểm tra lại thông tin trước khi đặt mua.</p>
        </div>

        @if($errors->any())
            <div class="mb-lg bg-error-container border-l-4 border-error p-md rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="material-symbols-outlined text-error">error</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-label-md font-label-md text-on-error-container">Vui lòng kiểm tra lại:</h3>
                        <ul class="mt-2 text-body-sm text-on-error-container list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.store', $product->id) }}" method="POST">
            @csrf
            
            <div class="flex flex-col lg:flex-row gap-xl">
                <!-- Left: Shipping & Payment -->
                <div class="lg:w-2/3 flex flex-col gap-lg">
                    
                    <!-- Shipping Address -->
                    <div class="bg-surface-container-lowest rounded-xl p-lg md:p-xl shadow-sm border border-outline-variant">
                        <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md">1. Thông tin giao hàng</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-xs">Họ và tên người nhận</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md">
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-xs">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md">
                            </div>
                            <div class="col-span-full">
                                <label class="block text-label-md font-label-md text-on-surface mb-xs">Địa chỉ chi tiết (Số nhà, đường, phường/xã, quận/huyện...)</label>
                                <textarea name="address" rows="3" required class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md">{{ old('address') }}</textarea>
                            </div>
                            <div class="col-span-full">
                                <label class="block text-label-md font-label-md text-on-surface mb-xs">Ghi chú cho người bán (Tùy chọn)</label>
                                <input type="text" name="note" value="{{ old('note') }}" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md">
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-surface-container-lowest rounded-xl p-lg md:p-xl shadow-sm border border-outline-variant">
                        <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md">2. Phương thức thanh toán</h2>
                        <div class="flex flex-col gap-sm">
                            <!-- COD -->
                            <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all" :class="paymentMethod === 'cod' ? 'border-primary bg-primary-container/10' : 'border-outline-variant hover:bg-surface-container-low'">
                                <div class="flex items-center gap-md">
                                    <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="w-5 h-5 text-primary focus:ring-primary border-outline">
                                    <div>
                                        <div class="text-label-md font-label-md text-on-surface">Thanh toán khi nhận hàng (COD)</div>
                                        <div class="text-body-sm text-on-surface-variant">Kiểm tra hàng trước khi thanh toán.</div>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-[32px] text-outline">local_shipping</span>
                            </label>

                            <!-- VNPay -->
                            <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all" :class="paymentMethod === 'vnpay' ? 'border-primary bg-primary-container/10' : 'border-outline-variant hover:bg-surface-container-low'">
                                <div class="flex items-center gap-md">
                                    <input type="radio" name="payment_method" value="vnpay" x-model="paymentMethod" class="w-5 h-5 text-primary focus:ring-primary border-outline">
                                    <div>
                                        <div class="text-label-md font-label-md text-on-surface">Thanh toán trực tuyến (VNPay)</div>
                                        <div class="text-body-sm text-on-surface-variant">Thanh toán an toàn qua Thẻ ATM, Visa, VNPAY-QR.</div>
                                    </div>
                                </div>
                                <div class="flex gap-1">
                                    <div class="w-8 h-8 bg-blue-100 rounded text-blue-800 text-[10px] font-bold flex items-center justify-center">VN</div>
                                    <div class="w-8 h-8 bg-red-100 rounded text-red-800 text-[10px] font-bold flex items-center justify-center">PAY</div>
                                </div>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Right: Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-surface-container-lowest rounded-xl p-lg shadow-sm border border-outline-variant sticky top-28">
                        <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md">Thông tin sản phẩm</h2>
                        
                        <div class="flex gap-md pb-md border-b border-outline-variant">
                            <img src="{{ $product->primary_image_medium_url }}" loading="lazy" alt="" class="w-20 h-20 object-cover rounded-lg border border-outline-variant">
                            <div class="flex flex-col justify-center">
                                <h3 class="text-body-md font-body-md text-on-surface line-clamp-2">{{ $product->title }}</h3>
                                <p class="text-label-md font-label-md text-primary mt-1">{{ $product->formatted_price }}</p>
                            </div>
                        </div>

                        <div class="py-md flex flex-col gap-2 border-b border-outline-variant">
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="text-on-surface-variant">Người bán:</span>
                                <span class="font-bold text-on-surface">{{ $product->user->name }}</span>
                            </div>
                            <div class="flex justify-between items-center text-body-sm">
                                <span class="text-on-surface-variant">Phí vận chuyển:</span>
                                <span class="text-[#006d39] font-bold">Thỏa thuận</span>
                            </div>
                        </div>

                        <div class="py-md flex justify-between items-center">
                            <span class="text-label-md font-label-md text-on-surface">Tổng cộng:</span>
                            <span class="text-price-lg font-price-lg text-primary">{{ $product->formatted_price }}</span>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-primary hover:opacity-90 text-on-primary text-label-md font-label-md rounded-full shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none transition-all flex justify-center items-center gap-xs mt-xs">
                            Xác nhận Đặt Mua
                            <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
