@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-outline-variant bg-surface focus:ring-primary focus:border-primary rounded-lg shadow-sm text-body-md text-on-surface']) }}>
