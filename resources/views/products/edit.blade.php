<x-app-layout>
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-xl">
        <div class="mb-lg flex items-center justify-between">
            <h1 class="text-headline-lg font-headline-lg text-on-surface">Cập nhật tin đăng</h1>
        </div>

        @if($errors->any())
            <div class="mb-lg bg-error-container border-l-4 border-error p-md rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="material-symbols-outlined text-error">error</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-label-md font-label-md text-on-error-container">Vui lòng kiểm tra lại các lỗi sau:</h3>
                        <ul class="mt-2 text-body-sm text-on-error-container list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden" x-data="imageUploader()">
            @csrf
            @method('PUT')

            <!-- Form Sections -->
            <div class="p-lg md:p-xl space-y-xl">
                
                <!-- 1. General Info -->
                <section>
                    <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary-container text-on-primary-container text-label-md">1</span>
                        Thông tin cơ bản
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
                        <div class="col-span-full">
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Tiêu đề tin đăng <span class="text-error">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $product->title) }}" required placeholder="VD: iPhone 14 Pro Max 256GB Chính hãng" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">
                        </div>

                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Danh mục <span class="text-error">*</span></label>
                            <x-custom-select name="category_id" :options="$categoryOptions" :selected="old('category_id', $product->category_id)" placeholder="Chọn danh mục" :required="true" />
                        </div>

                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Giá bán (VNĐ) <span class="text-error">*</span></label>
                            <div class="relative">
                                <input type="number" name="price" value="{{ old('price', $product->price) }}" required min="1000" step="1000" class="w-full pl-4 pr-12 rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-on-surface-variant text-body-sm">₫</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Số lượng <span class="text-error">*</span></label>
                            <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" required min="0" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">
                        </div>

                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Tình trạng (%) <span class="text-error">*</span></label>
                            <input type="number" name="condition_percent" value="{{ old('condition_percent', $product->condition_percent) }}" required min="1" max="100" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">
                            <p class="mt-1 text-body-sm text-on-surface-variant">100 = Mới nguyên, 95 = Như mới, 80 = Cũ</p>
                        </div>

                        <div class="flex items-center pt-md">
                            <input type="hidden" name="is_negotiable" value="0">
                            <input type="checkbox" name="is_negotiable" value="1" id="is_negotiable" {{ old('is_negotiable', $product->is_negotiable) ? 'checked' : '' }} class="rounded border-outline-variant text-primary shadow-sm focus:ring-primary bg-surface">
                            <label for="is_negotiable" class="ml-2 block text-label-md font-label-md text-on-surface">Cho phép thương lượng giá</label>
                        </div>
                    </div>
                </section>

                <hr class="border-outline-variant">

                <!-- 2. Detail Info -->
                <section>
                    <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary-container text-on-primary-container text-label-md">2</span>
                        Chi tiết sản phẩm
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md">
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Thương hiệu / Hãng</label>
                            <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" placeholder="VD: Apple, Samsung" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Dòng sản phẩm</label>
                            <input type="text" name="model" value="{{ old('model', $product->model) }}" placeholder="VD: iPhone 14 Pro Max" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">
                        </div>
                    </div>

                    <div>
                        <label class="block text-label-md font-label-md text-on-surface mb-xs">Mô tả chi tiết <span class="text-error">*</span></label>
                        <textarea name="description" rows="6" required placeholder="Mô tả chi tiết tình trạng, phụ kiện đi kèm, thời gian bảo hành..." class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface">{{ old('description', $product->description) }}</textarea>
                    </div>
                </section>

                <hr class="border-outline-variant">

                <!-- 3. Location & Images -->
                <section>
                    <h2 class="text-headline-sm font-headline-sm text-on-surface mb-md flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-primary-container text-on-primary-container text-label-md">3</span>
                        Hình ảnh & Giao dịch
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-md" x-data="locationSelector('{{ old('province_id', $product->province_id) }}', '{{ old('district_id', $product->district_id) }}')">
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Tỉnh/Thành phố <span class="text-error">*</span></label>
                            <div class="relative">
                                <select name="province_id" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface appearance-none pr-10" required :disabled="isLoading" @change="onProvinceChange($event)" x-model="provinceId">
                                    <option value="">-- Chọn khu vực --</option>
                                    <template x-for="prov in provinces" :key="prov.id">
                                        <option :value="prov.id" x-text="prov.name"></option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none" x-show="isLoading">
                                    <span class="material-symbols-outlined animate-spin text-on-surface-variant">autorenew</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface mb-xs">Quận/Huyện</label>
                            <select name="district_id" class="w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface appearance-none" :disabled="!districts.length || isLoading" x-model="districtId">
                                <option value="">-- Chọn Quận/Huyện --</option>
                                <template x-for="dist in districts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Existing Images -->
                    @if($product->images->count() > 0)
                    <div class="mb-md">
                        <label class="block text-label-md font-label-md text-on-surface mb-xs">Ảnh hiện tại (Đánh dấu để xóa)</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-sm">
                            @foreach($product->images as $image)
                            <label class="relative aspect-square rounded-lg overflow-hidden border border-outline-variant cursor-pointer group">
                                <img src="{{ $image->url }}" class="object-cover w-full h-full">
                                <div class="absolute inset-0 bg-surface-dim bg-opacity-0 group-hover:bg-opacity-50 transition-opacity flex items-center justify-center">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="w-6 h-6 text-error rounded border-outline focus:ring-error shadow-sm">
                                </div>
                                @if($loop->first)
                                <div class="absolute top-1 left-1 bg-primary text-on-primary text-[10px] font-bold px-1.5 py-0.5 rounded">Ảnh bìa</div>
                                @endif
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Image Upload -->
                    <div>
                        <label class="block text-label-md font-label-md text-on-surface mb-xs">Thêm hình ảnh mới (Tối đa 5 ảnh)</label>
                        <div class="flex items-center justify-center w-full"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleFileDrop($event)"
                        >
                            <label :class="isDragging ? 'border-primary bg-primary-container' : 'border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low'" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-xl cursor-pointer transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <span class="material-symbols-outlined text-[40px] mb-3" :class="isDragging ? 'text-primary' : 'text-on-surface-variant'">cloud_upload</span>
                                    <p class="mb-2 text-body-sm text-on-surface-variant"><span class="font-bold">Bấm để tải ảnh lên</span> hoặc kéo thả</p>
                                    <p class="text-[12px] text-on-surface-variant">PNG, JPG or WEBP (Max: 5MB)</p>
                                </div>
                                <input type="file" id="images_input" name="new_images[]" multiple accept="image/*" class="hidden" @change="handleFileInput($event)">
                            </label>
                        </div>

                        <!-- Image Previews -->
                        <div class="mt-md grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-sm" x-show="images.length > 0" style="display: none;">
                            <template x-for="(image, index) in images" :key="index">
                                <div class="relative aspect-square rounded-lg overflow-hidden border border-outline-variant group">
                                    <img :src="image.url" class="object-cover w-full h-full" loading="lazy">
                                    <div class="absolute top-1 left-1 bg-secondary text-on-secondary text-[10px] font-bold px-1.5 py-0.5 rounded">Ảnh mới</div>
                                    <button type="button" @click.prevent="removeImage(index)" class="absolute top-1 right-1 w-6 h-6 bg-error text-on-error rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="material-symbols-outlined text-[14px]">close</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </section>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('locationSelector', (initialProvinceId, initialDistrictId) => ({
                            provinceId: initialProvinceId,
                            districtId: initialDistrictId,
                            provinces: [],
                            districts: [],
                            isLoading: true,
                            
                            init() {
                                fetch('/api/locations')
                                    .then(res => res.json())
                                    .then(data => {
                                        this.provinces = data;
                                        this.isLoading = false;
                                        
                                        if (this.provinceId) {
                                            const selectedProv = this.provinces.find(p => p.id == this.provinceId);
                                            if (selectedProv) {
                                                this.districts = selectedProv.districts;
                                            }
                                        }
                                    })
                                    .catch(err => {
                                        console.error('Failed to load locations:', err);
                                        this.isLoading = false;
                                    });
                            },
                            
                            onProvinceChange(e) {
                                const selectedId = e.target.value;
                                this.provinceId = selectedId;
                                this.districtId = '';
                                this.districts = [];
                                
                                if (selectedId) {
                                    const selectedProv = this.provinces.find(p => p.id == selectedId);
                                    if (selectedProv) {
                                        this.districts = selectedProv.districts;
                                    }
                                }
                            }
                        }));

                        Alpine.data('imageUploader', () => ({
                            images: [],
                            isDragging: false,
                            handleFileDrop(e) {
                                this.isDragging = false;
                                if (e.dataTransfer.files.length > 0) {
                                    this.addFiles(e.dataTransfer.files);
                                }
                            },
                            handleFileInput(e) {
                                if (e.target.files.length > 0) {
                                    this.addFiles(e.target.files);
                                }
                            },
                            addFiles(files) {
                                for (let i = 0; i < files.length; i++) {
                                    if (this.images.length < 5) {
                                        this.images.push({
                                            url: URL.createObjectURL(files[i]),
                                            file: files[i]
                                        });
                                    }
                                }
                                this.updateInput();
                            },
                            removeImage(index) {
                                this.images.splice(index, 1);
                                this.updateInput();
                            },
                            updateInput() {
                                const dataTransfer = new DataTransfer();
                                this.images.forEach(img => dataTransfer.items.add(img.file));
                                document.getElementById('images_input').files = dataTransfer.files;
                            }
                        }))
                    })
                </script>

            </div>

            <!-- Footer Actions -->
            <div class="px-lg py-md bg-surface-container-low border-t border-outline-variant flex items-center justify-end gap-sm">
                <button type="button" onclick="history.back()" class="px-6 py-2 bg-surface border border-outline text-on-surface rounded-full text-label-md font-label-md hover:bg-surface-container-low transition-colors">
                    Hủy bỏ
                </button>
                <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded-full text-label-md font-label-md hover:opacity-90 shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Cập nhật tin
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
