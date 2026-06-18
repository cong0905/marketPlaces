@props([
    'variant' => 'filled', // filled, outlined, text
    'type' => 'button',
    'href' => null,
    'icon' => null
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 px-6 py-2 rounded-full text-label-md font-label-md transition-all disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variantClasses = match($variant) {
        'filled' => 'bg-primary text-on-primary hover:opacity-90 shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none',
        'outlined' => 'border border-outline text-primary hover:bg-surface-container-low active:bg-surface-container',
        'text' => 'text-primary hover:bg-surface-container-low active:bg-surface-container',
        'danger' => 'bg-error text-on-error hover:opacity-90 shadow-sm active:translate-y-[2px]',
        default => 'bg-primary text-on-primary hover:opacity-90 shadow-[0_2px_0_0_#6b4900] active:translate-y-[2px] active:shadow-none',
    };

    $classes = $baseClasses . ' ' . $variantClasses;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
