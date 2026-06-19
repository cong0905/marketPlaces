<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[calc(100vh-140px)]">
        <div class="bg-surface rounded-2xl shadow-sm border border-outline-variant/30 h-full flex overflow-hidden">
            
            <!-- Conversations List (Sidebar for Desktop) -->
            <div class="hidden md:flex flex-col w-1/3 border-r border-outline-variant/30 h-full bg-surface-container-lowest">
                <div class="p-4 border-b border-outline-variant/30 bg-surface">
                    <h2 class="text-xl font-bold text-on-surface">Tin nhắn</h2>
                </div>
                
                @php
                    // Fetch conversations for sidebar
                    $conversations = \App\Models\Conversation::where('buyer_id', auth()->id())
                        ->orWhere('seller_id', auth()->id())
                        ->with(['buyer', 'seller', 'product', 'latestMessage'])
                        ->orderByDesc('last_message_at')
                        ->get();
                @endphp

                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    @foreach($conversations as $conv)
                        @php 
                            $otherUserInList = $conv->getOtherUser(auth()->user());
                            $unreadCount = $conv->unreadCountFor(auth()->user());
                            $isActive = request()->route('conversation')->id === $conv->id;
                        @endphp
                        <a href="{{ route('chat.show', $conv->id) }}" class="flex items-center gap-4 p-4 border-b border-outline-variant/30 transition-colors {{ $isActive ? 'bg-primary-container/20 border-l-4 border-primary' : 'hover:bg-surface-container-low' }}">
                            <div class="relative">
                                <img src="{{ $otherUserInList->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover border border-outline-variant/30">
                                @if($unreadCount > 0 && !$isActive)
                                    <span class="absolute -top-1 -right-1 bg-error text-on-error text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-surface">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h3 class="font-semibold text-on-surface truncate">{{ $otherUserInList->name }}</h3>
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <p class="text-sm text-on-surface-variant truncate flex-1 {{ ($unreadCount > 0 && !$isActive) ? 'font-semibold text-on-surface' : '' }}">
                                        @if($conv->latestMessage)
                                            {{ $conv->latestMessage->sender_id === auth()->id() ? 'Bạn: ' : '' }}
                                            {!! Str::limit(strip_tags($conv->latestMessage->body), 30) !!}
                                        @else
                                            Chưa có tin nhắn
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Chat Area -->
            <div class="w-full md:w-2/3 flex flex-col h-full bg-surface-container-lowest" x-data="chatComponent()">
                
                <!-- Chat Header -->
                <div class="bg-surface p-4 border-b border-outline-variant/30 flex items-center justify-between shrink-0 shadow-sm z-10">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('chat.index') }}" class="md:hidden text-on-surface-variant hover:text-on-surface">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <img src="{{ $otherUser->avatar_url }}" alt="" class="w-10 h-10 rounded-full border border-outline-variant/30">
                        <div>
                            <h3 class="font-bold text-on-surface flex items-center gap-1">{{ $otherUser->name }}</h3>
                            <div class="flex flex-wrap gap-1 my-1">
                                @foreach($otherUser->seller_badges as $badge)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider {{ $badge['color'] }}" title="{{ $badge['label'] }}">
                                        {!! $badge['icon'] !!}
                                        {{ $badge['label'] }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="text-[10px] text-green-500 font-medium flex items-center gap-1 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Đang hoạt động
                            </div>
                        </div>
                    </div>
                    <!-- Product Info Snippet -->
                    <a href="{{ route('products.show', $conversation->product->slug) }}" class="hidden sm:flex items-center gap-3 bg-surface-container-low px-3 py-1.5 rounded-lg border border-outline-variant/50 hover:bg-surface-container transition-colors">
                        <img src="{{ $conversation->product->primary_image_medium_url }}" alt="" class="w-10 h-10 rounded object-cover">
                        <div>
                            <div class="text-xs font-semibold text-on-surface truncate max-w-[150px]">{{ $conversation->product->title }}</div>
                            <div class="text-xs text-error font-bold">{{ $conversation->product->formatted_price }}</div>
                        </div>
                    </a>
                </div>

                <!-- Product Info Snippet (Mobile) -->
                <a href="{{ route('products.show', $conversation->product->slug) }}" class="sm:hidden flex items-center justify-between bg-surface p-3 border-b border-outline-variant/30 shrink-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ $conversation->product->primary_image_medium_url }}" alt="" class="w-10 h-10 rounded object-cover">
                        <div>
                            <div class="text-xs font-semibold text-on-surface truncate max-w-[200px]">{{ $conversation->product->title }}</div>
                            <div class="text-xs text-error font-bold">{{ $conversation->product->formatted_price }}</div>
                        </div>
                    </div>
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>

                <!-- Messages Area -->
                <div class="flex-1 p-4 overflow-y-auto custom-scrollbar" x-ref="chatContainer">
                    <div class="space-y-4 flex flex-col justify-end min-h-full">
                        
                        <template x-for="(msg, index) in messages" :key="msg.id">
                            <div class="flex w-full" :class="msg.sender_id === authId ? 'justify-end' : 'justify-start'">
                                <div class="flex gap-2 max-w-[80%]" :class="msg.sender_id === authId ? 'flex-row-reverse' : 'flex-row'">
                                    
                                    <img x-show="msg.sender_id !== authId && (index === 0 || messages[index-1].sender_id === authId)" :src="msg.sender.avatar_url" class="w-8 h-8 rounded-full self-end mb-1">
                                    <div x-show="msg.sender_id !== authId && index > 0 && messages[index-1].sender_id !== authId" class="w-8 h-8"></div>
                                    
                                    <div class="flex flex-col" :class="msg.sender_id === authId ? 'items-end' : 'items-start'">
                                        <div class="px-4 py-2.5 rounded-2xl shadow-sm text-sm whitespace-pre-wrap break-words"
                                             :class="msg.sender_id === authId ? 'bg-primary text-on-primary rounded-br-none' : 'bg-surface-container text-on-surface rounded-bl-none border border-outline-variant/30'">
                                            <span x-text="msg.body" class="whitespace-pre-wrap break-words"></span>
                                        </div>
                                        <span class="text-[10px] text-on-surface-variant/70 mt-1" x-text="formatTime(msg.created_at)"></span>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div x-show="messages.length === 0" class="text-center text-gray-500 py-10">
                            Hãy gửi lời chào đến người bán!
                        </div>
                    </div>
                </div>

                <!-- Message Input -->
                <div class="bg-surface p-4 border-t border-outline-variant/30 shrink-0">
                    <form @submit.prevent="sendMessage" class="flex gap-2 items-end">
                        <div class="relative flex-1">
                            <textarea x-model="newMessage" @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()" rows="1" class="w-full pl-4 pr-10 py-3 rounded-xl border-outline-variant bg-surface text-on-surface focus:ring-primary focus:border-primary shadow-sm resize-none custom-scrollbar" placeholder="Nhập tin nhắn..." style="max-height: 120px;"></textarea>
                        </div>
                        <button type="submit" :disabled="newMessage.trim() === '' || isSending" class="p-3 bg-primary hover:opacity-90 text-on-primary rounded-xl shadow-md transition-opacity disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg x-show="!isSending" class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            <svg x-show="isSending" class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Alpine Component Script -->
    <script>
        function chatComponent() {
            return {
                messages: @json($conversation->messages->load('sender')),
                newMessage: '',
                conversationId: {{ $conversation->id }},
                authId: {{ auth()->id() }},
                isSending: false,

                init() {
                    this.scrollToBottom();
                    
                    console.log("Khởi tạo Chat... Kiểm tra hệ thống WebSockets.");
                    
                    // Listen to Reverb WebSocket Channel
                    if (window.Echo) {
                        console.log("Đã tìm thấy thư viện Echo! Bắt đầu kết nối tới kênh conversation." + this.conversationId);
                        window.Echo.private(`conversation.${this.conversationId}`)
                            .listen('MessageSent', (e) => {
                                console.log("NHẬN TIN NHẮN THEO THỜI GIAN THỰC:", e);
                                // Add message only if it's not sent by current user (to avoid duplication)
                                if(e.sender_id !== this.authId) {
                                    this.messages.push(e);
                                    this.scrollToBottom();
                                }
                            })
                            .error((err) => {
                                console.error("LỖI KẾT NỐI WEBSOCKET:", err);
                            });
                    } else {
                        console.error("LỖI NGHIÊM TRỌNG: Không tìm thấy window.Echo! (Javascript có thể chưa tải xong hoặc build bị lỗi)");
                    }
                },

                sendMessage() {
                    if (this.newMessage.trim() === '' || this.isSending) return;
                    
                    this.isSending = true;
                    
                    // Optimistic UI update
                    let tempMsg = {
                        id: Date.now(),
                        body: this.newMessage,
                        sender_id: this.authId,
                        created_at: new Date().toISOString(),
                        sender: {
                            id: this.authId,
                            avatar_url: '{{ auth()->user()->avatar_url }}'
                        }
                    };
                    
                    this.messages.push(tempMsg);
                    let body = this.newMessage;
                    this.newMessage = '';
                    this.scrollToBottom();

                    // Send to backend
                    axios.post(`/chat/${this.conversationId}`, {
                        body: body
                    }).then(response => {
                        // Replace temp msg with real one if needed, but Vue/Alpine is fine
                        this.isSending = false;
                    }).catch(error => {
                        console.error("Error sending message:", error);
                        this.isSending = false;
                        this.messages.pop(); // Remove if failed
                        this.newMessage = body;
                    });
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.chatContainer;
                        if(container) {
                            container.scrollTop = container.scrollHeight;
                        }
                    });
                },

                formatTime(datetime) {
                    const date = new Date(datetime);
                    return date.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });
                }
            }
        }
    </script>
</x-app-layout>
