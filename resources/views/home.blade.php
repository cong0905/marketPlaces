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
            <h1 class="text-display-lg font-display-lg text-primary mb-md">Mua Bán Nhanh Chóng, Tin Cậy</h1>
            <p class="text-body-lg font-body-lg text-on-surface-variant mb-xl max-w-2xl mx-auto">Khám phá hàng ngàn món đồ cũ chất lượng với giá hời. Nền tảng mua bán an toàn và tiện lợi nhất 2026.</p>
            <!-- Centered Search -->
            <form action="{{ route('products.index') }}" method="GET" class="max-w-3xl mx-auto bg-surface rounded-full shadow-sm p-2 flex items-center border border-outline-variant focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary-container transition-all">
                <span class="material-symbols-outlined text-outline ml-4">search</span>
                <input class="flex-1 bg-transparent border-none focus:ring-0 text-body-md font-body-md px-4 py-3 placeholder:text-outline-variant outline-none" name="keyword" placeholder="Tìm kiếm bất động sản, xe cộ, đồ điện tử..." type="text" value="{{ request('keyword') }}"/>
                <button type="submit" class="bg-primary text-on-primary rounded-full px-6 py-3 text-label-md font-label-md flex items-center gap-xs hover:opacity-90 transition-opacity">
                    Tìm kiếm
                </button>
            </form>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-xl px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto">
        <div class="flex justify-between items-end mb-lg">
            <h2 class="text-headline-md font-headline-md text-on-surface">Danh Mục Nổi Bật</h2>
            <a class="text-label-md font-label-md text-primary flex items-center gap-xs hover:underline" href="{{ route('products.index') }}">
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
                <a class="flex flex-col items-center gap-sm group" href="{{ route('products.index', ['category_id' => $category->id]) }}">
                    <div class="w-16 h-16 rounded-full bg-surface-container hover:bg-primary-container transition-colors flex items-center justify-center text-primary group-hover:text-on-primary-container shadow-sm border border-outline-variant">
                        <span class="material-symbols-outlined" style="font-size: 28px;">{{ $matIcon }}</span>
                    </div>
                    <span class="text-body-sm font-body-sm text-center text-on-surface-variant group-hover:text-primary font-medium truncate w-full">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-xl bg-surface-container-lowest px-margin-mobile md:px-margin-desktop">
        <div class="max-w-container-max mx-auto">
            <div class="flex items-center gap-sm mb-lg">
                <span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">star</span>
                <h2 class="text-headline-md font-headline-md text-on-surface">Tin Đăng Mới Nhất</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-gutter">
                @forelse($products as $product)
                    <!-- Product Card -->
                    <div class="bg-surface rounded-lg border border-outline-variant overflow-hidden hover:shadow-[0_4px_20px_-4px_rgba(15,23,42,0.1)] transition-all group cursor-pointer flex flex-col">
                        <a href="{{ route('products.show', $product->slug) }}" class="relative w-full pb-[100%] bg-surface-container block">
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
