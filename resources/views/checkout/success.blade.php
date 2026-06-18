<x-app-layout>
    <div class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-xl">
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant p-lg md:p-xl text-center">
            
            <div class="w-20 h-20 bg-primary-container rounded-full flex items-center justify-center mx-auto mb-lg">
                <span class="material-symbols-outlined text-[40px] text-on-primary-container">check_circle</span>
            </div>
            
            <h1 class="text-headline-md font-headline-md text-on-surface mb-xs">Đặt hàng thành công!</h1>
            <p class="text-body-md text-on-surface-variant mb-xl">Cảm ơn bạn đã mua hàng. Đơn hàng của bạn đã được ghi nhận.</p>
            
            <div class="bg-surface-container-low rounded-xl p-md mb-xl text-left">
                <h3 class="text-label-md font-label-md text-on-surface mb-md">Chi tiết đơn hàng</h3>
                
                <div class="flex items-center gap-md border-b border-outline-variant pb-md mb-md">
                    @php $product = $order->items->first()->product; @endphp
                    <img src="{{ $product->primary_image_medium_url }}" alt="{{ $product->title }}" class="w-20 h-20 object-cover rounded-lg border border-outline-variant">
                    <div>
                        <h4 class="text-body-md font-bold text-on-surface line-clamp-1">{{ $product->title }}</h4>
                        <div class="text-primary font-bold mt-1">{{ $product->formatted_price }}</div>
                        <div class="text-body-sm text-on-surface-variant mt-1">Người bán: {{ $product->user->name }}</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4 text-body-sm">
                    <div>
                        <span class="text-on-surface-variant block mb-1">Mã đơn hàng:</span>
                        <span class="font-bold text-on-surface">#{{ $order->id }}</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block mb-1">Phương thức thanh toán:</span>
                        <span class="font-bold text-on-surface">{{ $order->payment_method->label() }}</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block mb-1">Người nhận:</span>
                        <span class="font-bold text-on-surface">{{ $order->shipping_name }} - {{ $order->shipping_phone }}</span>
                    </div>
                    <div>
                        <span class="text-on-surface-variant block mb-1">Địa chỉ giao hàng:</span>
                        <span class="font-bold text-on-surface">{{ $order->shipping_address }}</span>
                    </div>
                </div>
            </div>
            
            <div class="mb-xl text-left border border-primary-container bg-surface rounded-xl p-md flex gap-4">
                <span class="material-symbols-outlined text-primary text-[24px]">info</span>
                <div>
                    <h4 class="text-label-md font-label-md text-on-surface mb-1">Bước tiếp theo</h4>
                    <p class="text-body-sm text-on-surface-variant">Người bán sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận đơn hàng và trao đổi phương thức giao nhận.</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-md">
                <x-button variant="outlined" href="{{ route('home') }}" class="w-full sm:w-auto">
                    Tiếp tục mua sắm
                </x-button>
                <x-button variant="filled" href="{{ route('dashboard', ['tab' => 'orders']) }}" class="w-full sm:w-auto">
                    Xem đơn mua
                </x-button>
                <x-button variant="outlined" href="{{ route('chat.start', $product->id) }}" class="w-full sm:w-auto">
                    Chat với người bán
                </x-button>
            </div>
            
        </div>
    </div>
</x-app-layout>
