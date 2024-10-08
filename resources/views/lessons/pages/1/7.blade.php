<x-deck-container :deck="\App\Models\Deck::find(44)"/>

<x-lesson-concept section-type="skill" section-title="grammar"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-1') !!}">

    <p>Since <b>Active Participles</b> refer to the state that results from an action, the <b>Active Participles</b> of
        many <b>stative verbs</b> related to cognition & sensation (e.g. <b>"to know"</b>) are essentially
        interchangeable with their actual <b>Present Tense</b> forms (i.e. <b>"he knows"</b> vs. <b>"he's aware"</b>).
        Using <b>Present Participles</b> in
        this way is invalid in English precisely because — by definition — a state cannot be in progress.
    </p>

    <div class="array">
        <x-sentence-item eng="he knows">
            <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
            <x-sentence-term arb="عارف" eng="aware" :term="$terms['ʕārif'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="he knew">
            <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
            <x-sentence-term arb="عرف" eng="3M.knew" :term="$terms['ʕirif'] ?? null"/>
        </x-sentence-item>
    </div>
    <x-sentence-item eng="he knows who I am">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="عارف" eng="knowing" :term="$terms['ʕārif'] ?? null"/>
        <x-sentence-term arb="مين" eng="who" :term="$terms['mīn'] ?? null"/>
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
    </x-sentence-item>

    <div class="array">
        <x-sentence-item eng="he sees">
            <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
            <x-sentence-term arb="شايف" eng="seeing" :term="$terms['šāyif'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="he saw">
            <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
            <x-sentence-term arb="شاف" eng="3M.saw" :term="$terms['šāf'] ?? null"/>
        </x-sentence-item>
    </div>
    <x-sentence-item eng="he sees where I am">
        <x-sentence-term arb="هو" eng="he" :term="$terms['huwwe'] ?? null"/>
        <x-sentence-term arb="شايف" eng="seeing" :term="$terms['šāyif'] ?? null"/>
        <x-sentence-term arb="وين" eng="where" :term="$terms['wēn'] ?? null"/>
        <x-sentence-term arb="أنا" eng="I" :term="$terms['ʔana'] ?? null"/>
    </x-sentence-item>

    <p>However, what is considered a <b>stative verb</b> in Arabic may or may not be so in English. For now,
        learn which <b>Active Participles</b> are used this way on a case-by-case basis.</p>
</x-lesson-concept>

<x-lesson-concept section-type="skill" section-title="vocabulary"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-2') !!}">

    <p>Let's use the preposition <b>عند (ʕind "at, by")</b> to describe the location of something. While <b>بـ (b-
            "in")</b>
        & <b>على (ʕala "on")</b> situate one thing in direct physical relation to another, <b>عند (ʕind)</b> is somewhat
        more abstract, situating something in the same vicinity or location as something else.</p>
    <x-sentence-item eng="Sama is at the door">
        <x-sentence-term arb="سما" eng="Sama"/>
        <x-sentence-term arb="عند" eng="at" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="الباب" eng="the-door" :term="$terms['bāb'] ?? null"/>
    </x-sentence-item>
    <p>In some situations, <b>"at"</b> in English can imply <b>"inside"</b> — but this is never
        the case in Arabic. Only <b>بـ (b-)</b> can mean <b>"inside"</b>, while <b>عند (ʕind)</b> is more akin to <b>"by"</b>
        (i.e. <b>"next to"</b>).</p>
    <x-sentence-item eng="Sama is at the store">
        <x-sentence-term arb="سما" eng="Sama"/>
        <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
        <x-sentence-term arb="ـالمحلّ" eng="the-store" :term="$terms['maħall'] ?? null"/>
    </x-sentence-item>
    <x-sentence-item eng="Sama is by the store">
        <x-sentence-term arb="سما" eng="Sama"/>
        <x-sentence-term arb="عند" eng="by" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="المحلّ" eng="the-store" :term="$terms['maħall'] ?? null"/>
    </x-sentence-item>
    <p>In other words, avoid using <b>عند (ʕind)</b> for something that is physically within a certain place.</p>

    <p>In the most abstract sense, <b>عند (ʕind)</b> simply refers to <b>"where ... is"</b>. It may even be used in
        reference to a person, meaning <b>"at ...'s place"</b>.</p>
    <x-sentence-item eng="Ghassan is at Akram's place">
        <x-sentence-term arb="غسّان" eng="Ghassan"/>
        <x-sentence-term arb="عند" eng="at" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="أكرم" eng="Akram"/>
    </x-sentence-item>
    <x-sentence-item eng="Ghassan is at the doctor's">
        <x-sentence-term arb="غسّان" eng="Ghassan"/>
        <x-sentence-term arb="عند" eng="at" :term="$terms['ʕind'] ?? null"/>
        <x-sentence-term arb="الدكتور" eng="the-doctor" :term="$terms['duktōr'] ?? null"/>
    </x-sentence-item>
