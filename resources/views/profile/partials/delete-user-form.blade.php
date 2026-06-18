<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-on-surface">
            {{ __('Xóa tài khoản') }}
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant">
            {{ __('Một khi tài khoản của bạn bị xóa, toàn bộ tài nguyên và dữ liệu sẽ bị xóa vĩnh viễn. Trước khi xóa tài khoản, vui lòng tải xuống bất kỳ dữ liệu hoặc thông tin nào bạn muốn giữ lại.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Xóa tài khoản') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-surface-container border border-outline-variant rounded-xl shadow-lg">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-on-surface">
                {{ __('Bạn có chắc chắn muốn xóa tài khoản của mình?') }}
            </h2>

            <p class="mt-1 text-sm text-on-surface-variant">
                {{ __('Một khi tài khoản của bạn bị xóa, tất cả tài nguyên và dữ liệu của nó sẽ bị xóa vĩnh viễn. Vui lòng nhập mật khẩu của bạn để xác nhận muốn xóa tài khoản vĩnh viễn.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Mật khẩu') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Mật khẩu') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Hủy') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Xóa tài khoản') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
