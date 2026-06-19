@props(['order'])

@if($order->status->value === 'cancelled')
    <div class="px-md py-sm border-b border-outline-variant bg-error-container/10 flex items-center justify-center gap-2">
        <span class="material-symbols-outlined text-error">cancel</span>
        <span class="text-error font-bold text-label-md">Đơn hàng đã bị hủy</span>
    </div>
@else
    <div class="px-md py-lg border-b border-outline-variant bg-surface-container-lowest">
        <div class="flex items-center justify-between max-w-xl mx-auto relative">
            <!-- Connecting Line -->
            <div class="absolute top-4 left-0 right-0 h-1 bg-surface-container-high -translate-y-1/2 z-0"></div>
            <div class="absolute top-4 left-0 h-1 bg-primary -translate-y-1/2 z-0 transition-all duration-500" 
                style="width: {{ $order->status->value === 'pending' ? '0%' : ($order->status->value === 'confirmed' ? '33%' : ($order->status->value === 'shipping' ? '66%' : '100%')) }};">
            </div>

            <!-- Steps -->
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ in_array($order->status->value, ['pending', 'confirmed', 'shipping', 'completed']) ? 'bg-primary border-primary text-on-primary shadow-sm' : 'bg-surface border-outline-variant text-outline-variant' }}">
                    <span class="material-symbols-outlined text-[16px]">inventory_2</span>
                </div>
                <span class="text-[11px] uppercase tracking-wider font-bold {{ in_array($order->status->value, ['pending', 'confirmed', 'shipping', 'completed']) ? 'text-primary' : 'text-outline' }}">Chờ duyệt</span>
            </div>

            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ in_array($order->status->value, ['confirmed', 'shipping', 'completed']) ? 'bg-primary border-primary text-on-primary shadow-sm' : 'bg-surface border-outline-variant text-outline-variant' }}">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                </div>
                <span class="text-[11px] uppercase tracking-wider font-bold {{ in_array($order->status->value, ['confirmed', 'shipping', 'completed']) ? 'text-primary' : 'text-outline' }}">Xác nhận</span>
            </div>

            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ in_array($order->status->value, ['shipping', 'completed']) ? 'bg-primary border-primary text-on-primary shadow-sm' : 'bg-surface border-outline-variant text-outline-variant' }}">
                    <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                </div>
                <span class="text-[11px] uppercase tracking-wider font-bold {{ in_array($order->status->value, ['shipping', 'completed']) ? 'text-primary' : 'text-outline' }}">Đang giao</span>
            </div>

            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center border-2 {{ $order->status->value === 'completed' ? 'bg-emerald-500 border-emerald-500 text-white shadow-sm' : 'bg-surface border-outline-variant text-outline-variant' }}">
                    <span class="material-symbols-outlined text-[16px]">task_alt</span>
                </div>
                <span class="text-[11px] uppercase tracking-wider font-bold {{ $order->status->value === 'completed' ? 'text-emerald-600' : 'text-outline' }}">Thành công</span>
            </div>
        </div>
    </div>
@endif
