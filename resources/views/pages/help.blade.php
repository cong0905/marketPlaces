<x-app-layout title="Trung tâm trợ giúp - Amber Marketplace" description="Giải đáp các thắc mắc và hướng dẫn sử dụng Amber Marketplace.">
    <main class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-xl md:py-2xl">
        <h1 class="text-display-lg font-display-lg text-on-surface mb-lg">Trung tâm trợ giúp</h1>
        
        <div class="prose prose-lg prose-zinc dark:prose-invert max-w-none">
            <p class="text-body-lg text-on-surface-variant mb-8">
                Tìm kiếm câu trả lời cho các câu hỏi thường gặp khi sử dụng Amber Marketplace.
            </p>

            <div class="space-y-6">
                <!-- FAQ Item 1 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Làm thế nào để đăng bán sản phẩm?</h3>
                    <p class="text-body-md text-on-surface-variant">
                        Bạn cần đăng nhập vào tài khoản, sau đó nhấn vào nút "Đăng tin" ở góc phải màn hình. Điền đầy đủ thông tin về sản phẩm, tải lên hình ảnh rõ nét và nhấn "Đăng tin". Tin của bạn sẽ được kiểm duyệt trước khi hiển thị công khai.
                    </p>
                </div>

                <!-- FAQ Item 2 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Tôi có thể thay đổi thông tin sản phẩm sau khi đăng không?</h3>
                    <p class="text-body-md text-on-surface-variant">
                        Có. Bạn có thể vào phần "Quản lý tin đăng" trong trang cá nhân, chọn sản phẩm cần sửa và nhấn nút "Chỉnh sửa".
                    </p>
                </div>

                <!-- FAQ Item 3 -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Giao dịch có an toàn không?</h3>
                    <p class="text-body-md text-on-surface-variant">
                        Chúng tôi khuyến khích người dùng giao dịch trực tiếp, kiểm tra kỹ hàng hóa trước khi thanh toán. Hệ thống đánh giá người dùng (Rating) cũng giúp bạn tham khảo uy tín của người bán/người mua trước khi quyết định giao dịch.
                    </p>
                </div>
            </div>

            <div class="mt-10 p-6 bg-surface-container rounded-xl text-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Không tìm thấy câu trả lời?</h3>
                <p class="text-body-md text-on-surface-variant mb-4">Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giúp đỡ bạn.</p>
                <a href="{{ route('pages.contact') }}" class="inline-block bg-primary text-on-primary px-6 py-2 rounded-lg font-label-md hover:opacity-90 transition-opacity">Liên hệ hỗ trợ</a>
            </div>
        </div>
    </main>
</x-app-layout>
