@php
    $seoTitle = $product->title . ' - ' . $product->formatted_price;
    $seoDescription = Str::limit(strip_tags($product->description), 150);
    $seoImage = $product->primary_image_medium_url ? asset($product->primary_image_medium_url) : null;
@endphp
<x-app-layout :title="$seoTitle" :description="$seoDescription" :image="$seoImage">
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Product",
      "name": "{{ $product->title }}",
      "image": [
        "{{ $seoImage }}"
       ],
      "description": "{{ $seoDescription }}",
      "sku": "{{ $product->id }}",
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "VND",
        "price": "{{ $product->price }}",
        "itemCondition": "https://schema.org/UsedCondition",
        "availability": "{{ $product->status->value === 'active' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
          "@@type": "Person",
          "name": "{{ $product->user->name }}"
        }
      }
    }
    </script>

    <main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-lg md:py-xl">
        <!-- Breadcrumbs -->
        <nav aria-label="Breadcrumb" class="flex text-body-sm font-body-sm text-on-surface-variant mb-lg">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a class="hover:text-primary transition-colors" href="{{ route('home') }}">Trang chủ</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                        <a class="hover:text-primary transition-colors" href="{{ route('products.index', ['category_id' => $product->category_id]) }}">{{ $product->category->name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                        <span class="text-on-surface font-semibold truncate max-w-[200px]">{{ $product->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-xl relative" x-data="{ mainImage: '{{ $product->primary_image_medium_url }}' }">
            
            <!-- Left Column: Gallery & Description -->
            <div class="lg:col-span-7 flex flex-col gap-xl">
                <!-- Product Gallery -->
                <div class="flex flex-col gap-md">
                    <!-- Main Image -->
                    <div class="aspect-square w-full bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden relative group flex items-center justify-center">
                        <img :src="mainImage" alt="{{ $product->title }}" class="w-full h-full object-contain object-center transition-transform duration-500">
                    </div>
                    <!-- Thumbnails -->
                    @if($product->images->count() > 1)
                    <div class="flex gap-sm overflow-x-auto pb-2 snap-x hide-scrollbar">
                        @foreach($product->images as $img)
                        <button class="shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden border-2 snap-start transition-all" 
                                :class="mainImage === '{{ $img->url }}' ? 'border-primary opacity-100' : 'border-transparent opacity-60 hover:opacity-100 hover:scale-[1.02] bg-surface-container'"
                                @click="mainImage = '{{ $img->url }}'">
                            <img class="w-full h-full object-cover" src="{{ $img->url }}">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Bento Specs Overview -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-sm">
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col items-center justify-center text-center gap-xs">
                        <span class="material-symbols-outlined text-on-surface-variant" style="font-variation-settings: 'FILL' 0;">health_and_safety</span>
                        <span class="text-label-md font-label-md text-on-surface">{{ $product->condition_percent }}%</span>
                        <span class="text-body-sm font-body-sm text-on-surface-variant">Tình trạng</span>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col items-center justify-center text-center gap-xs">
                        <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">branding_watermark</span>
                        <span class="text-label-md font-label-md text-on-surface">{{ $product->brand ?? 'Khác' }}</span>
                        <span class="text-body-sm font-body-sm text-on-surface-variant">Hãng</span>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col items-center justify-center text-center gap-xs">
                        <span class="material-symbols-outlined text-on-surface-variant">devices</span>
                        <span class="text-label-md font-label-md text-on-surface truncate w-full">{{ $product->model ?? 'Không rõ' }}</span>
                        <span class="text-body-sm font-body-sm text-on-surface-variant">Dòng máy</span>
                    </div>
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col items-center justify-center text-center gap-xs">
                        <span class="material-symbols-outlined text-on-surface-variant">handshake</span>
                        <span class="text-label-md font-label-md text-on-surface">{{ $product->is_negotiable ? 'Có' : 'Không' }}</span>
                        <span class="text-body-sm font-body-sm text-on-surface-variant">Thương lượng</span>
                    </div>
                </div>

                <!-- Detailed Description -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg md:p-xl">
                    <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md">Mô tả chi tiết</h2>
                    <div class="text-body-md font-body-md text-on-surface-variant space-y-4 leading-relaxed whitespace-pre-line">
                        {!! $product->description !!}
                    </div>
                </div>
                
                <!-- Report Product Action -->
                <div class="mt-2 text-right" x-data="{ reportModal: false }">
                    <button @click="reportModal = true" class="text-sm font-body-sm text-outline hover:text-error transition-colors inline-flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">flag</span>
                        Báo cáo vi phạm
                    </button>

                    <!-- Report Modal -->
                    <div x-show="reportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div x-show="reportModal" @click="reportModal = false" class="fixed inset-0 bg-surface-dim bg-opacity-75 transition-opacity"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                            <div class="inline-block align-bottom bg-surface-container-lowest rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-outline-variant">
                                <form action="{{ route('reports.store.product', $product->id) }}" method="POST">
                                    @csrf
                                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Báo cáo vi phạm</h3>
                                        <div class="mb-4 text-left">
                                            <label class="block text-label-md font-label-md text-on-surface mb-2">Lý do báo cáo</label>
                                            <select name="reason" class="w-full rounded-lg border-outline-variant bg-surface text-on-surface focus:ring-error focus:border-error text-body-sm">
                                                <option value="scam">Có dấu hiệu lừa đảo</option>
                                                <option value="fake_item">Hàng giả, hàng nhái</option>
                                                <option value="wrong_category">Đăng sai chuyên mục</option>
                                                <option value="inappropriate">Nội dung phản cảm</option>
                                                <option value="other">Lý do khác</option>
                                            </select>
                                        </div>
                                        <div class="text-left">
                                            <label class="block text-label-md font-label-md text-on-surface mb-2">Mô tả chi tiết</label>
                                            <textarea name="description" rows="3" required class="w-full rounded-lg border-outline-variant bg-surface text-on-surface focus:ring-error focus:border-error text-body-sm" placeholder="Vui lòng cung cấp thêm thông tin để quản trị viên dễ dàng xử lý..."></textarea>
                                        </div>
                                    </div>
                                    <div class="bg-surface-container px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-lg bg-error px-4 py-2 text-base font-bold text-on-error hover:opacity-90 sm:w-auto sm:text-sm">Gửi báo cáo</button>
                                        <button type="button" @click="reportModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg bg-surface border border-outline px-4 py-2 text-base font-medium text-on-surface hover:bg-surface-container-low sm:mt-0 sm:w-auto sm:text-sm">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Info, Seller, CTAs -->
            <div class="lg:col-span-5">
                <div class="sticky top-28 flex flex-col gap-lg">
                    <!-- Product Header Info -->
                    <div class="flex flex-col gap-sm">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center gap-2">
                                <span class="bg-surface-container text-on-surface-variant px-2 py-0.5 rounded text-label-md font-label-md uppercase tracking-wider text-[11px]">{{ $product->condition_label }}</span>
                                <span class="text-body-sm font-body-sm text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">schedule</span> Đăng {{ $product->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <!-- Favorite Button -->
                            @auth
                            <button 
                                id="favorite-btn"
                                onclick="toggleFavorite({{ $product->id }})"
                                class="p-2 rounded-full transition-all duration-300 hover:scale-110 {{ auth()->user()->hasFavorited($product) ? 'text-error bg-error-container/20' : 'text-outline hover:text-error hover:bg-error-container/10' }}"
                                title="{{ auth()->user()->hasFavorited($product) ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                <span class="material-symbols-outlined text-[24px]" id="favorite-icon" style="font-variation-settings: 'FILL' {{ auth()->user()->hasFavorited($product) ? '1' : '0' }};">favorite</span>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="p-2 rounded-full text-outline hover:text-error hover:bg-error-container/10 transition-all" title="Đăng nhập để yêu thích">
                                <span class="material-symbols-outlined text-[24px]">favorite</span>
                            </a>
                            @endauth
                        </div>
                        <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface leading-tight">
                            {{ $product->title }}
                        </h1>
                        <div class="flex items-baseline gap-3 mt-2">
                            <span class="text-price-lg font-price-lg text-primary text-[32px]">{{ $product->formatted_price }}</span>
                            <span class="text-label-md text-on-surface-variant">Kho: {{ $product->quantity }}</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="bg-surface-container-high px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-tertiary">health_and_safety</span>
                                <span class="text-label-md font-label-md text-on-surface">Tình trạng: {{ $product->condition_percent }}%</span>
                            </div>
                            <div class="bg-surface-container-high px-3 py-1.5 rounded-full flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant">location_on</span>
                                <span class="text-label-md font-label-md text-on-surface">{{ $product->district->name ?? '' }}, {{ $product->province->name ?? '' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop CTAs -->
                    <div class="hidden md:flex flex-col gap-3 mt-4">
                        @if($product->status->value === 'sold' || $product->quantity <= 0)
                            <button disabled class="w-full bg-surface-container-highest text-on-surface-variant text-headline-sm font-headline-sm py-4 rounded-xl cursor-not-allowed border border-outline-variant flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">remove_shopping_cart</span>
                                Đã bán hết
                            </button>
                        @else
                            <a href="{{ route('checkout.create', $product->id) }}" class="w-full bg-primary text-on-primary text-headline-sm font-headline-sm py-4 rounded-xl shadow-[0_4px_0_0_#6b4900] hover:translate-y-[2px] hover:shadow-[0_2px_0_0_#6b4900] active:translate-y-[4px] active:shadow-none transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">shopping_bag</span>
                                Mua ngay
                            </a>
                        @endif
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('chat.start', $product->id) }}" class="bg-secondary-container text-on-secondary-container hover:opacity-90 transition-opacity py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-2 border border-transparent">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">chat</span>
                                Chat ngay
                            </a>
                            @if($product->user->phone)
                                <a href="tel:{{ $product->user->phone }}" class="bg-surface-container-lowest border border-primary text-primary hover:bg-primary-container hover:text-on-primary-container transition-colors py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">call</span>
                                    Gọi điện
                                </a>
                            @else
                                <button disabled class="bg-surface-container-lowest border border-outline-variant text-outline opacity-50 cursor-not-allowed py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">phone_disabled</span>
                                    Ẩn số
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Seller Profile Card -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm hover:shadow-md transition-shadow duration-300">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-full overflow-hidden border border-outline-variant relative">
                                    <img alt="{{ $product->user->name }}" class="w-full h-full object-cover" src="{{ $product->user->avatar_url }}">
                                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                                </div>
                                <div>
                                    <h3 class="text-headline-sm font-headline-sm text-on-surface flex items-center gap-1">
                                        {{ $product->user->name }}
                                        <span class="material-symbols-outlined text-[16px] text-secondary" style="font-variation-settings: 'FILL' 1;" title="Đã xác minh">verified</span>
                                    </h3>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant flex items-center gap-1 mt-0.5">
                                        Tham gia: {{ $product->user->created_at->format('m/Y') }}
                                    </p>
                                    <!-- Badges -->
                                    <div class="flex flex-wrap gap-1 mt-2">
                                        @foreach($product->user->seller_badges as $badge)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $badge['color'] }}" title="{{ $badge['label'] }}">
                                                {!! $badge['icon'] !!}
                                                {{ $badge['label'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 py-4 border-y border-outline-variant mb-4">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="flex items-center text-primary-fixed-dim">
                                    @if($product->user->rating > 0)
                                        <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                        <span class="text-body-sm font-body-sm text-on-surface font-bold ml-1">{{ number_format($product->user->rating, 1) }}</span>
                                    @else
                                        <span class="text-body-sm font-body-sm text-on-surface mt-1">Chưa có đánh giá</span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center border-l border-outline-variant">
                                <span class="text-headline-sm font-headline-sm text-on-surface">Uy tín</span>
                                <span class="text-body-sm font-body-sm text-on-surface-variant">Thành viên lâu năm</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('users.show', $product->user->id) }}" class="w-full py-2.5 rounded-lg border border-outline text-on-surface hover:bg-surface-container transition-colors text-label-md font-label-md flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                            Xem Profile
                        </a>
                    </div>

                    <!-- Trust/Safety Banner -->
                    <div class="bg-surface-container rounded-lg p-md flex gap-3 items-start border border-outline-variant/50">
                        <span class="material-symbols-outlined text-primary mt-0.5" style="font-variation-settings: 'FILL' 1;">shield</span>
                        <div>
                            <h4 class="text-label-md font-label-md text-on-surface mb-1">Mua bán an toàn</h4>
                            <p class="text-body-sm font-body-sm text-on-surface-variant leading-tight">Gặp mặt trực tiếp để kiểm tra hàng. Không nên chuyển khoản trước khi xem máy.</p>
                        </div>
                    </div>

                    <!-- Seller Reviews -->
                    @if(isset($reviews) && $reviews->count() > 0)
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg mt-lg shadow-sm">
                        <h4 class="text-label-lg font-label-lg text-on-surface mb-md">Đánh giá từ người mua ({{ $reviews->count() }})</h4>
                        <div class="flex flex-col gap-4">
                            @foreach($reviews as $review)
                            <div class="border-b border-outline-variant pb-4 last:border-0 last:pb-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $review->reviewer->avatar_url }}" alt="{{ $review->reviewer->name }}" class="w-8 h-8 rounded-full border border-outline-variant">
                                    <div>
                                        <p class="text-label-md font-label-md text-on-surface">{{ $review->reviewer->name }}</p>
                                        <div class="flex items-center text-primary-fixed-dim text-[12px]">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? '1' : '0' }};">star</span>
                                            @endfor
                                            <span class="text-on-surface-variant ml-2 font-body-sm">{{ $review->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <div class="text-body-sm text-on-surface mt-1 prose prose-sm max-w-none">{!! $review->comment !!}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        @if($relatedProducts->count() > 0)
        <section class="mt-2xl border-t border-outline-variant pt-xl">
            <h2 class="text-headline-md font-headline-md text-on-surface mb-lg flex items-center justify-between">
                Sản phẩm liên quan
                <a class="text-body-sm font-body-sm text-primary hover:underline flex items-center" href="{{ route('products.index', ['category_id' => $product->category_id]) }}">Xem tất cả <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-gutter">
                @foreach($relatedProducts as $related)
                <!-- Product Card -->
                <a class="group bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 flex flex-col" href="{{ route('products.show', $related->slug) }}">
                    <div class="aspect-square bg-surface-container relative overflow-hidden">
                        @if($related->primary_image)
                            <img alt="{{ $related->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $related->primary_image_medium_url }}">
                        @else
                            <div class="flex items-center justify-center w-full h-full text-outline-variant">
                                <span class="material-symbols-outlined text-[48px]">inventory_2</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-md flex flex-col flex-grow">
                        <h3 class="text-body-md font-body-md text-on-surface line-clamp-2 mb-2 group-hover:text-primary transition-colors">{{ $related->title }}</h3>
                        <div class="mt-auto">
                            <p class="text-price-lg font-price-lg text-primary">{{ $related->formatted_price }}</p>
                            <p class="text-body-sm font-body-sm text-on-surface-variant mt-1 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">location_on</span> {{ $related->district->name ?? '' }}, {{ $related->province->name ?? '' }}
                            </p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
    </main>

    <!-- Mobile Sticky Bottom CTAs -->
    <div class="md:hidden fixed bottom-0 left-0 w-full bg-surface-container-lowest border-t border-outline-variant p-margin-mobile pb-8 shadow-[0_-4px_20px_rgb(0,0,0,0.05)] z-40 flex items-center gap-sm">
        <a href="{{ route('chat.start', $product->id) }}" class="flex-1 bg-secondary-container text-on-secondary-container py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-1">
            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">chat</span>
            Chat
        </a>
        @if($product->status->value === 'sold' || $product->quantity <= 0)
            <button disabled class="flex-1 bg-surface-container-highest text-on-surface-variant py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-1 opacity-60 cursor-not-allowed border border-outline-variant">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">remove_shopping_cart</span>
                Hết hàng
            </button>
        @else
            <a href="{{ route('checkout.create', $product->id) }}" class="flex-1 bg-primary text-on-primary py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-1 shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none">
                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">shopping_bag</span>
                Mua ngay
            </a>
        @endif
        @if($product->user->phone)
        <a href="tel:{{ $product->user->phone }}" class="w-12 h-12 bg-surface-container-high border border-outline-variant text-on-surface rounded-lg flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined">call</span>
        </a>
        @endif
    </div>

    @auth
    <script>
        function toggleFavorite(productId) {
            const btn = document.getElementById('favorite-btn');
            const icon = document.getElementById('favorite-icon');
            
            fetch(`/yeu-thich/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                if (data.is_favorited) {
                    btn.classList.remove('text-outline', 'hover:text-error', 'hover:bg-error-container/10');
                    btn.classList.add('text-error', 'bg-error-container/20');
                    icon.style.fontVariationSettings = "'FILL' 1";
                    btn.title = 'Bỏ yêu thích';
                } else {
                    btn.classList.remove('text-error', 'bg-error-container/20');
                    btn.classList.add('text-outline', 'hover:text-error', 'hover:bg-error-container/10');
                    icon.style.fontVariationSettings = "'FILL' 0";
                    btn.title = 'Thêm vào yêu thích';
                }
                // Animate
                btn.classList.add('scale-125');
                setTimeout(() => btn.classList.remove('scale-125'), 200);
            })
            .catch(err => console.error('Favorite toggle failed:', err));
        }
    </script>
    @endauth
</x-app-layout>
