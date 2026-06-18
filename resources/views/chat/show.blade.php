<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-[calc(100vh-140px)]">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 h-full flex overflow-hidden">
            
            <!-- Conversations List (Sidebar for Desktop) -->
            <div class="hidden md:flex flex-col w-1/3 border-r border-gray-100 dark:border-gray-700 h-full">
                <div class="p-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tin nhắn</h2>
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
                        <a href="{{ route('chat.show', $conv->id) }}" class="flex items-center gap-4 p-4 border-b border-gray-50 dark:border-gray-750 transition-colors {{ $isActive ? 'bg-indigo-50 dark:bg-gray-700' : 'hover:bg-gray-50 dark:hover:bg-gray-750' }}">
                            <div class="relative">
                                <img src="{{ $otherUserInList->avatar_url }}" alt="" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                                @if($unreadCount > 0 && !$isActive)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border-2 border-white dark:border-gray-800">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $otherUserInList->name }}</h3>
                                </div>
                                <div class="flex justify-between items-center gap-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate flex-1 {{ ($unreadCount > 0 && !$isActive) ? 'font-semibold text-gray-900 dark:text-white' : '' }}">
                                        @if($conv->latestMessage)
                                            {{ $conv->latestMessage->sender_id === auth()->id() ? 'Bạn: ' : '' }}
                                            {{ $conv->latestMessage->body }}
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
            <div class="w-full md:w-2/3 flex flex-col h-full bg-gray-50 dark:bg-gray-900/50" x-data="chatComponent()">
                
                <!-- Chat Header -->
                <div class="bg-white dark:bg-gray-800 p-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between shrink-0 shadow-sm z-10">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('chat.index') }}" class="md:hidden text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <img src="{{ $otherUser->avatar_url }}" alt="" class="w-10 h-10 rounded-full border border-gray-200">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $otherUser->name }}</h3>
                            <div class="text-xs text-green-500 font-medium flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> Đang hoạt động
                            </div>
                        </div>
                    </div>
                    <!-- Product Info Snippet -->
                    <a href="{{ route('products.show', $conversation->product->slug) }}" class="hidden sm:flex items-center gap-3 bg-gray-50 dark:bg-gray-700 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-100 transition-colors">
                        <img src="{{ $conversation->product->primary_image_medium_url }}" alt="" class="w-10 h-10 rounded object-cover">
                        <div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate max-w-[150px]">{{ $conversation->product->title }}</div>
                            <div class="text-xs text-red-600 font-bold">{{ $conversation->product->formatted_price }}</div>
                        </div>
                    </a>
                </div>

                <!-- Product Info Snippet (Mobile) -->
                <a href="{{ route('products.show', $conversation->product->slug) }}" class="sm:hidden flex items-center justify-between bg-white dark:bg-gray-800 p-3 border-b border-gray-100 dark:border-gray-700 shrink-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ $conversation->product->primary_image_medium_url }}" alt="" class="w-10 h-10 rounded object-cover">
                        <div>
                            <div class="text-xs font-semibold text-gray-900 dark:text-white truncate max-w-[200px]">{{ $conversation->product->title }}</div>
                            <div class="text-xs text-red-600 font-bold">{{ $conversation->product->formatted_price }}</div>
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
                                             :class="msg.sender_id === authId ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded-bl-none border border-gray-100 dark:border-gray-700'">
                                            <span x-text="msg.body"></span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 mt-1" x-text="formatTime(msg.created_at)"></span>
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
                <div class="bg-white dark:bg-gray-800 p-4 border-t border-gray-100 dark:border-gray-700 shrink-0">
                    <form @submit.prevent="sendMessage" class="flex gap-2 items-end">
                        <div class="relative flex-1">
                            <textarea x-model="newMessage" @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()" rows="1" class="w-full pl-4 pr-10 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm resize-none custom-scrollbar" placeholder="Nhập tin nhắn..." style="max-height: 120px;"></textarea>
                        </div>
                        <button type="submit" :disabled="newMessage.trim() === '' || isSending" class="p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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
                    
                    // Listen to Reverb WebSocket Channel
                    if (window.Echo) {
                        window.Echo.private(`conversation.${this.conversationId}`)
                            .listen('MessageSent', (e) => {
                                // Add message only if it's not sent by current user (to avoid duplication)
                                if(e.sender_id !== this.authId) {
                                    this.messages.push(e);
                                    this.scrollToBottom();
                                }
                            });
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
