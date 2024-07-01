<div class="faq">
    <div class="faq-question">
        <div class="material-symbols-rounded">help</div>
        <p style="margin-block: 1.2rem; font-weight: 700">
            {{ $question }}
        </p>
    </div>
    <div class="faq-answer">
        <div class="material-symbols-rounded">info</div>
        <div style="margin-block: 1.2rem; display: flex; flex-flow: column; gap: 0.4rem">
            {{ $slot }}
        </div>
    </div>
</div>
