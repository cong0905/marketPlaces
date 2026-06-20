@php
    $seoTitle = "Amber Marketplace - Nền tảng mua bán đồ cũ an toàn, tiện lợi";
    $seoDescription = "Amber Marketplace giúp bạn mua bán đồ cũ một cách dễ dàng, uy tín và an toàn. Tìm kiếm hàng nghìn sản phẩm điện tử, gia dụng, thời trang giá tốt.";
@endphp
<x-app-layout :title="$seoTitle" :description="$seoDescription">
    <!-- Hero Section -->
    <section class="relative bg-surface-container-low py-2xl px-margin-mobile md:px-margin-desktop text-center overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at 50% 50%, var(--tw-colors-primary-container) 0%, transparent 50%); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;"></div>
        <div class="max-w-container-max mx-auto relative z-10">
            <h1 class="text-display-lg font-display-lg text-primary mb-md reveal-on-scroll">Mua Bán Nhanh Chóng, Tin Cậy</h1>
            <p class="text-body-lg font-body-lg text-on-surface-variant mb-xl max-w-2xl mx-auto reveal-on-scroll" style="animation-delay: 100ms;">Khám phá hàng ngàn món đồ cũ chất lượng với giá hời. Nền tảng mua bán an toàn và tiện lợi nhất 2026.</p>
            <!-- Centered Search -->
            <form action="{{ route('products.index') }}" method="GET" class="max-w-3xl mx-auto bg-surface rounded-full shadow-sm p-2 flex items-center border border-outline-variant focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary-container transition-all reveal-on-scroll" style="animation-delay: 200ms;">
                <span class="material-symbols-outlined text-outline ml-4">search</span>
                <input class="flex-1 bg-transparent border-none focus:ring-0 text-body-md font-body-md px-4 py-3 placeholder:text-outline-variant outline-none" name="keyword" placeholder="Tìm kiếm bất động sản, xe cộ, đồ điện tử..." type="text" value="{{ request('keyword') }}"/>
                <button type="submit" class="bg-primary text-on-primary rounded-full px-6 py-3 text-label-md font-label-md flex items-center gap-xs hover:opacity-90 transition-opacity hover:scale-105 active:scale-95 duration-200">
                    Tìm kiếm
                </button>
            </form>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-xl px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="flex justify-between items-end mb-lg reveal-on-scroll">
            <h2 class="text-headline-md font-headline-md text-on-surface">Danh Mục Nổi Bật</h2>
            <a class="text-label-md font-label-md text-primary flex items-center gap-xs hover:underline hover:translate-x-1 transition-transform" href="{{ route('products.index') }}">
                Xem tất cả <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-4 md:grid-cols-8 gap-gutter">
            @foreach($categories->take(8) as $category)
                @php
                    // Map old emoji icons to material symbols if possible, fallback to category
                    $iconMap = [
                        '📱' => 'devices',
                        '💻' => 'computer',
                        '🚗' => 'directions_car',
                        '🏍️' => 'two_wheeler',
                        '🏠' => 'real_estate_agent',
                        '👗' => 'checkroom',
                        '📚' => 'menu_book',
                        '🛋️' => 'chair'
                    ];
                    $matIcon = $iconMap[$category->icon] ?? 'category';
                @endphp
                <a class="flex flex-col items-center gap-sm group reveal-on-scroll" style="animation-delay: {{ $loop->index * 50 }}ms;" href="{{ route('products.index', ['category_id' => $category->id]) }}">
                    <div class="w-16 h-16 rounded-full bg-surface-container hover:bg-primary-container transition-colors flex items-center justify-center text-primary group-hover:text-on-primary-container shadow-sm border border-outline-variant group-hover:-translate-y-1 group-hover:shadow-md duration-300">
                        <span class="material-symbols-outlined transition-transform group-hover:scale-110" style="font-size: 28px;">{{ $matIcon }}</span>
                    </div>
                    <span class="text-body-sm font-body-sm text-center text-on-surface-variant group-hover:text-primary font-medium truncate w-full">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Flash Sales Section -->
    @if(isset($flashSales) && $flashSales->count() > 0)
    <section class="py-xl bg-[#fff8e1] dark:bg-[#453600] px-margin-mobile md:px-margin-desktop border-y border-[#ffeb3b]/30 relative overflow-hidden">
        <!-- Decor -->
        <div class="absolute -top-10 -right-10 text-[#ffeb3b]/20">
            <span class="material-symbols-outlined" style="font-size: 200px; font-variation-settings: 'FILL' 1;">bolt</span>
        </div>
        
        <div class="max-w-container-max mx-auto relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-md mb-lg reveal-on-scroll">
                <div class="flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[#f59e0b] animate-pulse" style="font-size: 40px; font-variation-settings: 'FILL' 1;">bolt</span>
                    <h2 class="text-headline-md font-headline-md text-on-surface uppercase tracking-wide">Giờ Vàng Giá Sốc</h2>
                    
                    @php $firstSale = $flashSales->first(); @endphp
                    <!-- AlpineJS Countdown Timer -->
                    <div x-data="countdown('{{ $firstSale->ends_at->toIso8601String() }}')" class="flex items-center gap-2 ml-4">
                        <div class="bg-error text-white font-bold rounded px-2 py-1 text-label-lg" x-text="hours">00</div>
                        <span class="text-error font-bold">:</span>
                        <div class="bg-error text-white font-bold rounded px-2 py-1 text-label-lg" x-text="minutes">00</div>
                        <span class="text-error font-bold">:</span>
                        <div class="bg-error text-white font-bold rounded px-2 py-1 text-label-lg" x-text="seconds">00</div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-gutter">
                @foreach($flashSales as $sale)
                    @php $product = $sale->product; @endphp
                    <!-- Flash Sale Card -->
                    <div class="bg-surface rounded-lg border border-[#ffeb3b] overflow-hidden shadow-[0_4px_20px_-4px_rgba(245,158,11,0.2)] hover:-translate-y-2 transition-all duration-300 group cursor-pointer flex flex-col relative reveal-on-scroll" style="animation-delay: {{ $loop->index * 75 }}ms;">
                        <div class="absolute top-0 right-0 bg-error text-white px-2 py-1 rounded-bl-lg z-10 font-bold text-label-sm shadow-sm group-hover:scale-110 transition-transform origin-top-right">
                            -{{ round((($product->price - $sale->discount_price) / $product->price) * 100) }}%
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" class="relative w-full pb-[100%] bg-surface-container block">
                            @if($product->primary_image)
                                <img src="{{ $product->primary_image_medium_url }}" alt="{{ $product->title }}" class="absolute inset-0 object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                            @endif
                        </a>
                        <div class="p-md flex flex-col flex-1">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <h3 class="text-body-sm font-body-sm text-on-surface line-clamp-2 mb-xs group-hover:text-primary transition-colors">{{ $product->title }}</h3>
                            </a>
                            <div class="flex flex-col mt-auto">
                                <span class="text-price-lg font-price-lg text-error font-bold">{{ number_format($sale->discount_price, 0, ',', '.') }} ₫</span>
                                <span class="text-body-sm text-outline-variant line-through">{{ $product->formatted_price }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('countdown', (targetDate) => ({
                target: new Date(targetDate).getTime(),
                now: new Date().getTime(),
                distance: 0,
                hours: '00',
                minutes: '00',
                seconds: '00',
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    this.now = new Date().getTime();
                    this.distance = this.target - this.now;
                    if (this.distance < 0) {
                        this.hours = '00'; this.minutes = '00'; this.seconds = '00';
                        return;
                    }
                    let h = Math.floor((this.distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    let m = Math.floor((this.distance % (1000 * 60 * 60)) / (1000 * 60));
                    let s = Math.floor((this.distance % (1000 * 60)) / 1000);
                    
                    this.hours = String(h).padStart(2, '0');
                    this.minutes = String(m).padStart(2, '0');
                    this.seconds = String(s).padStart(2, '0');
                }
            }))
        })
    </script>
    @endif

    <!-- Featured Products -->
    <section class="py-xl bg-surface-container-lowest px-margin-mobile md:px-margin-desktop">
        <div class="max-w-container-max mx-auto">
            <div class="flex items-center gap-sm mb-lg reveal-on-scroll">
                <span class="material-symbols-outlined text-primary-container animate-pulse" style="font-variation-settings: 'FILL' 1;">star</span>
                <h2 class="text-headline-md font-headline-md text-on-surface">Tin Đăng Mới Nhất</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-gutter">
                @forelse($products as $product)
                    <!-- Product Card -->
                    <div class="bg-surface rounded-lg border border-outline-variant overflow-hidden hover:shadow-[0_8px_30px_-4px_rgba(15,23,42,0.15)] hover:-translate-y-2 transition-all duration-300 group cursor-pointer flex flex-col reveal-on-scroll" style="animation-delay: {{ $loop->index * 75 }}ms;">
                        <a href="{{ route('products.show', $product->slug) }}" class="relative w-full pb-[100%] bg-surface-container block overflow-hidden">
                            @if($product->primary_image)
                                <img src="{{ $product->primary_image_medium_url }}" alt="{{ $product->title }}" class="absolute inset-0 object-cover w-full h-full group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="absolute inset-0 bg-surface-container-high animate-pulse"></div>
                            @endif
                            <div class="absolute top-2 left-2 bg-primary text-on-primary text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide flex items-center gap-1 z-10 shadow-sm">
                                <span class="material-symbols-outlined" style="font-size: 12px; font-variation-settings: 'FILL' 1;">bolt</span> MỚI
                            </div>
                            @if($product->status->value === 'sold' || $product->quantity <= 0)
                            <div class="absolute inset-0 bg-surface-dim/50 backdrop-blur-[2px] flex items-center justify-center z-20">
                                <span class="bg-surface text-on-surface px-4 py-2 rounded-full font-bold shadow-md border border-outline-variant/30 text-label-lg">Đã bán hết</span>
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
                                    <span class="truncate">{{ $product->location_province }}</span>
                                </span>
                                <span class="shrink-0">{{ $product->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-outline bg-surface rounded-xl border border-dashed border-outline-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">inventory_2</span>
                        <p class="text-body-sm">Chưa có sản phẩm nào được đăng bán.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-app-layout>
