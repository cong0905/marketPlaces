<x-app-layout title="Danh sách yêu thích">
    <x-slot name="header">
        <h2 class="font-bold text-headline-sm text-on-surface leading-tight">
            {{ __('Danh sách yêu thích') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop">
            <div class="bg-surface-container border border-outline-variant rounded-xl shadow-sm p-6 sm:p-8">
                
                @if($favorites->isEmpty())
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-[64px] text-on-surface-variant opacity-50 mb-4">favorite_border</span>
                        <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Bạn chưa có sản phẩm yêu thích nào</h3>
                        <p class="text-body-md text-on-surface-variant mb-6">Hãy dạo quanh và thả tim cho những món đồ bạn thích nhé.</p>
                        <x-button variant="filled" href="{{ route('products.index') }}">
                            Khám phá ngay
                        </x-button>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($favorites as $product)
                            <div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant hover:shadow-md transition-shadow flex flex-col h-full group relative">
                                
                                <!-- Unfavorite Button (Absolute) -->
                                <form action="{{ route('favorites.toggle', $product) }}" method="POST" class="absolute top-2 right-2 z-10">
                                    @csrf
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full bg-surface/80 text-error backdrop-blur-sm hover:bg-error hover:text-on-error transition-colors shadow-sm" title="Bỏ yêu thích">
                                        <span class="material-symbols-outlined text-[18px] filled" style="font-variation-settings: 'FILL' 1;">favorite</span>
                                    </button>
                                </form>

                                <a href="{{ route('products.show', $product->slug) }}" class="relative aspect-square block overflow-hidden bg-surface-container border-b border-outline-variant">
                                    @if($product->primary_image)
                                        <img src="{{ $product->primary_image_medium_url }}" alt="{{ $product->title }}" loading="lazy" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="flex items-center justify-center w-full h-full text-on-surface-variant">
                                            <span class="material-symbols-outlined text-[48px]">image_not_supported</span>
                                        </div>
                                    @endif
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
                                        <span class="flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[16px]">location_on</span>
                                            <span class="truncate max-w-[120px]">{{ $product->province->name ?? 'Không rõ' }}</span>
                                        </span>
                                        <a href="{{ route('checkout.create', $product) }}" class="text-primary hover:text-primary-container font-label-md transition-colors">Mua ngay</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-8">
                        {{ $favorites->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
