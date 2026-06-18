<x-admin-layout>
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index', ['status' => $product->status->value]) }}" class="p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kiểm duyệt sản phẩm</h1>
                <p class="mt-1 text-sm text-gray-500">Mã SP: #{{ $product->id }}</p>
            </div>
        </div>
        
        <!-- Status Badge -->
        <span class="px-3 py-1.5 rounded-full text-sm font-medium bg-{{ $product->status->color() }}-100 text-{{ $product->status->color() }}-800 border border-{{ $product->status->color() }}-200">
            {{ $product->status->label() }}
        </span>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Product Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $product->title }}</h2>
                <div class="text-2xl font-bold text-red-600 mb-6">{{ $product->formatted_price }}</div>
                
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Mô tả chi tiết:</h3>
                <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 text-sm whitespace-pre-wrap bg-gray-50 dark:bg-gray-900 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                    {{ $product->description }}
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                        <span class="block text-xs text-gray-500 mb-1">Danh mục</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $product->category->name }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                        <span class="block text-xs text-gray-500 mb-1">Thương hiệu / Dòng</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $product->brand ?: 'Không rõ' }} / {{ $product->model ?: 'Không rõ' }}</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                        <span class="block text-xs text-gray-500 mb-1">Tình trạng</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $product->condition_label }} ({{ $product->condition_percent }}%)</span>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                        <span class="block text-xs text-gray-500 mb-1">Thương lượng</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $product->is_negotiable ? 'Có' : 'Không' }}</span>
                    </div>
                </div>
            </div>

            <!-- Images Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Hình ảnh đính kèm ({{ $product->images->count() }})</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($product->images as $image)
                        <a href="{{ asset('storage/' . $image->path) }}" target="_blank" class="block aspect-square rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 hover:opacity-75 transition-opacity">
                            <img src="{{ asset('storage/' . $image->path) }}" alt="Ảnh" class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right: Actions & User Info -->
        <div class="space-y-6">
            
            <!-- Actions Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700" x-data="{ showRejectModal: false }">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quyết định kiểm duyệt</h3>
                
                @if($product->status->value === 'pending')
                    <div class="space-y-3">
                        <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button type="submit" class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md transition-colors flex justify-center items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Phê duyệt tin này
                            </button>
                        </form>
                        
                        <button @click="showRejectModal = true" type="button" class="w-full py-3 px-4 bg-white dark:bg-gray-700 border-2 border-red-200 dark:border-red-900 hover:bg-red-50 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 font-bold rounded-xl transition-colors flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            Từ chối tin đăng
                        </button>
                    </div>

                    <!-- Reject Modal -->
                    <div x-show="showRejectModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            
                            <div x-show="showRejectModal" @click="showRejectModal = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            
                            <div x-show="showRejectModal" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">Lý do từ chối</h3>
                                                <div class="mt-4">
                                                    <textarea name="rejection_reason" rows="4" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-red-500 focus:border-red-500 shadow-sm" placeholder="Nhập lý do chi tiết để người bán biết cách khắc phục..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                                        <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                            Xác nhận Từ Chối
                                        </button>
                                        <button type="button" @click="showRejectModal = false" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                            Hủy bỏ
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl {{ $product->status->value === 'active' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                        <p class="font-medium">Tin này đã được xử lý.</p>
                        @if($product->rejection_reason)
                            <p class="mt-2 text-sm"><strong>Lý do từ chối:</strong> {{ $product->rejection_reason }}</p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Seller Info -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Thông tin người đăng</h3>
                <div class="flex items-center gap-4 mb-4">
                    <img src="{{ $product->user->avatar_url }}" alt="" class="w-14 h-14 rounded-full border border-gray-200">
                    <div>
                        <div class="font-bold text-gray-900 dark:text-white">{{ $product->user->name }}</div>
                        <div class="text-sm text-gray-500">Tham gia: {{ $product->user->created_at->format('m/Y') }}</div>
                    </div>
                </div>
                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $product->user->email }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $product->user->phone }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $product->location_district ? $product->location_district . ', ' : '' }}{{ $product->location_province }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin-layout>
