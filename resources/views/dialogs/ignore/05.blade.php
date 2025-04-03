<x-page-head title="Lazy Sanad"
             blurb="Sanad's parents get involved when his grades start to slip, but his father trusts that he can catch up.">
    <x-link :href="route('dialogs.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('dialogs.show', '05')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/qLA_9NjlBcQ" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    <x-term-item arb="تحمّل" eng="to bear, to endure, to deal with"/>
    <x-term-item arb="تخيّل" eng="to imagine"/>
    <x-term-item arb="دعم" eng="to support"/>
    <x-term-item arb="شجّع" eng="to encourage"/>
    <x-term-item arb="طرد" eng="to expel, to kick out"/>
    <x-term-item arb="طار" eng="to fly"/>
    <x-term-item arb="فاجأ" eng="to surprise"/>
    <x-term-item arb="قدّر" eng="to appreciate"/>
    <x-term-item arb="نجح" eng="to succeed"/>
    <x-term-item arb="نقز" eng="to be startled, to flinch"/>
    <x-term-item arb="نقّز" eng="to startle"/>
    <x-term-item arb="ودّى" eng="to lead, to send, to take (someone somewhere)"/>
    <x-term-item arb="جائزة" eng="award, prize"/>
    <x-term-item arb="رياضيّات" eng="math"/>
    <x-term-item arb="مسرح" eng="theater"/>
    <x-term-item arb="مستوى" eng="level"/>
    <x-term-item arb="شرف" eng="honor"/>
    <x-term-item arb="شهادة" eng="certificate, diploma"/>
    <x-term-item arb="قدرة" eng="ability, capability"/>
    <x-term-item arb="قرار" eng="decision"/>
    <x-term-item arb="مادّة" eng="subject matter, field of study"/>
    <x-term-item arb="مصدوم" eng="shocked"/>
    <x-term-item arb="علنيّ" eng="public"/>
    <x-term-item arb="خلال" eng="through, throughout"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="بابا شو بتعمل؟ نقّزتني"
                       eng="Dad, what are you doing? you startled me"/>
        <x-dialog-line speaker="أبو سند" arb="حابب أشوف شو اللي مفرّحك"
                       eng="I'd like to see what's making you so happy"/>
        <x-dialog-line speaker="سند" arb="ولا إشي — الرياضيّات — كثير حابّه هاذا الدرس"
                       eng="nothing — math — I love it, this lesson"/>
        <x-dialog-line speaker="أبو سند" arb="إنتا بتدرس رياضيّات وإنتا نايم؟ كيف يعني؟"
                       eng="you study math while lying down? how?"/>
        <x-dialog-line speaker="سند" arb="ما عمرك جرّبت؟" eng="you've never tried?"/>
        <x-dialog-line arb="بتخيّل الأرقام قدّامي — هيك بتطير"
                       eng="I imagine the numbers in front of me — they fly like that"/>
        <x-dialog-line speaker="أبو سند" arb="طبّ قوم، أقعد على المكتب وأمسك ورقة وقلم"
                       eng="alright, get up, sit at the desk & grab a paper & pen"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 2">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="معليش ماما حبيبي" eng="it's OK, my dear"/>
        <x-dialog-line arb="بسّ المرّة الجاية أوّل مبدّك تروح على الحمّام بتقلّي بسرعة، أوك؟"
                       eng="but next time, as soon as you want to go to the bathroom you tell me quickly, OK?"/>
        <x-dialog-line arb="بعدين ما بنلحّق" eng="otherwise we don't make it on time"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 3">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="هاي ما دخّلتها" eng="I didn't put this one in"/>
        <x-dialog-line arb="ألو؟ أهلًا مسّ كوثر — خير،  فيه إشي؟"
                       eng="hello? hi Miss Kawthar — everything OK? is there something (going on)?"/>
        <x-dialog-line arb="طبعًا، بكرا عالحدعش بالزبط بنكون عندكم"
                       eng="of course, tomorrow at 11 exactly we'll be there (lit. at your place)"/>
        <x-dialog-line arb="شو القصّة؟ سند؟" eng="what's the story? Sanad?"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 4">
    <div class="dialog-body">
        <x-dialog-line speaker="سامر" arb="ماما"
                       eng="Mom"/>
        <x-dialog-line speaker="سالي" arb="فيه نونو — ودّيه بسرعة"
                       eng="there's a 'nunu' — take him, quickly"/>
        <x-dialog-line speaker="سند" arb="لأ ودّيه إنتي — ماما"
                       eng="no, you take him — Mom"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 5">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="إحكيلي، لشو مسّ كوثر بدّها ايّانا بكرا، آه؟"
                       eng="tell me what Miss Kawthar needs us for tomorrow, huh?"/>
        <x-dialog-line speaker="سند" arb="مسّ كوثر؟ مش عارف"
                       eng="Miss Kawthar? I don't know"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 6">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="يا الله، شو رح يصير بكرا؟"
                       eng="my God, what's going to happen tomorrow?"/>
        <x-dialog-line arb="شو رح يعملو بسّ يعرفو؟"
                       eng="what are they going to do once they find out?"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 7">
    <div class="dialog-body">
        <x-dialog-line speaker="مسّ كوثر" arb="أهلا وسهلا بأولياء أمور سند"
                       eng="welcome to the protectors of Sanad's affairs"/>
        <x-dialog-line speaker="أبو سند" arb="اهلًا فيكي مسّ — إنشالله خير"
                       eng="hello, Miss — hopefully everything is OK"/>
        <x-dialog-line speaker="مسّ كوثر"
                       arb="خير — بسّ بصراحة حبّيت أحطّكم بالصورة قبل مناخد أيّا قرار"
                       eng="it's OK — but honestly I wanted to put you in the picture before we take any decision"/>
        <x-dialog-line speaker="إمّ سند" arb="سوري بسّ قرار بشو؟"
                       eng="sorry but a decision about what?"/>
        <x-dialog-line speaker="مسّ كوثر" arb="بصراحة، سند من بداية السنة فيه تراجع كبير"
                       eng="honestly, as for Sanad, since the beginning of the year there's been a big falling behind"/>
        <x-dialog-line arb="علاماته واطية وتركيزه صفر بالحصّة"
                       eng="his grades are low & his concentration is zero in the class"/>
        <x-dialog-line arb="قلنا بنستنّى للشهر الثاني بسّ للأسف ما نجح ولا بمادّة"
                       eng="we said we'd wait until the second month, but sadly he didn't succeed in even a single subject"/>
        <x-dialog-line speaker="أهل سند" arb="شو؟"
                       eng="what?"/>
        <x-dialog-line speaker="إمّ سند" arb="أنا مصدومة من كلامك بصراحة مسّ"
                       eng="I'm shocked by your words, to be honest, Miss"/>
        <x-dialog-line arb="سند، شو عمّ بصير معك؟"
                       eng="Sanad, what's going on with you?"/>
        <x-dialog-line speaker="أبو سند" arb="بصراحة يا مسّ، أنا بقدّر كلامك"
                       eng="honestly Miss, I appreciate your words"/>
        <x-dialog-line arb="كمان أنا بعرف إنّه هاذا مش مستوى سند — هو قادر يشدّ حيله"
                       eng="I also know that this isn't Sanad's level — he can put in more effort"/>
        <x-dialog-line arb="معليش، خلّينا نستنّى للشهر الثاني ويشوف"
                       eng="it's OK, let's wait until the second month & he'll see"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 8">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="من وين جايب إنتا هالروقان"
                       eng="where'd you get this serenity from?"/>
        <x-dialog-line arb="يعني قاعد بغرفته كلّ الوقت بلعب وبسرح وبتخوّت علينا؟"
                       eng="so he's sitting in his room playing & daydreaming & making fun of us the whole time?"/>
        <x-dialog-line arb="شو بدّك نستنّى عبين مينطرد من المدرسة يعني؟"
                       eng="what, do you want us to wait while he gets kicked out of school?"/>
        <x-dialog-line speaker="أبو سند" arb="أنا عارف إنّه غلط"
                       eng="I know it's wrong"/>
        <x-dialog-line arb="بسّ عارف كمان إنّه إذا بدّه، بقدر، وإحنا كمان لازم نساعده"
                       eng="but I also know that if he wants, he can, & we also need to help him"/>
        <x-dialog-line arb="وبالآخر، هو بتحمّل النتيجة"
                       eng="& in the end he will deal with the results"/>
        <x-dialog-line speaker="سالي" arb="ماما سامر عامل على حاله"
                       eng="Mom, Samer has made on himself"/>
        <x-dialog-line speaker="إمّ سند" arb="هيّني جاية" eng="I'm coming"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 9">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="يعني شو حكتلك بالزبط؟ بدّي أعرف"
                       eng="I mean, what did she tell you exactly? I want to know"/>
        <x-dialog-line arb="وليش هالمرّة حكو معك إنتا؟ مدايمًا بحكو معي أنا"
                       eng="& why did they talk to you this time? I mean, they always talk to me"/>
        <x-dialog-line speaker="أبو سند" arb="ما بعرف — شو بعرّفني أنا؟"
                       eng="I don't know — how am I supposed to know?"/>
        <x-dialog-line arb="حكو فيه اجتماع مع مسّ كوثر بالمسرح وبسّ"
                       eng="they said there's a meeting with Miss Kawthar in the theater & that's all"/>
        <x-dialog-line speaker="إمّ سند" arb="بالمسرح؟ يا حبيبي، شكله علامات الشهر طلعت"
                       eng="in the theater?u oof, it seems the month's grades came out"/>
        <x-dialog-line arb="يعني البهدلة هالمرّة رح تكون علنيّة — ع مستوى المدرسة"
                       eng="so the scolding this time is going to be public — at the school level"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 10">
    <div class="dialog-body">
        <x-dialog-line speaker="مسّ كوثر" arb="وهلّأ بعد مخلّصنا شهادات الشرف للطلبة المتفوّقين"
                       eng="& now, after we finished the honor diplomas for the outstanding students"/>
        <x-dialog-line speaker="إمّ سند" arb="إجى دورنا — حضّر حالك للبهدلة"
                       eng="our turn has come — get ready for the scolding"/>
        <x-dialog-line speaker="مسّ كوثر" arb="رح نعطي شهادة الطالب الأكثر تقدّمًا خلال الشهر"
                       eng="we're going to give the diploma of the most-improved student over the course of the month"/>
        <x-dialog-line arb="وهاي الجائزة للطالب سند" eng="& this prize is for the student Sanad"/>
        <x-dialog-line speaker="إمّ سند" arb="شو؟" eng="what?"/>
        <x-dialog-line speaker="أهل سند" arb="أحلى سند" eng="go Sanad"/>
        <x-dialog-line speaker="أبو سند" arb="برافو سند" eng="bravo Sanad"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 11">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="كيف بسّ؟"
                       eng="but how?"/>
        <x-dialog-line speaker="أبو سند" arb="كنت عارف إنّك إذا بدّك رح تقدر"
                       eng="I knew that if you want, you can"/>
        <x-dialog-line speaker="إمّ سند" arb="برافو حبيبي سند، أنا كثير فخورة فيك"
                       eng="bravo my dear Sanad, I'm very proud of you"/>
        <x-dialog-line speaker="سند" arb="شو، فاجأتكم صحّ؟"
                       eng="so, I surprised you, right?"/>
        <x-dialog-line speaker="سامر" arb="نونو"
                       eng="'nunu'"/>
        <x-dialog-line speaker="سالي" arb="بسرعة"
                       eng="quickly"/>
        <x-dialog-line speaker="سند" arb="أحلى سمّور"
                       eng="go Sammour"/>
        <x-dialog-line speaker="سالي" arb="وأخيرًا عاملها"
                       eng="he's finally done it"/>
        <x-dialog-line speaker="أبو سند" arb="بطل"
                       eng="(he's a) hero"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 12">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند"
                       arb="أولادنا محتاجين منّنا إنّه نطوّل بالنا عليهم وندعمهم ونثق بقدراتهم"
                       eng="our children need from us that we be patient with them & support them & trust in their capabilities"/>
        <x-dialog-line speaker="أبو سند" arb="ونشجّعهم دائمًا إنّهم يحطّو طاقة أكبر بالإشي اللي بعملوه"
                       eng="& encourage them always to put greater energy into the thing they're doing"/>
        <x-dialog-line speaker="إمّ سند" arb="ووقتها رح نشوفهم بعملو أشياء ما منتوقّعها"
                       eng="& then (lit. its time) we will see them do things we didn't expect"/>
    </div>
</x-activity-area>
