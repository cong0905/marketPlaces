<nav x-data="navigationComponent" class="bg-surface/80 dark:bg-surface-dim/80 docked full-width top-0 sticky backdrop-blur-md border-b border-outline-variant dark:border-outline shadow-sm z-50">
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop h-20 flex justify-between items-center w-full">
        <div class="flex justify-between items-center w-full">
            
            <!-- Logo & Left Section -->
            <div class="flex items-center gap-sm">
                <a href="{{ route('home') }}" class="text-headline-md font-headline-md text-primary font-bold flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 28px;">storefront</span>
                    <span>Amber</span>
                </a>
            </div>

            <!-- Central Search Bar (Hidden on mobile, shown on md+) -->
            <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                <form action="{{ route('products.index') }}" method="GET" class="w-full relative flex items-center bg-surface rounded-full shadow-sm border border-outline-variant focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary-container transition-all">
                    <span class="material-symbols-outlined text-outline ml-4">search</span>
                    <input type="text" name="keyword" placeholder="Tìm kiếm sản phẩm..." class="flex-1 bg-transparent border-none focus:ring-0 text-body-md font-body-md px-4 py-2 placeholder:text-outline-variant outline-none" value="{{ request('keyword') }}">
                    <button type="submit" class="bg-primary text-on-primary rounded-full px-4 py-2 text-label-md font-label-md flex items-center gap-xs hover:opacity-90 transition-opacity mr-1 my-1">
                        Tìm
                    </button>
                </form>
            </div>

            <!-- Right Section (Auth & Actions) -->
            <div class="hidden sm:flex sm:items-center sm:gap-4">
                
                <!-- Post Ad Button -->
                <a href="{{ route('products.create') }}" class="text-label-md font-label-md bg-primary-container text-on-primary-container px-6 py-2 rounded-lg font-bold shadow-[0_2px_0_0_#e6a100] active:scale-95 transition-transform duration-100 flex items-center gap-sm">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                    <span>Đăng Tin</span>
                </a>

                @auth
                    @php
                        $unreadNotifications = auth()->user()->unreadNotifications;
                        $allNotifications = auth()->user()->notifications()->latest()->take(10)->get();
                        $unreadCount = $unreadNotifications->count();
                    @endphp
                    <!-- Chat Icon -->
                    @php
                        $unreadMessagesCount = \App\Models\Message::whereHas('conversation', function($q) {
                            $q->where('buyer_id', auth()->id())->orWhere('seller_id', auth()->id());
                        })->where('sender_id', '!=', auth()->id())->whereNull('read_at')->count();
                    @endphp
                    <a href="{{ route('chat.index') }}" class="p-2 text-outline hover:text-primary transition-colors relative focus:outline-none flex items-center justify-center" title="Tin nhắn">
                        <span class="material-symbols-outlined" style="font-size: 26px;">chat</span>
                        <template x-if="unreadMessagesCount > 0">
                            <span class="absolute top-1 right-1 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                            </span>
                        </template>
                    </a>

                    <!-- Notifications -->
                    <x-dropdown align="right" width="80" contentClasses="py-0 bg-surface-container-lowest border border-outline-variant shadow-xl overflow-hidden rounded-xl">
                        <x-slot name="trigger">
                            <button class="p-2 text-outline hover:text-primary transition-colors relative focus:outline-none">
                                <span class="material-symbols-outlined" style="font-size: 26px;">notifications</span>
                                @if($unreadCount > 0)
                                <span class="absolute top-1 right-1 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-error"></span>
                                </span>
                                @endif
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 bg-surface-container border-b border-outline-variant/40 flex items-center justify-between">
                                <p class="text-sm font-bold text-on-surface">Thông báo @if($unreadCount > 0)<span class="text-xs font-bold text-on-primary bg-error px-1.5 py-0.5 rounded-full ml-1">{{ $unreadCount }}</span>@endif</p>
                                @if($unreadCount > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-secondary hover:text-secondary/80 font-medium transition-colors">Đọc tất cả</button>
                                </form>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto divide-y divide-outline-variant/20">
                                @forelse($allNotifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" class="block px-4 py-3.5 hover:bg-surface-container-low transition-colors duration-150 {{ is_null($notification->read_at) ? 'bg-primary-container/5' : '' }}">
                                        <div class="flex items-start gap-3">
                                            <span class="material-symbols-outlined text-primary shrink-0 mt-0.5" style="font-size: 20px; font-variation-settings: 'FILL' 1;">{{ $notification->data['icon'] ?? 'notifications' }}</span>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-on-surface {{ is_null($notification->read_at) ? 'font-bold' : '' }}">{{ $notification->data['message'] ?? '' }}</p>
                                                <p class="text-xs text-outline mt-1 flex items-center gap-1">
                                                    <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                            @if(is_null($notification->read_at))
                                            <span class="w-2 h-2 rounded-full bg-secondary shrink-0 mt-2"></span>
                                            @endif
                                        </div>
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <span class="material-symbols-outlined text-outline-variant text-[40px] mb-2">notifications_off</span>
                                        <p class="text-sm text-on-surface-variant">Chưa có thông báo nào</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($allNotifications->count() > 0)
                            <div class="px-4 py-2.5 bg-surface-container-low border-t border-outline-variant/40 text-center">
                                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-secondary hover:text-secondary/80 transition-colors">Xem tất cả thông báo</a>
                            </div>
                            @endif
                        </x-slot>
                    </x-dropdown>

                    <!-- Settings Dropdown -->
                    <x-dropdown align="right" width="56" contentClasses="py-0 bg-surface-container-lowest border border-outline-variant shadow-xl overflow-hidden rounded-xl">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 p-1 border border-transparent rounded-full hover:bg-surface-container focus:outline-none transition ease-in-out duration-150">
                                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full border border-outline-variant shadow-sm">
                                <div class="hidden md:block font-bold text-sm text-on-surface">{{ Auth::user()->name }}</div>
                                <span class="material-symbols-outlined text-outline" style="font-size: 18px;">keyboard_arrow_down</span>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-outline-variant/40 bg-surface-container-low text-sm">
                                <p class="text-xs text-outline font-medium">Đăng nhập với tên</p>
                                <p class="font-bold text-on-surface truncate mt-0.5">{{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1">
                                @if(Auth::user()->isAdmin())
                                    <x-dropdown-link :href="route('admin.dashboard')" class="text-secondary font-bold flex items-center gap-2">
                                        <span class="material-symbols-outlined text-secondary" style="font-size: 18px;">dashboard</span>
                                        {{ __('Admin Dashboard') }}
                                    </x-dropdown-link>
                                @endif

                                <x-dropdown-link :href="route('dashboard')" class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-outline" style="font-size: 18px;">dashboard</span>
                                    {{ __('Bảng điều khiển') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('favorites.index')" class="flex items-center gap-2 text-error hover:bg-error-container/20">
                                    <span class="material-symbols-outlined text-error" style="font-size: 18px; font-variation-settings: 'FILL' 1;">favorite</span>
                                    {{ __('Danh sách yêu thích') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-outline" style="font-size: 18px;">settings</span>
                                    {{ __('Cài đặt tài khoản') }}
                                </x-dropdown-link>

                                <div class="border-t border-outline-variant/30 my-1"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="flex items-center gap-2 text-error hover:bg-error-container/20">
                                        <span class="material-symbols-outlined text-error" style="font-size: 18px;">logout</span>
                                        {{ __('Đăng xuất') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="hidden md:block text-label-md font-label-md text-primary bg-surface border border-outline px-4 py-2 rounded-lg hover:bg-surface-container-low transition-colors">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="text-label-md font-label-md text-primary hover:underline">Đăng ký</a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden gap-2">
                <a href="{{ route('products.create') }}" class="p-2 text-indigo-600 bg-indigo-50 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </a>
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-900 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Search Bar -->
        <div class="md:hidden pb-3">
                <form action="{{ route('products.index') }}" method="GET" class="w-full relative flex items-center bg-surface rounded-full border border-outline-variant focus-within:border-secondary focus-within:ring-2 focus-within:ring-secondary-container transition-all p-1">
                    <span class="material-symbols-outlined text-outline ml-2" style="font-size: 20px;">search</span>
                    <input type="text" name="keyword" placeholder="Tìm kiếm..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm font-body-sm px-2 py-1 placeholder:text-outline-variant outline-none">
                </form>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden shadow-inner bg-gray-50 dark:bg-gray-900/50">
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4 flex items-center gap-3">
                    <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-10 h-10 rounded-full border border-gray-200">
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    @if(Auth::user()->isAdmin())
                        <x-responsive-nav-link :href="route('admin.dashboard')">{{ __('Admin Dashboard') }}</x-responsive-nav-link>
                    @endif
                    <x-responsive-nav-link :href="route('dashboard')">{{ __('Bảng điều khiển') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('favorites.index')">{{ __('Danh sách yêu thích') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Cài đặt tài khoản') }}</x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Đăng xuất') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">Đăng nhập</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">Đăng ký</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>

@auth
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navigationComponent', () => ({
            open: false,
            userMenuOpen: false,
            unreadMessagesCount: {{ $unreadMessagesCount ?? 0 }},
            init() {
                // Đợi 1 giây để đảm bảo Vite đã tải xong window.Echo
                setTimeout(() => {
                    if (window.Echo) {
                        window.Echo.private(`App.Models.User.{{ auth()->id() }}`)
                            .listen('MessageSent', (e) => {
                                console.log("GLOBAL NAV: Có tin nhắn mới!");
                                this.unreadMessagesCount++;
                            });
                    }
                }, 1000);
            }
        }));
    });
</script>
@endauth
@guest
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navigationComponent', () => ({
            open: false,
            userMenuOpen: false,
            unreadMessagesCount: 0
        }));
    });
</script>
@endguest
