<x-admin-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Quản lý tin đăng</h1>
            <p class="mt-1 text-sm text-gray-500">Kiểm duyệt và quản lý các sản phẩm trên hệ thống.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 overflow-hidden">
        <nav class="flex -mb-px" aria-label="Tabs">
            <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors {{ $status === 'pending' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Chờ duyệt
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'active']) }}" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors {{ $status === 'active' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Đang bán
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'rejected']) }}" class="w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors {{ $status === 'rejected' ? 'border-red-500 text-red-600 dark:text-red-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Bị từ chối
            </a>
        </nav>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sản phẩm</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Thông tin</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Người bán</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $product->primary_image_medium_url }}" alt="" class="w-12 h-12 rounded object-cover bg-gray-100">
                                    <div class="max-w-[250px]">
                                        <div class="font-medium text-gray-900 dark:text-white truncate" title="{{ $product->title }}">{{ $product->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->category->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-red-600 font-bold">{{ $product->formatted_price }}</div>
                                <div class="text-sm text-gray-500">{{ $product->condition_label }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $product->location_province }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $product->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm transition-colors">
                                    Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                Không có sản phẩm nào trong danh sách này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $products->appends(['status' => $status])->links() }}
        </div>
    </div>
</x-admin-layout>