</x-lesson-concept>

<x-activity-area title="{{ __('exercise') }}">
    <p>Everyone is on their way to hang out at Ghassan's today. Using the following information, can you indicate
        everyone's
        location in the image?</p>
    <div class="array">
        <div class="activity-info-container">
            <div>أكرم</div>
            <div>عند غسّان</div>
            <div>غسّان</div>
            <div>بالدار</div>
            <div>شادي</div>
            <div>عند الباب</div>
            <div>جريس</div>
            <div>عالكوربا</div>
            <div>محمّد</div>
            <div>بالشارع</div>
            <div>يوسف</div>
            <div>متأخّر</div>
        </div>
    </div>

    <img src="{{ asset('img/images/بيت غسّان.png') }}"
         style="border-radius: 1.6rem; max-width: 50%; margin: 0 auto">
</x-activity-area>

<x-lesson-concept section-type="idea" section-title="speak like a native"
                  title="{!! __('lessons.' . $unit . '0' . $lesson . '-3') !!}">

    <p>We can apologize in Arabic using <b>آسف (ʔāsif "sorry")</b>. Since this is an adjective, it must agree in gender
        & number with the subject. We can accept the apology using some familiar expressions, like <b>عادي (ʕādi)</b> &
        <b>معليش (maʕlēš)</b>. It's that simple!</p>
    <div class="array">
        <x-sentence-item eng="it's OK, it's no problem">
            <x-sentence-term arb="عادي" eng="it's OK" :term="$terms['ʕādi'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="مشكلة" eng="problem" :term="$terms['muškile'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="we're sorry">
            <x-sentence-term arb="إحنا" eng="we" :term="$terms['ʔiħna'] ?? null"/>
            <x-sentence-term arb="آسفين" eng="(p.) sorry" :term="$terms['ʔāsif'] ?? null"/>
        </x-sentence-item>
    </div>
</x-lesson-concept>

<x-activity-area title="{{ __('dialogue') }}">
    <p>Shadow the following phonecall between Akram & Sama. They've agreed to meet for lunch, but Sama got lost & is
        late.</p>
    <div class="activity-dialog">
        <x-dialog-line speaker="أكرم" arb="ألو؟"/>
        <x-dialog-line speaker="سما" arb="..."/>
        <x-dialog-line speaker="أكرم" arb="ألو، سما؟ مش سامع"/>
        <x-dialog-line speaker="سما" arb="هلّأ سامع؟"/>
        <x-dialog-line speaker="أكرم" arb="آه، هلّأ منيح — وين إنتي؟"/>
        <x-dialog-line speaker="سما" arb="أنا بالشارع، بسّ اللقط مش منيح"/>
        <x-dialog-line speaker="أكرم" arb="معليش، بسّ إنتي كثير متأخّرة"/>
        <x-dialog-line speaker="سما" arb="والله أنا آسفة — أنا ضايعة شويّ"/>
        <x-dialog-line speaker="أكرم" arb="عادي، مش مشكلة — بس إنتي فاهمة هلّأ وين المحلّ؟"/>
        <x-dialog-line speaker="سما" arb="آه، أنا هلّأ عالكوربا — إنتا قاعد بالمطعم؟"/>
        <x-dialog-line speaker="أكرم" arb="لأ، أنا واقف برّا"/>
        <x-dialog-line speaker="سما" arb="بسّ يعني عند المطعم؟"/>
        <x-dialog-line speaker="أكرم" arb="آه، آه"/>
        <x-dialog-line speaker="سما" arb="عنجدّ؟ مش شايفة وين إنتا"/>
        <x-dialog-line speaker="أكرم" arb=" إنتي عنجدّ ضايعة — أنا هون، عند الباب"/>
        <x-dialog-line speaker="سما" arb="يمّا، أهلا"/>
        <x-dialog-line speaker="أكرم" arb="أهلين، أهلين"/>
        <x-dialog-line speaker="سما" arb="والله أنا كثير متأسّفة، أنا مش عارفة هالمنطقة"/>
        <x-dialog-line speaker="أكرم" arb="عادي، مش مشكلة — يلّا"/>
    </div>
    <x-activity-mc que="مين بالمحلّ؟ أكرم ولّا سما؟" ans="a"
                   a="أكرم"
                   b="سما"
    />
    <x-activity-mc que="سما عارفة وين المحلّ؟" ans="b"
                   a="آه"
                   b="لأ"
    />
    <x-activity-mc que="همّه سامعين منيح؟" ans="b"
                   a="آه"
                   b="لأ"
    />
</x-activity-area>
