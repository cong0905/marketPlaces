<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-surface border border-outline rounded-lg font-label-md text-label-md text-on-surface hover:bg-surface-container-low focus:outline-none transition-colors']) }}>
    {{ $slot }}
</button>
