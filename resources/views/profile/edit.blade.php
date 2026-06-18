<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-headline-sm text-on-surface leading-tight">
            {{ __('Hồ sơ cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-margin-mobile md:px-margin-desktop space-y-6">
            <div class="p-6 sm:p-8 bg-surface-container border border-outline-variant rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-surface-container border border-outline-variant rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-surface-container border border-outline-variant rounded-xl shadow-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
