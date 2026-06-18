<x-admin-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý Người dùng</h1>
            <p class="mt-1 text-sm text-gray-500">Xem danh sách và thông tin hoạt động của tất cả người dùng.</p>
        </div>
        
        <!-- Search -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full sm:w-auto relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc email..." class="w-full sm:w-64 pl-10 pr-4 py-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </form>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thành viên</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Vai trò</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Tin đã đăng</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Mua / Bán</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Uy tín</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar_url }}" alt="" class="w-10 h-10 rounded-full border border-gray-200">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Quản trị viên</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Người dùng</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-gray-300">
                                {{ $user->products_count }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                <span class="text-blue-600">{{ $user->buyer_orders_count }}</span> / <span class="text-green-600">{{ $user->seller_orders_count }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-yellow-600 flex items-center gap-1">
                                    {{ $user->rating > 0 ? number_format($user->rating, 1) : '--' }} ⭐
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn xử lý tài khoản này?');">
                                    @csrf @method('PUT')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-200 text-red-600 hover:bg-red-50 rounded-md text-xs font-medium transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        Khóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">Không tìm thấy người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $users->links() }}
        </div>
    </div>
</x-admin-layout>
