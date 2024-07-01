<div x-data="{ tab: 'm1' }" x-ref="buttons" class="module-buttons-container"
     style="background-image: url('{{ $img }}'); background-position-y: {{ $position ?? 'center' }}">
    <div class="module-buttons">
        <button :class="{ 'current': tab === 'm1', 'complete': tab === 'm2' || tab === 'm3' }"
                @click="tab = 'm1'; $dispatch('tab-changed', 'm1')">1
        </button>
        <button :class="{ 'current': tab === 'm2', 'complete': tab === 'm3' }"
                @click="tab = 'm2'; $dispatch('tab-changed', 'm2')">2
        </button>
        <button :class="{ 'current': tab === 'm3' }"
                @click="tab = 'm3'; $dispatch('tab-changed', 'm3')">3
        </button>
    </div>
</div>
