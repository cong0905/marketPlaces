@php
    $seoTitle = 'Profile của ' . $user->name;
    $seoDescription = 'Xem trang cá nhân, thông tin liên hệ và danh sách sản phẩm đang bán của ' . $user->name . ' trên Amber Marketplace.';
@endphp
<x-app-layout :title="$seoTitle" :description="$seoDescription">
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
                        <span class="text-on-surface font-semibold">Profile Người Dùng</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-xl">
            
            <!-- Left Sidebar: Profile Info -->
            <div class="lg:col-span-4 flex flex-col gap-lg">
                <!-- User Profile Card -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl shadow-sm flex flex-col items-center text-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-surface-container relative mb-4">
                        <img alt="{{ $user->name }}" class="w-full h-full object-cover" src="{{ $user->avatar_url }}">
                        <div class="absolute bottom-1 right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <h1 class="text-headline-md font-headline-md text-on-surface flex items-center justify-center gap-1 mb-1">
                        {{ $user->name }}
                        <span class="material-symbols-outlined text-[20px] text-secondary" style="font-variation-settings: 'FILL' 1;" title="Đã xác minh">verified</span>
                    </h1>
                    
                    <p class="text-body-md font-body-md text-on-surface-variant mb-6">Tham gia từ {{ $user->created_at->format('m/Y') }}</p>

                    <div class="w-full grid grid-cols-2 gap-4 py-4 border-y border-outline-variant mb-6">
                        <div class="flex flex-col items-center justify-center text-center">
                            <span class="text-label-lg font-label-lg text-on-surface flex items-center text-primary-fixed-dim">
                                <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="ml-1">{{ $user->rating > 0 ? number_format($user->rating, 1) : '--' }}</span>
                            </span>
                            <span class="text-body-sm font-body-sm text-on-surface-variant mt-1">Đánh giá</span>
                        </div>
                        <div class="flex flex-col items-center justify-center text-center border-l border-outline-variant">
                            <span class="text-label-lg font-label-lg text-on-surface">{{ $products->total() }}</span>
                            <span class="text-body-sm font-body-sm text-on-surface-variant mt-1">Sản phẩm</span>
                        </div>
                    </div>

                    @if($user->bio)
                        <div class="w-full text-left bg-surface-container p-4 rounded-lg mb-6">
                            <h3 class="text-label-md font-label-md text-on-surface mb-2">Giới thiệu</h3>
                            <p class="text-body-sm font-body-sm text-on-surface-variant">{{ $user->bio }}</p>
                        </div>
                    @endif

                    <div class="w-full flex flex-col gap-3">
                        @if($user->phone)
                            <a href="tel:{{ $user->phone }}" class="w-full bg-primary text-on-primary hover:opacity-90 transition-opacity py-3 rounded-lg text-label-md font-label-md flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[20px]">call</span>
                                {{ $user->phone }}
                            </a>
                        @endif
                        @auth
                            @if(auth()->id() !== $user->id)
                                <!-- Report User Action -->
                                <div x-data="{ reportModal: false }" class="w-full">
                                    <button @click="reportModal = true" class="w-full bg-surface-container text-on-surface hover:bg-surface-container-high transition-colors py-3 rounded-lg border border-outline-variant text-label-md font-label-md flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-[20px]">flag</span>
                                        Báo cáo người dùng
                                    </button>

                                    <!-- Report Modal -->
                                    <div x-show="reportModal" class="fixed inset-0 z-50 overflow-y-auto text-left" style="display: none;">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div x-show="reportModal" @click="reportModal = false" class="fixed inset-0 bg-surface-dim bg-opacity-75 transition-opacity"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                            <div class="inline-block align-bottom bg-surface-container-lowest rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-outline-variant">
                                                <form action="{{ route('reports.store.user', $user->id) }}" method="POST">
                                                    @csrf
                                                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Báo cáo người dùng</h3>
                                                        <div class="mb-4">
                                                            <label class="block text-label-md font-label-md text-on-surface mb-2">Lý do báo cáo</label>
                                                            <select name="reason" class="w-full rounded-lg border-outline-variant bg-surface text-on-surface focus:ring-error focus:border-error text-body-sm">
                                                                <option value="scam">Có dấu hiệu lừa đảo</option>
                                                                <option value="inappropriate">Hành vi không phù hợp</option>
                                                                <option value="spam">Spam / Đăng tin rác</option>
                                                                <option value="other">Lý do khác</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-label-md font-label-md text-on-surface mb-2">Chi tiết</label>
                                                            <textarea name="description" rows="3" required class="w-full rounded-lg border-outline-variant bg-surface text-on-surface focus:ring-error focus:border-error text-body-sm" placeholder="Mô tả cụ thể vấn đề..."></textarea>
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
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- User Reviews Box -->
                @if($reviews->count() > 0)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-4">Đánh giá mới nhất ({{ $user->reviewsReceived()->count() }})</h3>
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

            <!-- Right Area: Active Products -->
            <div class="lg:col-span-8">
                <h2 class="text-headline-md font-headline-md text-on-surface mb-lg flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 1;">storefront</span>
                    Sản phẩm đang bán ({{ $products->total() }})
                </h2>

                @if($products->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-gutter">
                        @foreach($products as $product)
                        <a class="group bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 flex flex-col" href="{{ route('products.show', $product->slug) }}">
                            <div class="aspect-square bg-surface-container relative overflow-hidden">
                                @if($product->primary_image)
                                    <img alt="{{ $product->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $product->primary_image_medium_url }}">
                                @else
                                    <div class="flex items-center justify-center w-full h-full text-outline-variant">
                                        <span class="material-symbols-outlined text-[48px]">inventory_2</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-md flex flex-col flex-grow">
                                <h3 class="text-body-md font-body-md text-on-surface line-clamp-2 mb-2 group-hover:text-primary transition-colors">{{ $product->title }}</h3>
                                <div class="mt-auto">
                                    <p class="text-price-lg font-price-lg text-primary">{{ $product->formatted_price }}</p>
                                    <p class="text-body-sm font-body-sm text-on-surface-variant mt-1 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">location_on</span> {{ $product->district->name ?? '' }}, {{ $product->province->name ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                        <div class="mt-xl">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center py-20">
                        <span class="material-symbols-outlined text-[64px] text-outline-variant mb-4">inventory_2</span>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Người dùng chưa đăng bán sản phẩm nào</h3>
                        <p class="text-body-md font-body-md text-on-surface-variant">Hãy quay lại sau nhé.</p>
                    </div>
                @endif
            </div>

        </div>
    </main>
</x-app-layout>
