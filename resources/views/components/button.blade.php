<button {{ $attributes->merge(['type' => 'submit', 'class' => '']) }}>
    {{ $value ?? $slot }}
</button>
