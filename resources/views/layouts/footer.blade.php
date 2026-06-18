<footer class="bg-surface-container-lowest dark:bg-surface-dim full-width relative border-t border-outline-variant dark:border-outline mt-xl">
    <div class="w-full px-margin-desktop py-xl max-w-container-max mx-auto grid grid-cols-1 md:grid-cols-4 gap-lg">
        <!-- Brand / Copyright -->
        <div class="flex flex-col gap-sm">
            <div class="flex items-center gap-sm mb-sm">
                <span class="text-headline-md font-headline-md text-primary font-bold">Amber Marketplace</span>
            </div>
            <p class="text-body-sm font-body-sm text-on-surface-variant">© {{ date('Y') }} Amber Marketplace. Reliable. Energetic. Efficient.</p>
        </div>
        <!-- Links -->
        <div class="flex flex-col gap-sm">
            <h4 class="text-label-md font-label-md text-on-surface font-bold mb-xs">Về Chúng Tôi</h4>
            <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline opacity-80 hover:opacity-100" href="{{ route('pages.about') }}">Giới thiệu</a>
            <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline opacity-80 hover:opacity-100" href="{{ route('pages.contact') }}">Liên hệ</a>
        </div>
        <div class="flex flex-col gap-sm">
            <h4 class="text-label-md font-label-md text-on-surface font-bold mb-xs">Hỗ Trợ Khách Hàng</h4>
            <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline opacity-80 hover:opacity-100" href="{{ route('pages.help') }}">Trung tâm trợ giúp</a>
            <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline opacity-80 hover:opacity-100" href="{{ route('pages.safety') }}">Quy định an toàn</a>
        </div>
        <div class="flex flex-col gap-sm">
            <h4 class="text-label-md font-label-md text-on-surface font-bold mb-xs">Pháp Lý</h4>
            <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline opacity-80 hover:opacity-100" href="{{ route('pages.terms') }}">Điều khoản sử dụng</a>
            <a class="text-body-sm font-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline opacity-80 hover:opacity-100" href="{{ route('pages.privacy') }}">Chính sách bảo mật</a>
        </div>
    </div>
</footer>
