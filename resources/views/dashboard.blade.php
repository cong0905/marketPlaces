<x-app-layout>
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-xl">
        <h1 class="text-headline-lg font-headline-lg text-on-surface mb-lg">Quản lý cá nhân</h1>

        @if(session('success'))
            <div class="mb-lg bg-surface-container border-l-4 border-[#006d39] p-md rounded-r-lg">
                <p class="text-body-sm font-body-sm font-bold text-[#006d39]">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Tabs -->
        <div class="bg-surface rounded-premium shadow-premium border border-outline-variant overflow-hidden mb-lg">
            <div class="border-b border-outline-variant">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <a href="{{ route('dashboard', ['tab' => 'products']) }}" class="w-1/4 py-4 px-1 text-center border-b-2 font-label-md text-label-md transition-colors {{ $tab === 'products' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline' }}">
                        Tin Đang Bán
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'purchases']) }}" class="w-1/4 py-4 px-1 text-center border-b-2 font-label-md text-label-md transition-colors {{ $tab === 'purchases' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline' }}">
                        Đơn Mua
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'sales']) }}" class="w-1/4 py-4 px-1 text-center border-b-2 font-label-md text-label-md transition-colors {{ $tab === 'sales' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline' }}">
                        Đơn Bán
                    </a>
                    <a href="{{ route('dashboard', ['tab' => 'favorites']) }}" class="w-1/4 py-4 px-1 text-center border-b-2 font-label-md text-label-md transition-colors {{ $tab === 'favorites' ? 'border-primary text-primary' : 'border-transparent text-on-surface-variant hover:text-on-surface hover:border-outline' }}">
                        <span class="material-symbols-outlined text-[16px] align-text-bottom mr-0.5" style="font-variation-settings: 'FILL' 1;">favorite</span>
                        Yêu thích
                    </a>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="bg-surface rounded-premium shadow-premium border border-outline-variant p-lg md:p-xl">
            
            @if($tab === 'products')
                <!-- My Products -->
                <div class="flex justify-between items-center mb-lg">
                    <h2 class="text-headline-sm font-headline-sm text-on-surface">Danh sách tin đăng của bạn</h2>
                    <a href="{{ route('products.create') }}" class="px-6 py-3 bg-primary text-on-primary rounded-full text-label-md font-label-md hover:opacity-90 active:-translate-y-px transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Đăng tin mới
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-outline-variant">
                                <th class="pb-3 text-label-md font-label-md text-on-surface-variant">Sản phẩm</th>
                                <th class="pb-3 text-label-md font-label-md text-on-surface-variant">Giá</th>
                                <th class="pb-3 text-label-md font-label-md text-on-surface-variant">Trạng thái</th>
                                <th class="pb-3 text-label-md font-label-md text-on-surface-variant">Ngày đăng</th>
                                <th class="pb-3 text-label-md font-label-md text-on-surface-variant text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse($myProducts as $item)
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="py-md flex gap-md items-center">
                                        <img src="{{ $item->primary_image_medium_url }}" alt="" class="w-12 h-12 object-cover rounded border border-outline-variant">
                                        <div>
                                            <a href="{{ route('products.show', $item->slug) }}" class="text-body-md font-body-md text-on-surface hover:text-primary line-clamp-1 max-w-[300px]">{{ $item->title }}</a>
                                            <span class="text-body-sm font-body-sm text-on-surface-variant">{{ $item->category->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-md text-on-surface font-bold">{{ $item->formatted_price }}</td>
                                    <td class="py-md">
                                        <span class="px-2 py-1 text-[11px] uppercase tracking-wider font-bold rounded-full bg-surface-container text-on-surface-variant border border-outline-variant">
                                            {{ $item->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-md text-body-sm text-on-surface-variant">{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="py-md text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('products.edit', $item->id) }}" class="text-on-surface-variant hover:text-primary transition-colors" title="Sửa">
                                                <span class="material-symbols-outlined text-[20px]">edit</span>
                                            </a>
                                            <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin này?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-on-surface-variant hover:text-error transition-colors" title="Xóa">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-xl text-center text-on-surface-variant text-body-md">Bạn chưa đăng tin nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-lg">{{ $myProducts->appends(['tab' => 'products'])->links() }}</div>

            @elseif($tab === 'purchases')
                <!-- My Purchases -->
                <h2 class="text-headline-sm font-headline-sm text-on-surface mb-lg">Lịch sử mua hàng</h2>
                <div class="space-y-md">
                    @forelse($purchases as $order)
                        <div class="border border-outline-variant rounded-xl overflow-hidden bg-surface-container-lowest">
                            <div class="bg-surface-container-low px-md py-sm flex justify-between items-center border-b border-outline-variant">
                                <div class="text-body-sm font-body-sm text-on-surface-variant">
                                    <span class="mr-2">Mã đơn: <span class="font-bold text-on-surface">#{{ $order->order_number }}</span></span>
                                    <span>Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <span class="px-2 py-1 text-[11px] uppercase tracking-wider font-bold rounded-full bg-surface-container text-on-surface-variant border border-outline-variant">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                            <x-order-timeline :order="$order" />
                            <div class="p-md flex gap-md items-start">
                                @php $product = $order->items->first()->product; @endphp
                                <img src="{{ $product->primary_image_medium_url }}" alt="" class="w-20 h-20 object-cover rounded border border-outline-variant">
                                <div class="flex-1">
                                    <h3 class="text-body-md font-body-md text-on-surface">{{ $product->title }}</h3>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant mt-1">Người bán: {{ $order->seller->name }}</p>
                                </div>
                                <div class="flex flex-col gap-2 min-w-[120px] items-end" x-data="{ reviewModal: false }">
                                    @if(in_array($order->status->value, ['confirmed', 'shipping']))
                                        <form action="{{ route('orders.complete', $order->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="px-4 py-2 bg-emerald-500 text-white text-label-md font-label-md rounded-full hover:opacity-90 active:-translate-y-px transition-all">
                                                Đã nhận hàng
                                            </button>
                                        </form>
                                    @elseif($order->status->value === 'completed')
                                        @if(!$order->review)
                                            <button @click="reviewModal = true" class="px-4 py-2 bg-primary text-on-primary text-label-md font-label-md rounded-full hover:opacity-90 active:-translate-y-px transition-all flex items-center gap-1">
                                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                                Đánh giá
                                            </button>

                                            <!-- Review Modal -->
                                            <div x-show="reviewModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                                    <div x-show="reviewModal" @click="reviewModal = false" class="fixed inset-0 bg-surface-dim bg-opacity-75 transition-opacity"></div>
                                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                                    <div class="inline-block align-bottom bg-surface-container-lowest rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-outline-variant">
                                                        <form action="{{ route('reviews.store', $order->id) }}" method="POST">
                                                            @csrf
                                                            <div class="px-md pt-md pb-md">
                                                                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-md">Đánh giá người bán: {{ $order->seller->name }}</h3>
                                                                
                                                                <div class="mb-md">
                                                                    <label class="block text-label-md font-label-md text-on-surface mb-sm">Chất lượng điểm số</label>
                                                                    <div class="flex items-center gap-md">
                                                                        @for($i=1; $i<=5; $i++)
                                                                            <label class="flex flex-col items-center cursor-pointer group">
                                                                                <input type="radio" name="rating" value="{{ $i }}" {{ $i==5 ? 'checked' : '' }} class="text-primary-container focus:ring-primary-container">
                                                                                <span class="text-body-sm font-body-sm mt-1 flex items-center group-hover:text-primary-container">
                                                                                    {{ $i }} <span class="material-symbols-outlined text-[16px] text-primary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span>
                                                                                </span>
                                                                            </label>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <label class="block text-label-md font-label-md text-on-surface mb-sm">Nhận xét của bạn</label>
                                                                    <textarea name="comment" rows="3" required class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary text-body-sm" placeholder="Chia sẻ trải nghiệm mua hàng của bạn..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="bg-surface-container px-md py-sm sm:flex sm:flex-row-reverse gap-sm">
                                                                <button type="submit" class="w-full inline-flex justify-center rounded-full bg-primary px-6 py-2 text-label-md font-label-md text-on-primary hover:opacity-90 active:-translate-y-px sm:w-auto">Gửi đánh giá</button>
                                                                <button type="button" @click="reviewModal = false" class="mt-3 w-full inline-flex justify-center rounded-full bg-surface border border-outline px-6 py-2 text-label-md font-label-md text-on-surface hover:bg-surface-container-low sm:mt-0 sm:w-auto">Hủy</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-body-sm font-body-sm font-bold text-[#006d39] flex items-center">Đã đánh giá <span class="material-symbols-outlined text-[16px] ml-1 text-primary-fixed-dim" style="font-variation-settings: 'FILL' 1;">star</span>{{ $order->review->rating }}</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-xl text-center text-on-surface-variant text-body-md border border-dashed border-outline-variant rounded-xl bg-surface">Bạn chưa có đơn mua nào.</div>
                    @endforelse
                </div>
                <div class="mt-lg">{{ $purchases->appends(['tab' => 'purchases'])->links() }}</div>

            @elseif($tab === 'sales')
                <!-- My Sales -->
                <h2 class="text-headline-sm font-headline-sm text-on-surface mb-lg">Kênh Người Bán: Quản lý Đơn hàng</h2>
                
                <!-- Seller Statistics -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-lg">
                    <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary-container rounded-full flex items-center justify-center text-on-primary-container">
                            <span class="material-symbols-outlined text-[24px]">account_balance_wallet</span>
                        </div>
                        <div>
                            <p class="text-body-sm text-on-surface-variant font-medium">Tổng Doanh Thu</p>
                            <p class="text-headline-sm font-bold text-primary">{{ isset($totalRevenue) ? number_format($totalRevenue, 0, ',', '.') : 0 }} ₫</p>
                        </div>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex items-center gap-4">
                        <div class="w-12 h-12 bg-secondary-container rounded-full flex items-center justify-center text-on-secondary-container">
                            <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                        </div>
                        <div>
                            <p class="text-body-sm text-on-surface-variant font-medium">Đơn Đang Xử Lý & Giao Hàng</p>
                            <p class="text-headline-sm font-bold text-on-surface">{{ $pendingOrdersCount ?? 0 }} đơn</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-md">
                    @forelse($sales as $order)
                        <div class="border border-outline-variant rounded-xl overflow-hidden bg-surface-container-lowest">
                            <div class="bg-surface-container-low px-md py-sm flex justify-between items-center border-b border-outline-variant">
                                <div class="text-body-sm font-body-sm text-on-surface-variant">
                                    <span class="mr-2">Mã đơn: <span class="font-bold text-on-surface">#{{ $order->order_number }}</span></span>
                                    <span>Người mua: {{ $order->shipping_name }} - {{ $order->shipping_phone }}</span>
                                </div>
                                <span class="px-2 py-1 text-[11px] uppercase tracking-wider font-bold rounded-full bg-surface-container text-on-surface-variant border border-outline-variant">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                            <x-order-timeline :order="$order" />
                            <div class="p-md flex gap-md items-start">
                                @php $product = $order->items->first()->product; @endphp
                                <img src="{{ $product->primary_image_medium_url }}" alt="" class="w-20 h-20 object-cover rounded border border-outline-variant">
                                <div class="flex-1">
                                    <h3 class="text-body-md font-body-md text-on-surface">{{ $product->title }}</h3>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant mt-1">Giao đến: {{ $order->shipping_address }}</p>
                                    <p class="text-price-lg font-price-lg text-primary mt-2">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</p>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <!-- Actions -->
                                    @if($order->status->value === 'pending')
                                        <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-full text-label-md font-label-md hover:opacity-90 active:-translate-y-px transition-all">Xác nhận đơn</button>
                                        </form>
                                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="w-full px-4 py-2 border border-error text-error bg-error-container/10 rounded-full text-label-md font-label-md hover:bg-error-container transition-colors">Hủy đơn</button>
                                        </form>
                                    @elseif($order->status->value === 'confirmed')
                                        <form action="{{ route('orders.ship', $order->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="w-full px-4 py-2 bg-emerald-500 text-white rounded-full text-label-md font-label-md hover:opacity-90 active:-translate-y-px transition-all">Đã giao ĐVVC</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-xl text-center text-on-surface-variant text-body-md border border-dashed border-outline-variant rounded-xl bg-surface">Bạn chưa có đơn bán nào.</div>
                    @endforelse
                </div>
                <div class="mt-lg">{{ $sales->appends(['tab' => 'sales'])->links() }}</div>

            @elseif($tab === 'favorites')
                <!-- My Favorites -->
                <h2 class="text-headline-sm font-headline-sm text-on-surface mb-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 1;">favorite</span>
                    Sản phẩm yêu thích
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-gutter">
                    @forelse($favorites as $product)
                        <div class="bg-surface rounded-lg border border-outline-variant overflow-hidden hover:shadow-md transition-all group cursor-pointer flex flex-col relative">
                            <!-- Unfavorite Button -->
                            <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="absolute top-2 right-2 z-10">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-full bg-surface-container-lowest/80 backdrop-blur-sm text-error hover:bg-error-container/30 transition-all shadow-sm" title="Bỏ yêu thích">
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                </button>
                            </form>
                            <a href="{{ route('products.show', $product->slug) }}" class="relative w-full pb-[100%] bg-surface-container block">
                                @if($product->primary_image)
                                    <img src="{{ $product->primary_image_medium_url }}" alt="{{ $product->title }}" class="absolute inset-0 object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="absolute inset-0 bg-surface-container-high flex items-center justify-center">
                                        <span class="material-symbols-outlined text-[48px] text-outline-variant">image_not_supported</span>
                                    </div>
                                @endif
                            </a>
                            <div class="p-md flex flex-col flex-1">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <h3 class="text-body-sm font-body-sm text-on-surface line-clamp-2 mb-xs group-hover:text-primary transition-colors">{{ $product->title }}</h3>
                                </a>
                                <div class="text-price-lg font-price-lg text-primary mb-sm mt-auto">{{ $product->formatted_price }}</div>
                                <div class="flex items-center justify-between text-xs text-outline">
                                    <span class="flex items-center gap-1 truncate max-w-[65%]">
                                        <span class="material-symbols-outlined shrink-0" style="font-size: 14px;">location_on</span>
                                        <span class="truncate">{{ $product->province->name ?? 'Không rõ' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-xl text-center text-on-surface-variant text-body-md border border-dashed border-outline-variant rounded-xl bg-surface">
                            <span class="material-symbols-outlined text-[48px] text-outline-variant mb-4 block">heart_broken</span>
                            <p>Bạn chưa yêu thích sản phẩm nào.</p>
                            <a href="{{ route('products.index') }}" class="inline-block mt-4 px-6 py-2 bg-primary text-on-primary rounded-full text-label-md font-label-md hover:opacity-90 transition-opacity">Khám phá ngay</a>
                        </div>
                    @endforelse
                </div>
                <div class="mt-lg">{{ $favorites->appends(['tab' => 'favorites'])->links() }}</div>
            @endif

        </div>
    </div>
</x-app-layout>
