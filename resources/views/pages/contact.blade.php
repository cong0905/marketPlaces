<x-app-layout title="Liên hệ - Amber Marketplace" description="Liên hệ với đội ngũ hỗ trợ của Amber Marketplace.">
    <main class="max-w-3xl mx-auto px-margin-mobile md:px-margin-desktop py-xl md:py-2xl">
        <h1 class="text-display-lg font-display-lg text-on-surface mb-lg">Liên hệ</h1>
        
        <div class="prose prose-lg prose-zinc dark:prose-invert max-w-none">
            <p class="text-body-lg text-on-surface-variant mb-8">
                Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy liên hệ với chúng tôi qua các kênh dưới đây.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="bg-surface-container border border-outline-variant rounded-xl p-6">
                    <span class="material-symbols-outlined text-[32px] text-primary mb-4" style="font-variation-settings: 'FILL' 1;">mail</span>
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Email Hỗ trợ</h3>
                    <p class="text-body-md text-on-surface-variant">support@amber.vn</p>
                    <p class="text-body-sm text-on-surface-variant mt-2">Phản hồi trong vòng 24 giờ</p>
                </div>
                
                <div class="bg-surface-container border border-outline-variant rounded-xl p-6">
                    <span class="material-symbols-outlined text-[32px] text-primary mb-4" style="font-variation-settings: 'FILL' 1;">call</span>
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">Hotline</h3>
                    <p class="text-body-md text-on-surface-variant">1900 1234</p>
                    <p class="text-body-sm text-on-surface-variant mt-2">Thứ 2 - Thứ 6 (8:00 - 18:00)</p>
                </div>
            </div>

            <h2 class="text-headline-md font-headline-md text-on-surface mt-10 mb-4">Gửi tin nhắn trực tiếp</h2>
            <form action="#" method="GET" class="space-y-4 max-w-xl bg-surface-container-lowest p-6 border border-outline-variant rounded-xl">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1">Họ và tên</label>
                    <input type="text" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary text-body-md" placeholder="Nhập tên của bạn">
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1">Email</label>
                    <input type="email" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary text-body-md" placeholder="Nhập địa chỉ email">
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1">Nội dung</label>
                    <textarea rows="4" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary text-body-md" placeholder="Nội dung cần hỗ trợ..."></textarea>
                </div>
                <button type="button" class="bg-primary text-on-primary px-6 py-3 rounded-lg font-label-md hover:opacity-90 transition-opacity">Gửi tin nhắn</button>
            </form>
        </div>
    </main>
</x-app-layout>
