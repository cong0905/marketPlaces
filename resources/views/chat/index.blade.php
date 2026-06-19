<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[calc(100vh-140px)]">
        <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 h-full flex overflow-hidden">
            
            <!-- Conversations List -->
            <div class="w-full md:w-1/3 flex flex-col h-full border-r border-outline-variant/30">
                <div class="p-4 border-b border-outline-variant/30">
                    <h2 class="text-xl font-bold text-on-surface">Tin nhắn</h2>
                </div>
                
                <div class="flex-1 overflow-y-auto custom-scrollbar" x-data="chatIndexComponent()">
                    @forelse($conversations as $conv)
                        @php 
                            $otherUser = $conv->getOtherUser(auth()->user());
                            $unreadCount = $conv->unreadCountFor(auth()->user());
                        @endphp
                        <a href="{{ route('chat.show', $conv->id) }}" class="flex items-center gap-4 p-4 border-b border-outline-variant/30 hover:bg-surface-container-low transition-colors {{ request()->route('conversation') == $conv->id ? 'bg-primary-container/20 border-l-4 border-primary' : '' }}">
                            <div class="relative">
                                <img src="{{ $otherUser->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover border border-outline-variant/30">
                                @if($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 bg-error text-on-error text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-surface">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h3 class="font-semibold text-on-surface truncate">{{ $otherUser->name }}</h3>
                                    <span class="text-xs text-on-surface-variant/70 whitespace-nowrap">{{ $conv->last_message_at?->diffForHumans(null, true, true) }}</span>
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <p class="text-sm text-on-surface-variant truncate flex-1 {{ $unreadCount > 0 ? 'font-semibold text-on-surface' : '' }}">
                                        @if($conv->latestMessage)
                                            {{ $conv->latestMessage->sender_id === auth()->id() ? 'Bạn: ' : '' }}
                                            {!! Str::limit(strip_tags($conv->latestMessage->body), 30) !!}
                                        @else
                                            Chưa có tin nhắn
                                        @endif
                                    </p>
                                </div>
                                <div class="text-xs text-primary mt-1 truncate">
                                    <span class="font-medium">Sản phẩm:</span> {{ $conv->product->title }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            Bạn chưa có cuộc trò chuyện nào.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Area (Hidden on mobile when on index) -->
            <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-surface-container-lowest">
                <svg class="w-20 h-20 text-outline-variant mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <h3 class="text-lg font-medium text-on-surface-variant">Chọn một cuộc trò chuyện để bắt đầu</h3>
            </div>

        </div>
    </div>
    
    <script>
        function chatIndexComponent() {
            return {
                init() {
                    console.log("Khởi tạo danh sách Chat Toàn cầu...");
                    if (window.Echo) {
                        window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                            .listen('MessageSent', (e) => {
                                console.log("CÓ TIN NHẮN MỚI TỪ KÊNH TOÀN CẦU! Tải lại danh sách...");
                                // Tải lại trang nhẹ nhàng để đẩy tin nhắn lên đầu và cập nhật số chấm đỏ
                                window.location.reload();
                            });
                    }
                }
            }
        }
    </script>
</x-app-layout>
