@php
    $categoryName = request('category_id') ? $categories->firstWhere('id', request('category_id'))?->name : null;
    $seoTitle = $categoryName ? "Mua bán {$categoryName} cũ giá rẻ, uy tín" : "Mua bán đồ cũ trực tuyến - Tất cả sản phẩm";
    $seoDescription = "Danh sách sản phẩm {$categoryName} đồ cũ đang được rao bán. Giao dịch an toàn, giá tốt nhất trên Amber Marketplace.";
@endphp
<x-app-layout :title="$seoTitle" :description="$seoDescription">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-xl" x-data="{ showFilters: false }">
        
        <!-- Breadcrumb & Mobile Filter Toggle -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-4">
            <nav class="flex text-body-sm text-on-surface-variant" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Trang chủ</a>
                    </li>
                    <li><div class="flex items-center"><span class="material-symbols-outlined text-[16px]">chevron_right</span></div></li>
                    <li aria-current="page"><span class="text-on-surface font-bold">Tất cả sản phẩm</span></li>
                </ol>
            </nav>

            <button @click="showFilters = !showFilters" class="md:hidden w-full flex items-center justify-center gap-2 py-2 px-4 bg-surface border border-outline-variant rounded-full text-on-surface shadow-sm font-label-md">
                <span class="material-symbols-outlined text-[20px]">filter_list</span>
                Lọc Sản Phẩm
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-lg">
            
            <!-- Sidebar Filters -->
            <div class="md:w-1/4" :class="{'hidden md:block': !showFilters}">
                <form action="{{ route('products.index') }}" method="GET" id="filter-form" class="bg-surface-container-lowest rounded-2xl p-lg shadow-sm border border-outline-variant space-y-lg md:sticky md:top-24">
                    
                    <!-- Keyword Search (Preserve) -->
                    @if(request('keyword'))
                        <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                        <div class="pb-md border-b border-outline-variant">
                            <h3 class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider mb-2">Đang tìm kiếm</h3>
                            <div class="inline-flex items-center gap-2 bg-primary-container text-on-primary-container px-3 py-1.5 rounded-lg text-body-sm font-medium">
                                "{{ request('keyword') }}"
                                <a href="{{ route('products.index', request()->except('keyword')) }}" class="hover:text-primary transition-colors"><span class="material-symbols-outlined text-[16px]">close</span></a>
                            </div>
                        </div>
                    @endif

                    <!-- Category Filter -->
                    <div>
                        <h3 class="text-label-md font-label-md text-on-surface mb-3">Danh mục</h3>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                            <label class="flex items-center group cursor-pointer">
                                <input type="radio" name="category_id" value="" class="w-5 h-5 rounded-full border-outline text-primary focus:ring-primary focus:ring-2 focus:ring-offset-2 bg-surface" {{ empty(request('category_id')) ? 'checked' : '' }} onchange="this.form.submit()">
                                <span class="ml-3 text-body-md text-on-surface group-hover:text-primary transition-colors">Tất cả</span>
                            </label>
                            @foreach($categories as $category)
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="category_id" value="{{ $category->id }}" class="w-5 h-5 rounded-full border-outline text-primary focus:ring-primary focus:ring-2 focus:ring-offset-2 bg-surface" {{ request('category_id') == $category->id ? 'checked' : '' }} onchange="this.form.submit()">
                                    <span class="ml-3 text-body-md text-on-surface group-hover:text-primary transition-colors">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="border-t border-outline-variant pt-md">
                        <h3 class="text-label-md font-label-md text-on-surface mb-3">Khoảng giá (VNĐ)</h3>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}" class="w-full text-body-sm rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary">
                            <span class="text-on-surface-variant">-</span>
                            <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}" class="w-full text-body-sm rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary">
                        </div>
                    </div>

                    <!-- Condition -->
                    <div class="border-t border-outline-variant pt-md">
                        <h3 class="text-label-md font-label-md text-on-surface mb-3">Tình trạng tối thiểu</h3>
                        @php
                            $conditionOptions = [
                                ['value' => '', 'label' => 'Tất cả'],
                                ['value' => '95', 'label' => 'Như mới (>= 95%)'],
                                ['value' => '80', 'label' => 'Rất tốt (>= 80%)'],
                                ['value' => '60', 'label' => 'Tốt (>= 60%)'],
                            ];
                        @endphp
                        <x-custom-select name="condition_min" :options="$conditionOptions" :selected="request('condition_min')" placeholder="Tất cả" />
                    </div>

                    <!-- Location -->
                    <div class="border-t border-outline-variant pt-md">
                        <h3 class="text-label-md font-label-md text-on-surface mb-3">Khu vực</h3>
                        @php
                            $provinceOptions = [['value' => '', 'label' => 'Toàn quốc']];
                            foreach($provinces as $p) {
                                $provinceOptions[] = ['value' => $p->id, 'label' => $p->name];
                            }
                        @endphp
                        <x-custom-select name="province" :options="$provinceOptions" :selected="request('province')" placeholder="Toàn quốc" />
                    </div>

                    <x-button type="submit" variant="filled" class="w-full">
                        Áp Dụng Lọc
                    </x-button>
                    
                    @if(request()->anyFilled(['category_id', 'min_price', 'max_price', 'condition_min', 'province']))
                        <a href="{{ route('products.index', ['keyword' => request('keyword')]) }}" class="block text-center text-body-sm text-on-surface-variant hover:text-primary mt-3 transition-colors">Xóa bộ lọc</a>
                    @endif
                </form>
            </div>

            <!-- Main Content: Product Grid -->
            <div class="md:w-3/4">
                
                <!-- Sorting Bar -->
                <div class="bg-surface-container-lowest rounded-xl p-md shadow-sm border border-outline-variant mb-lg flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-body-sm text-on-surface-variant">
                        Tìm thấy <span class="font-bold text-on-surface">{{ $products->total() }}</span> sản phẩm
                    </p>
                    <div class="flex items-center gap-2">
                        <label class="text-body-sm text-on-surface-variant whitespace-nowrap">Sắp xếp:</label>
                        @php
                            $sortOptions = [
                                ['value' => 'latest', 'label' => 'Mới nhất'],
                                ['value' => 'price_asc', 'label' => 'Giá: Thấp đến Cao'],
                                ['value' => 'price_desc', 'label' => 'Giá: Cao xuống Thấp'],
                                ['value' => 'views', 'label' => 'Xem nhiều nhất'],
                            ];
                        @endphp
                        <div class="w-48">
                            <x-custom-select name="sort" :options="$sortOptions" :selected="request('sort', 'latest')" placeholder="Mới nhất" />
                        </div>
                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-lg">
                    @forelse($products as $product)
                        <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant hover:shadow-md transition-shadow flex flex-col h-full group">
                            <a href="{{ route('products.show', $product->slug) }}" class="relative aspect-square block overflow-hidden bg-surface-container">
                                @if($product->primary_image)
                                    <img src="{{ $product->primary_image_medium_url }}" alt="{{ $product->title }}" loading="lazy" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[48px]">image_not_supported</span>
                                    </div>
                                @endif
                                <div class="absolute bottom-2 left-2 bg-inverse-surface/80 text-inverse-on-surface text-[10px] font-bold px-2 py-1 rounded backdrop-blur-sm">
                                    {{ $product->condition_label }}
                                </div>
                            </a>
                            <div class="p-4 flex-1 flex flex-col">
                                <a href="{{ route('products.show', $product->slug) }}">
                                    <h3 class="font-body-md text-body-md text-on-surface line-clamp-2 hover:text-primary transition-colors" title="{{ $product->title }}">
                                        {{ $product->title }}
                                    </h3>
                                </a>
                                <div class="mt-2 text-price-lg font-price-lg text-primary">
                                    {{ $product->formatted_price }}
                                </div>
                                <div class="mt-auto pt-4 flex items-center justify-between text-body-sm text-on-surface-variant">
                                    <span class="flex items-center gap-1 truncate max-w-[60%]">
                                        <span class="material-symbols-outlined text-[16px]">location_on</span>
                                        <span class="truncate">{{ $product->province->name ?? 'Không rõ' }}</span>
                                    </span>
                                    <span>{{ $product->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-surface-container-lowest rounded-2xl border border-dashed border-outline-variant">
                            <span class="material-symbols-outlined text-[64px] text-on-surface-variant opacity-50 mb-4">search_off</span>
                            <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Không tìm thấy sản phẩm</h3>
                            <p class="text-body-md text-on-surface-variant mb-6">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm của bạn.</p>
                            <x-button variant="outlined" href="{{ route('products.index') }}">
                                Xóa bộ lọc
                            </x-button>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="mt-xl">
                    {{ $products->appends(request()->query())->links() }}
                </div>

            </div>
        </div>
    </div>
    
    <script>
        // Trigger form submission when sort select changes
        document.addEventListener('alpine:init', () => {
            document.querySelectorAll('input[name="sort"]').forEach(el => {
                el.addEventListener('change', () => {
                    document.getElementById('filter-form').insertAdjacentHTML('beforeend', `<input type="hidden" name="sort" value="${el.value}">`);
                    document.getElementById('filter-form').submit();
                });
            });
            document.querySelectorAll('input[name="condition_min"], input[name="province"]').forEach(el => {
                el.addEventListener('change', () => {
                    document.getElementById('filter-form').submit();
                });
            });
        });
    </script>
</x-app-layout>
