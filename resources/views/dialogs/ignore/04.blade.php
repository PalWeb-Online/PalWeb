<x-page-head title="Not Like Us"
             blurb="Some of the parents in the neighborhood aren't too pleased that their children are playing with the new kids from a different part of town.">
    <x-link :href="route('dialogs.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('dialogs.show', '04')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/QOjSOycpd6c" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    {{--            <x-term-item arb="استجوب" eng="to question, to interrogate"/>--}}
    <x-term-item arb="حدّد" eng="to delimit, to define"/>
    <x-term-item arb="حسب" eng="to count (as valid)"/>
    <x-term-item arb="حسب حساب ~" eng="to account for ~, to keep ~ in mind"/>
    <x-term-item arb="حكم (على)" eng="to judge, to pass judgement (on someone)"/>
    <x-term-item arb="سلّى" eng="to entertain"/>
    <x-term-item arb="بشهّي" eng="is delicious"/>
    <x-term-item arb="ضغط" eng="to press"/>
    <x-term-item arb="ضغط" eng="pressure"/>
    <x-term-item arb="مضغوط" eng="under pressure, weighed down, overworked"/>
    <x-term-item arb="غلب" eng="to beat, to defeat"/>
    <x-term-item arb="غلّب" eng="to bother, to trouble"/>
    <x-term-item arb="غلبة" eng="bother, trouble"/>
    <x-term-item arb="ميّز" eng="to distinguish, to discriminate"/>
    <x-term-item arb="نادى" eng="to call over"/>
    <x-term-item arb="اتّفق" eng="to come to an agreement, to agree on (something)"/>
    <x-term-item arb="حكي" eng="talk">
        <x-term-item subterm arb="حكي فاضي" eng="empty talk; hot air"/>
    </x-term-item>
    <x-term-item arb="خبرة" eng="experience"/>
    <x-term-item arb="فريق" eng="team"/>
    <x-term-item arb="ملعب" eng="playing field, playground, stadium, court"/>
    {{--            why is this reversed? --}}
    {{--            <x-sentence-item eng="we had a lot of pressure at work today">--}}
    {{--                <x-sentence-term arb="كثير" eng="very much"/>--}}
    {{--                <x-sentence-term arb="كان عندنا" eng="we had"/>--}}
    {{--                <x-sentence-term arb="ضغط" eng="pressure"/>--}}
    {{--                <x-sentence-term arb="بالشغل" eng="at work"/>--}}
    {{--                <x-sentence-term arb="اليوم" eng="today"/>--}}
    {{--            </x-sentence-item>--}}
    {{--            <x-term-item arb="متعب" eng="exhausting"/>--}}
    <x-term-item arb="فهمان" eng="knowledgeable"/>
    {{--            <x-term-item arb="مختلف" eng="different"/>--}}
    <x-term-item arb="رائع" eng="amazing"/>
    <x-term-item arb="فوقانيّ" eng="above"/>
    <x-term-item arb="تحتانيّ" eng="below"/>
    <x-term-item arb="حاضر" eng="yessir"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="جول"
                       eng="goal"/>
        <x-dialog-line speaker="ولد" arb="شو؟ هاذا جول؟ هاذا مش جول"
                       eng="what? that's a goal? that's not a goal"/>
        <x-dialog-line speaker="سند" arb="كيف يعني مش جول؟"
                       eng="how is it not a goal?"/>
        <x-dialog-line speaker="يزن" arb="إحنا متّفقين إنّه من هون لهون الجول — هيّ الحجار"
                       eng="we've agreed that the goal is from here to here — here are the stones"/>
        <x-dialog-line speaker="ولد" arb="بسّ هاي إجت جنب الحجر — يعني بالعارضة"
                       eng="but this came next to the stones — meaning on the beam"/>
        <x-dialog-line arb="هاذ الجول مش محسوب"
                       eng="this goal isn't counted"/>
        <x-dialog-line speaker="سند" arb="شو؟"
                       eng="what?"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 2">
    <div class="dialog-body">
        <x-dialog-line speaker="أبو سند" arb="مرحبا دينا، يعطيكي العافية"
                       eng="hello Dina, God bless your work"/>
        <x-dialog-line arb="شو، شكله كان يومك متعب"
                       eng="huh, it seems your day was exhausting"/>
        <x-dialog-line speaker="إمّ سند" arb="كثير كان عندنا ضغط بالشغل اليوم — شو، وين الولاد"
                       eng="we had a lot of pressure at work today — huh, where are the kids?"/>
        <x-dialog-line speaker="أبو سند" arb="راحو يلعبو بالحارة"
                       eng="they went to play in the block"/>
        <x-dialog-line speaker="إمّ سند" arb="طمّنني، شو صار مع المديرة الجديدة؟"
                       eng="tell me, what happened with the new manager?"/>
        <x-dialog-line speaker="أبو سند" arb="مش عارف"
                       eng="I don't know"/>
        <x-dialog-line speaker="إمّ سند" arb="ليش؟ مالها إشي؟"
                       eng="why? is something going on with her?"/>
        <x-dialog-line arb="مش قلت عندها خبرة ومعها دكتوراة وفهمانة كثير؟"
                       eng="haven't you said she has experience & a doctorate & is very knowledgeable?"/>
        <x-dialog-line speaker="أبو سند" arb="مزبوط، بسّ ..."
                       eng="right, but ..."/>
        <x-dialog-line speaker="إمّ سند" arb="بسّ شو؟"
                       eng="but why?"/>
        <x-dialog-line speaker="أبو سند" arb="أنا عارف؟ الشباب فالمكتب منزعجين كثير"
                       eng="I don't know — the guys at the office are really annoyed"/>
        <x-dialog-line arb="ومش عاجبهم الشغل معها"
                       eng="& they don't like work with her"/>
        <x-dialog-line speaker="إمّ سند" arb="وإنتا شو رأيك؟"
                       eng="& what do you think?"/>
        <x-dialog-line speaker="أبو سند" arb="همّه مش عاجبهم إنّه مديرتهم ستّ"
                       eng="they don't like that their manager is a lady"/>
        <x-dialog-line arb="بسّ بدّك الصراحة؟"
                       eng="but honestly?"/>
        <x-dialog-line arb="من يوم مإجت وضع الشركة كثير تحسّن"
                       eng="since the day she arrived the company's situation has improved a lot"/>
        <x-dialog-line arb="والأمور ترتّبت — بدّك الصراحة؟"
                       eng="& things have fallen into place — honestly?"/>
        <x-dialog-line arb="أنا شايف إنّها أحسن بكثير من المدير القديم"
                       eng="I've seen that she's much better than the previous manager"/>
        <x-dialog-line speaker="إمّ سند" arb="أخّ بسّ — أيمتى الناس رح تبطّل تحكم بهاي الطريقة؟"
                       eng="ugh enough — when are people going to stop judging this way?"/>
        <x-dialog-line speaker="سند" arb="أنا هاذي آخر مرّة بلعب معهم"
                       eng="this is the last time I play with them"/>
        <x-dialog-line speaker="أبو سند" arb="هاء — شو صاير يا ولاد؟"
                       eng="huh, what's going on kids?"/>
        <x-dialog-line speaker="سند" arb="بعد مدخّلنا جول — أمّا شو جول بابا"
                       eng="after we put in a goal — wow, what a goal Dad"/>
        <x-dialog-line speaker="سند" arb="قال ما حسبوه وخسرنا"
                       eng="he said they didn't count it & we lost"/>
        <x-dialog-line speaker="أبو سند" arb="مين همّه؟"
                       eng="who are they?"/>
        <x-dialog-line speaker="سند" arb="ولاد الحارة اللي فوق"
                       eng="the kids of the block up (the street)"/>
        <x-dialog-line speaker="أبو سند" arb="وليش إنتو أصلًا رحتو تلعبو معهم؟"
                       eng="& why did you go play with them in the first place?"/>
        <x-dialog-line speaker="سالي" arb="بابا، عمّه أبو يزن مش عاجبه إنّهم لعبو معهم"
                       eng="Dad, uncle Abu Yazan doesn't like that they played with them"/>
        <x-dialog-line arb="بحكي إنّه سكّان الحارة الفوق مش زيّنا"
                       eng="he says the residents of the block up (the street) aren't like us"/>
        <x-dialog-line speaker="سند" arb="بسّ همّه شاطرين — كثير لعبهم حلو"
                       eng="but they're really skilled — their playing is really good"/>
        <x-dialog-line speaker="إمّ سند" arb="وبعدين مع هالحكي — شو يعني زيّنا ومش زيّنا؟"
                       eng="not again with this talk — what does that mean, 'like us' & 'not like us'?"/>
        <x-dialog-line arb="من أيمتى منميّز بين الناس بحسب وين ساكنين؟"
                       eng="since when do we distinguish between people based on where they live?"/>
        <x-dialog-line arb="يلّا، أدخلو غسّلو إيديكم — أنا والبابا رح نحضّرلكم الأكل"
                       eng="alright, go in & wash your hands — Dad & I are going to prepare the food for you"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 3">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="أنا بقول الحلّ إنّه نعمل مبادرة"
                       eng="I say the solution is that we do an initiative"/>
        <x-dialog-line arb="ونزبّط الملعب ونشتري مرمى جديد"
                       eng="we fix the field & buy a new goalpost"/>
        <x-dialog-line speaker="ولد" arb="أيوى، مشكلتنا إنّه مش قادرين نحدّد الجول صحّ"
                       eng="yeah, our problem is that we can't delimit the goal correctly"/>
        <x-dialog-line arb="عشان هيك منضلّ نتخانق"
                       eng="that's why we stay at each others' necks"/>
        <x-dialog-line speaker="يزن" arb="بسّ أهالينا إذا شافونا عمّ نعمل إشي مع بعض رح ينزعجو"
                       eng="but if our parents saw we're doing something together they'll be upset"/>
        <x-dialog-line arb="بعدين، من وين أصلًا رح نجيب مصاري نشتري جول؟"
                       eng="besides, from where are we going to get money to buy a goal anyway?"/>
        <x-dialog-line speaker="سالي" arb="مش مشكلة، شو رأيكم نعمل بازار"
                       eng="no problem, what do you say we have a bazaar"/>
        <x-dialog-line arb="ونبيع فيّه كلّ الأشياء اللي ما منحتاجها"
                       eng="& there sell everything that we donˈt need?"/>
        <x-dialog-line speaker="رنا" arb="وممكن نعمل كيك وعصير"
                       eng="& we can make cake & juice"/>
        <x-dialog-line speaker="ولد" arb="أنا أختي بتعمل كيك كثير زاكي"
                       eng="my sister makes a really yummy cake"/>
        <x-dialog-line speaker="سند" arb="وإحنا إذا منتساعد، رح نزبّط الملعب ورح ننبسط كلّ الصيف"
                       eng="& if we help each other, we'll fix the field & we'll have fun all summer"/>
        <x-dialog-line arb="ورح نعمل دوري كلّ يوم ورح نضلّنا نغلبكم"
                       eng="& we'll have a tournament every day & we'll keep beating you"/>
        <x-dialog-line speaker="ولاد" arb="يلّا"
                       eng="alright"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 4">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ يزن"
                       arb="مأحلاهم الولاد، كيف بسلّو حالهم — بسّ مين هذول الولاد اللي معهم؟"
                       eng="how cute the kids are, how they entertain themselves — but who are those kids that are with them?"/>
        <x-dialog-line speaker="إمّ سند" arb="يسلمو إيديكي على هالقهوة الزاكية — مأحلى ريحتها"
                       eng="thank you for the delicious coffee — how nice its smell is"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="صحّتين وهنأ"
                       eng="enjoy"/>
        <x-dialog-line arb="بتعرفي يا إمّ يزن — شكله هذول الولاد من الحارة الفوقانيّة"
                       eng="you know, Imm Yazan — it seems those kids are from the block up (the street)"/>
        <x-dialog-line arb="ما بدّكم تعرفو شو سمعت؟ بقولو إنّه فيه عيلة من الحارة الفوقانيّة"
                       eng="don't you want to know what I heard? they say there's a family from the block up (the street)"/>
        <x-dialog-line arb="بدّهم يسكنو بالشقّة الفاضية اللي بالعمارة"
                       eng="they want to live in the empty apartment that's in the building"/>
        <x-dialog-line speaker="إمّ يزن" arb="يي"
                       eng="oh"/>
        <x-dialog-line speaker="إمّ سند" arb="شو مالك — نقزتي"
                       eng="what's wrong — you flinched"/>
        <x-dialog-line speaker="إمّ يزن" arb="شو؟ يصيرو جيراننا يعني؟ بسّ مهمّه بالمرّة مش زيّنا"
                       eng="what? (let them) be our neighbors? but they're not like us at all"/>
        <x-dialog-line speaker="إمّ سند" arb="وليش لازم يكونو زيّنا يا أمّ يزن"
                       eng="& why should they be like us, Umm Yazan?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="لأ، يعني يمكن يكونو مناح"
                       eng="no, I mean they might be good"/>
        <x-dialog-line arb="بسّ يعني عادي ولادهم يلعبو مع ولادكم؟"
                       eng="but you mean it's fine for their kids to play with your kids?"/>
        <x-dialog-line speaker="إمّ سند" arb="يي — شو فيها يعني؟"
                       eng="oh — what's wrong with that?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="إشربو القهوة بلاش تبرد"
                       eng="drink your coffee, lest it get cold"/>
        <x-dialog-line arb="رايحة أجيبلكم هريسة بتشهّي، بسّ بليز"
                       eng="(I'm) going to bring you some delicious harisa, but please"/>
        <x-dialog-line arb="إذا طلعو الولاد، نادوني بسرعة، خلّيني أستجوبهم"
                       eng="if the kids come up, call me over quickly, let me question them"/>
        <x-dialog-line speaker="إمّ سند" arb="حاضر يا برج المراقبة"
                       eng="yes, oh surveillance tower"/>
        <x-dialog-line speaker="إمّ يزن" arb="إمّ إنصاف"
                       eng="Imm Insaf"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="إجو؟"
                       eng="did they come?"/>
        <x-dialog-line speaker="إمّ يزن" arb="لأ، بسّ بدّي أقلّك إنّه ما بدّنا نغلّبك"
                       eng="no, I just want to tell you that we don't want to trouble you"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="يي، ولا غلبة ولا إشي — تفضّلو"
                       eng="oh, it's not a bother or anything — help yourselves"/>
        <x-dialog-line arb="يا عليّ، من اللخمة جبت الشوكة والصحن الغلط"
                       eng="oh no, from the shock I brought the wrong fork & plate"/>
        <x-dialog-line speaker="إمّ سند" arb="يي، شو مالهم؟ خلص، مناكل بأيّ إشي"
                       eng="oh, what's wrong with them? enough, we'll eat with anything"/>
        <x-dialog-line speaker="إمّ إنصاف"
                       arb="لأ، يعني بسّ همّه هذول اللي بخلّيهم للبنت اللي بتيجي بتنضّفلي البيت"
                       eng="no, but I mean these are the ones I leave for the girl that comes & cleans the house for me"/>
        <x-dialog-line arb="ما تأخذيني، مإنتو لخمتوني لمّا ناديتوني هيك"
                       eng="don't hold it against me, you flustered me when you called me over like that"/>
        <x-dialog-line speaker="إمّ سند" arb="يعني مش فاهمة — إنتي ما بتجليهم؟"
                       eng="I mean, I don't get it — you don't wash them?"/>
        <x-dialog-line arb="لأ طبعًا بجليهم — بسّ يعني"
                       eng="no of course I wash them — but I mean"/>
        <x-dialog-line speaker="إمّ سند" arb="وبعدين مع هالبسّ — ما بتوقّعها منّك يا إمّ إنصاف"
                       eng="not again with this 'but' — I don't expect it from you, Imm Insaf"/>
        <x-dialog-line arb="بتشهّي هالهريسة — وخاصّة بهالشوكة وهالصحن"
                       eng="this harisa is delicious — especially with this fork & this plate"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 5">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="أيوى يزن — روح"
                       eng="yeah Yazan, go"/>
        <x-dialog-line speaker="يزن" arb="جول"
                       eng="goal"/>
        <x-dialog-line speaker="أبو سند" arb="برافو يابا، لعب حلو ورائع — بدّعتو"
                       eng="brave son, nice & amazing playing — well done"/>
        <x-dialog-line arb="وشباب الفريق الثاني كمان بدّعو"
                       eng="& the guys of the other team also did well"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 6">
    <div class="dialog-body">
        <x-dialog-line speaker="سالي" arb="يعني إحنا حتّى لو مختلفين بأشياء"
                       eng="I mean, even if we're different in some things"/>
        <x-dialog-line arb="بسّ فيه كثير أشياء بتجمعنا وهاذ الأهمّ"
                       eng="there are a lot of things that bring us together, though, & that's the most important thing"/>
        <x-dialog-line arb="كلّنا لازم نحترم بعض"
                       eng="all of us should respect each other"/>
        <x-dialog-line speaker="سند" arb="ونتطلّع على الأشياء الحلوة المشتركة"
                       eng="& look at the nice things we share in common"/>
        <x-dialog-line arb="ونكون إيد وحدة حتّى نقدر نبني مستقبلنا مع بعض"
                       eng="& work together (lit. be one hand) so we can build our future together"/>
        <x-dialog-line speaker="سالي" arb="وما تنسو تعملو شير وسبسكرايب"
                       eng="& don't forget to share & subscribe"/>
    </div>
</x-activity-area>
