@props([
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Chọn một tùy chọn',
    'required' => false
])

<div x-data="{
        open: false,
        value: '{{ $selected }}',
        label: '{{ $placeholder }}',
        options: {{ json_encode($options) }},
        init() {
            const selectedOpt = this.findOption(this.value);
            if (selectedOpt) {
                this.label = selectedOpt.label;
            }
        },
        findOption(val) {
            for(let group of this.options) {
                if(group.options) {
                    let opt = group.options.find(o => o.value == val);
                    if(opt) return opt;
                } else {
                    if(group.value == val) return group;
                }
            }
            return null;
        },
        select(val, lbl) {
            this.value = val;
            this.label = lbl;
            this.open = false;
        }
    }" 
    class="relative"
    @keydown.escape="open = false"
>
    <button type="button" @click="open = !open" 
        class="w-full text-left rounded-lg border border-outline-variant bg-surface focus:ring-2 focus:ring-primary focus:border-primary shadow-sm text-body-md text-on-surface px-4 py-2 flex justify-between items-center transition-colors hover:bg-surface-container-lowest"
        :class="open ? 'ring-2 ring-primary border-primary' : ''"
    >
        <span x-text="label" :class="value === '' ? 'text-on-surface-variant' : 'text-on-surface'"></span>
        <span class="material-symbols-outlined text-on-surface-variant transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
    </button>

    <div x-show="open" @click.away="open = false" x-transition.opacity.duration.200ms style="display: none;"
        class="absolute z-50 mt-1 w-full bg-surface-container-lowest rounded-lg shadow-lg border border-outline-variant max-h-60 overflow-y-auto py-2">
        <template x-for="group in options" :key="group.label || group.value">
            <div>
                <template x-if="group.options">
                    <div>
                        <div class="px-4 py-2 text-[12px] font-bold text-on-surface-variant uppercase tracking-wider bg-surface-container-low" x-text="group.label"></div>
                        <template x-for="opt in group.options" :key="opt.value">
                            <div @click="select(opt.value, opt.label)" 
                                class="px-4 py-2 cursor-pointer hover:bg-surface-container-high transition-colors text-body-md"
                                :class="value == opt.value ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface'">
                                <span x-text="opt.label"></span>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!group.options">
                    <div @click="select(group.value, group.label)" 
                        class="px-4 py-2 cursor-pointer hover:bg-surface-container-high transition-colors text-body-md"
                        :class="value == group.value ? 'bg-primary-container text-on-primary-container font-bold' : 'text-on-surface'">
                        <span x-text="group.label"></span>
                    </div>
                </template>
            </div>
        </template>
    </div>
    
    <input type="hidden" name="{{ $name }}" x-model="value" {{ $required ? 'required' : '' }}>
</div>
