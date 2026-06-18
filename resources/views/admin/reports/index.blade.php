<x-admin-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Báo cáo vi phạm</h1>
            <p class="mt-1 text-sm text-gray-500">Xử lý các khiếu nại, báo cáo từ người dùng.</p>
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
            <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors {{ $status === 'pending' ? 'border-yellow-500 text-yellow-600 dark:text-yellow-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Cần xử lý
            </a>
            <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm transition-colors {{ $status === 'resolved' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Đã giải quyết
            </a>
        </nav>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Đối tượng bị báo cáo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lý do & Mô tả</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Người báo cáo</th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($reports as $report)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors" x-data="{ showModal: false }">
                            <td class="px-6 py-4">
                                @if($report->reportable_type === 'App\Models\Product')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mb-1">Tin đăng</span>
                                    <a href="{{ route('admin.products.show', $report->reportable_id) }}" class="block font-medium text-indigo-600 hover:text-indigo-900 truncate max-w-[200px]" target="_blank">
                                        {{ $report->reportable->title ?? 'Sản phẩm không còn tồn tại' }}
                                    </a>
                                @elseif($report->reportable_type === 'App\Models\User')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 mb-1">Người dùng</span>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $report->reportable->name ?? 'User không còn tồn tại' }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-red-600 mb-1">{{ $report->reason->label() }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate" title="{{ $report->description }}">{{ $report->description }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $report->reporter->name }}<br>
                                <span class="text-xs">{{ $report->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($status === 'pending')
                                    <button @click="showModal = true" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-md text-xs font-medium transition-colors">
                                        Xử lý ngay
                                    </button>

                                    <!-- Resolve Modal -->
                                    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div x-show="showModal" @click="showModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                                            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                                <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST">
                                                    @csrf @method('PUT')
                                                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Xử lý báo cáo</h3>
                                                        <p class="text-sm text-gray-500 mb-4">Nội dung báo cáo: "{{ $report->description }}"</p>
                                                        
                                                        <div class="mb-4">
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hành động</label>
                                                            <select name="action" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-500">
                                                                @if($report->reportable_type === 'App\Models\Product')
                                                                    <option value="hide_product">Ẩn/Khóa sản phẩm vi phạm</option>
                                                                @endif
                                                                <option value="warn_user">Cảnh cáo người dùng</option>
                                                                <option value="dismiss">Báo cáo sai, bỏ qua</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ghi chú của Admin</label>
                                                            <textarea name="admin_note" rows="3" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-500"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse gap-2">
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-lg bg-indigo-600 px-4 py-2 text-base font-medium text-white hover:bg-indigo-700 sm:w-auto sm:text-sm">Hoàn tất xử lý</button>
                                                        <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg bg-white px-4 py-2 text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Hủy</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Đã xử lý</span>
                                    <div class="text-xs text-gray-400 mt-1" title="{{ $report->admin_note }}">Ghi chú: {{ Str::limit($report->admin_note, 20) }}</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Không có báo cáo nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $reports->appends(['status' => $status])->links() }}
        </div>
    </div>
</x-admin-layout>
