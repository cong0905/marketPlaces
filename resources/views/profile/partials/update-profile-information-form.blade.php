<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-on-surface">
            {{ __('Thông tin cá nhân') }}
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant">
            {{ __("Cập nhật thông tin hồ sơ, ảnh đại diện, địa chỉ và số điện thoại của bạn.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data" x-data="profileForm()">
        @csrf
        @method('patch')

        <!-- Avatar Upload Section -->
        <div class="flex items-center gap-6 p-4 bg-surface-container-low border border-outline-variant rounded-xl shadow-sm">
            <div class="relative w-20 h-20 rounded-full overflow-hidden border border-outline-variant bg-surface group flex-shrink-0">
                <img :src="avatarUrl" class="w-full h-full object-cover">
                <label for="avatar-input" class="absolute inset-0 bg-black/40 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-white text-[24px]">photo_camera</span>
                </label>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('avatar-input').click()" class="px-4 py-2 bg-primary text-on-primary rounded-full text-label-md font-label-md hover:opacity-90 shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none transition-all">
                    Thay ảnh đại diện
                </button>
                <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" @change="previewAvatar($event)">
                <p class="text-[12px] text-on-surface-variant mt-1.5">Hỗ trợ PNG, JPG, WEBP (Tối đa 2MB)</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md">
            <div>
                <x-input-label for="name" :value="__('Họ và tên')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-on-surface">
                            {{ __('Địa chỉ email của bạn chưa được xác minh.') }}
                            <button form="send-verification" class="underline text-sm text-on-surface-variant hover:text-primary rounded-md focus:outline-none">
                                {{ __('Bấm vào đây để gửi lại email xác minh.') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-primary">
                                {{ __('Một liên kết xác minh mới đã được gửi đến địa chỉ email của bạn.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="phone" :value="__('Số điện thoại')" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="VD: 0987654321" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="address" :value="__('Địa chỉ cụ thể')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" placeholder="VD: Số 12, Ngõ 34, Đường Nguyễn Trãi" />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>
        </div>

        <!-- Province & District selection using Alpine.js & open-api.vn -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-md" x-data="locationSelector('{{ old('location_province', $user->location_province) }}', '{{ old('location_district', $user->location_district) }}')">
            <div>
                <x-input-label for="province-select" :value="__('Tỉnh / Thành phố')" />
                <div class="relative mt-1">
                    <select id="province-select" name="location_province" class="block w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface appearance-none pr-10" :disabled="isLoading" @change="onProvinceChange($event)" x-model="provinceName">
                        <option value="">-- Chọn Tỉnh / Thành phố --</option>
                        <template x-for="prov in provinces" :key="prov.code">
                            <option :value="prov.name" x-text="prov.name"></option>
                        </template>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none" x-show="isLoading">
                        <span class="material-symbols-outlined animate-spin text-on-surface-variant">autorenew</span>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('location_province')" />
            </div>

            <div>
                <x-input-label for="district-select" :value="__('Quận / Huyện')" />
                <select id="district-select" name="location_district" class="mt-1 block w-full rounded-lg border-outline-variant bg-surface focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface" :disabled="!districts.length || isLoading" @change="onDistrictChange($event)" x-model="districtName">
                    <option value="">-- Chọn Quận / Huyện --</option>
                    <template x-for="dist in districts" :key="dist.code">
                        <option :value="dist.name" x-text="dist.name"></option>
                    </template>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('location_district')" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-6 py-2 bg-primary text-on-primary rounded-full text-label-md font-label-md hover:opacity-90 shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none transition-all flex items-center gap-1">
                <span class="material-symbols-outlined text-[18px]">save</span>
                {{ __('Lưu thay đổi') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-on-surface-variant flex items-center gap-1"
                >
                    <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
                    {{ __('Đã lưu hồ sơ thành công.') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            if (!Alpine.store('profileDataRegistered')) {
                Alpine.store('profileDataRegistered', true);

                Alpine.data('profileForm', () => ({
                    avatarUrl: '{{ $user->avatar_url }}',
                    previewAvatar(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.avatarUrl = URL.createObjectURL(file);
                        }
                    }
                }));

                Alpine.data('locationSelector', (initialProvince, initialDistrict) => ({
                    provinceName: initialProvince,
                    districtName: initialDistrict,
                    provinces: [],
                    districts: [],
                    isLoading: true,
                    
                    init() {
                        fetch('https://provinces.open-api.vn/api/?depth=2')
                            .then(res => res.json())
                            .then(data => {
                                this.provinces = data;
                                this.isLoading = false;
                                
                                if (this.provinceName) {
                                    const selectedProv = this.provinces.find(p => p.name === this.provinceName);
                                    if (selectedProv) {
                                        this.districts = selectedProv.districts;
                                    }
                                }
                            })
                            .catch(err => {
                                console.error('Failed to load provinces:', err);
                                this.isLoading = false;
                            });
                    },
                    
                    onProvinceChange(e) {
                        const selectedName = e.target.value;
                        this.provinceName = selectedName;
                        this.districtName = '';
                        this.districts = [];
                        
                        if (selectedName) {
                            const selectedProv = this.provinces.find(p => p.name === selectedName);
                            if (selectedProv) {
                                this.districts = selectedProv.districts;
                            }
                        }
                    },
                    
                    onDistrictChange(e) {
                        this.districtName = e.target.value;
                    }
                }));
            }
        });
    </script>
</section>
