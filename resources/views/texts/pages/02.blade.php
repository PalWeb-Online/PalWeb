<x-page-head title="Who’s the Boy?"
             blurb="When Rana mentions someone named Jimmy & is delivered a bouquet of flowers, her mother jumps to conclusions.">
    <x-link :href="route('texts.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('texts.show', '02')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/O-B85gxPBus" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    <x-term-item arb="بالغ" eng="to exaggerate"/>
    <x-term-item arb="مبالغة" eng="exaggeration"/>
    <x-term-item arb="جاب قراره" eng="to find out everything"/>
    <x-term-item arb="دلّع" eng="to be sweet to; to pamper, to spoil"/>
    <x-term-item arb="ربّى" eng="to raise, to educate"/>
    <x-term-item arb="ترباية" eng="education (as a person); parenting"/>
    <x-term-item arb="رفض" eng="to refuse"/>
    <x-term-item arb="مرفوض" eng="unacceptable"/>
    <x-term-item arb="ركّز" eng="to focus, to concentrate, to pay attention"/>
    <x-term-item arb="مركّز" eng="focused, concentrated"/>
    <x-term-item arb="تركيز" eng="concentration"/>
    <x-term-item arb="سرح" eng="to daydream"/>
    <x-term-item arb="سرحان" eng="daydreaming"/>
    {{--            <x-term-item arb="صبغ" eng="to dye"/>--}}
    <x-term-item arb="انتبه (على)" eng="to notice; to pay attention (to one's surroundings)"/>
    <x-term-item arb="واجه" eng="to confront"/>
    <x-term-item arb="ردّة فعل" eng="reaction"/>
    <x-term-item arb="مراهقة" eng="teen age"/>
    <x-term-item arb="شرط" eng="condition"/>
    <x-term-item arb="عقل" eng="mind"/>
    <x-term-item arb="علامة" eng="grade, mark"/>
    <x-term-item arb="كلام" eng="someone's word"/>
    <x-term-item subterm arb="أخد كلام ~ جدّ" eng="to take ~'s word seriously"/>
    <x-term-item arb="صايع" eng="wayward, vagabond"/>
    <x-term-item arb="شخصيًّا" eng="personally"/>
    <x-term-item arb="يا خسارة" eng="that's too bad; what a shame, what a waste"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="activity-dialog">
        <x-dialog-line speaker="رنا" arb="بابا، ملاحظ إشي متغيّر فيّي؟"
                       eng="Dad, have you noticed anything that has changed about me?"/>
        <x-dialog-line speaker="أبو يزن" arb="نصحانة؟ لابسة أواعي أخوكي؟"
                       eng="you've gotten fat? you're wearing your brother's clothes?"/>
        <x-dialog-line arb="لأ، عرفت — شعرك بدّه حمّام"
                       eng="no, I know — your hair needs a bath"/>
        <x-dialog-line speaker="رنا" arb="عنجدّ بابا؟ هاذا اللي طلع معاك؟"
                       eng="really, Dad? this is what you came up with?"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 2">
    <div class="activity-dialog">
        <x-dialog-line speaker="رنا" arb="ماما، شو رأيك؟"
                       eng="Mom, what do you think"/>
        <x-dialog-line speaker="إمّ يزن" arb="زبطت"
                       eng="it worked"/>
        <x-dialog-line arb="آه حبيبتي شو كنتي عمّ تحكي؟"
                       eng="yes, darling, what were you saying?"/>
        <x-dialog-line speaker="رنا" arb="أصلًا أنا عاجبني اللون، وجيمي بحبّه هيك"
                       eng="I like this color anyway, & Jimmy likes it this way"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 3">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ يزن" arb="يا خسارة تربياتي فيها"
                       eng="what a waste, my parenting on her"/>
        <x-dialog-line arb="معقول رنا تعمل هيك؟ ما عمّ بصدّق"
                       eng="could Rana do such a thing? I'm not believing it"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="جيل صايع"
                       eng="a wayward generation"/>
        <x-dialog-line speaker="إمّ سند" arb="إمّ يزن، طوّلي بالك — طبّ إنتي سألتيها؟ واجهتيها؟"
                       eng="Imm Yazan, have patience — OK, did you ask her? did you confront her?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="وبتقلّك جيمي هيك بحبّ شعرها"
                       eng="& she tells you Jimmy likes her hair this way"/>
        <x-dialog-line speaker="إمّ سند" arb="طيّب أنا رح أقوم أعمل قهوة"
                       eng="OK, I'm going to get up & make coffee"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="قلتيلي جيمي؟ كمان بتدلّعه؟"
                       eng="you told me 'Jimmy'? she's sweet to him too?"/>
        <x-dialog-line arb="يعني شو بدّه يكون إسمه؟ جليل، جميل، جاسم؟"
                       eng="what will his name be? Jaleel, Jameel, Jasem?"/>
        <x-dialog-line speaker="إمّ يزن" arb="شو بعرّفني؟ وشو نعمل هلّأ؟"
                       eng="how should I know? & what (should) we do now?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="إنتي ولا يهمّك، أنا رح أجيبلك قراره"
                       eng="you don't worry about it, I'll get to the bottom of this"/>
        <x-dialog-line arb="إنتي بسّ خلّي عينك عليها — إذا قالت بدّي أطلع ما تقبلي"
                       eng="you keep your eye on her — if she says 'I want to go out' don't agree (to it)"/>
        <x-dialog-line arb="شوفي إذا بتقعد على التلفون كثير وقدّام المراية، بتسرح وما بتركّز"
                       eng="see if she sits on her phone a lot or in front of the mirror, & daydreams & doesn't concentrate"/>
        <x-dialog-line arb="بدّك توافيني بكلّ التفاصيل وخلّي الباقي عليّ"
                       eng="you'll provide me with all the details & leave the rest to me"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 4">
    <div class="activity-dialog">
        <x-dialog-line speaker="سالي" arb="كثير صعب الإمتحان — كثير كثير"
                       eng="the test is so hard — so, so much"/>
        <x-dialog-line speaker="إمّ سند" arb="شو رأيك تخلّي رنا تساعدك"
                       eng="how about you let Rana help you?"/>
        <x-dialog-line speaker="سالي" arb="طلبت منها — قال أمّها ما وافقت"
                       eng="I asked her (for help) — supposedly her mom didn't agree"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 5">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ يزن" arb="شو سرحانة، آه؟"
                       eng="daydreaming much, huh?"/>
        <x-dialog-line speaker="رنا" arb="ماما أيش مالك؟ ولا إشي، عمّ بدرس"
                       eng="Mom, what's going on with you? nothing, I'm just studying"/>
        <x-dialog-line speaker="إمّ يزن" arb="لأ، أنا شفتك سرحتي"
                       eng="no, I saw you drifted off"/>
        <x-dialog-line arb="وبعدين أنا حاسّة إنّه عقلك مش براسك — مش عارفة بشو"
                       eng="plus, I feel that your mind isn't in your head — I don't know in what"/>
        <x-dialog-line speaker="رنا" arb="مش فاهمة — ليش هيك عمّ بتقولي"
                       eng="I don't understand — why are you saying this?"/>
        <x-dialog-line speaker="إمّ يزن" arb="ما بعرف ليش — بسّ فيه تراجع بعلاماتك، وهاذا مرفوض"
                       eng="I don't know why — but there's a falling back in your grades, & this is unacceptable"/>
        <x-dialog-line arb="مفهوم كلامي؟"
                       eng="are my words understood?"/>
        <x-dialog-line speaker="رنا" arb="تراجع؟ طبّ إحنا لسّا ما أخدنا علامات الشهر الأوّل — شو فيه؟"
                       eng="falling back? but we still haven't received the first month's grades — what's going on?"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 6">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ إنصاف" arb="جلال، جاد، جرير، طبّ جورج؟"
                       eng="Jalal, Jad, Jareer, OK George?"/>
        <x-dialog-line arb="يي، ما حدا بردّ"
                       eng="huh, nobody is answering"/>
        <x-dialog-line arb="ما ضلّ أسماء بحرف الـ(ج)"
                       eng="there are no more names with the letter 'G'"/>
        <x-dialog-line arb="هيّهم"
                       eng="here they are"/>
        <x-dialog-line arb="قال بدّهم يروّحو لحالهم، حابّين يمشو"
                       eng="sure — 'they want to go home alone; they want to walk'"/>
        <x-dialog-line arb="لحالهم قال"
                       eng="'alone' — sure"/>
        <x-dialog-line speaker="رنا" arb="ما بعرف شو صارلها الماما — عمّ بتبالغ بكلّ إشي — مش عمّ بفهم ليش"
                       eng="I don't know what happened to Mom — she's overreacting to everything — I'm not understanding why"/>
        <x-dialog-line speaker="سالي" arb="معليش رنا، إحنا كمان مضغوطين عشان الإمتحانات"
                       eng="it's OK Rana, we're also under pressure because of the tests"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 7">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ إنصاف" arb="ورد أحمر، وقدّام البيت كمان؟"
                       eng="red flowers, & in front of the house too?"/>
        <x-dialog-line arb="شو هالرومانسيّة هاي؟"
                       eng="what's all this romance?"/>
        <x-dialog-line arb="إنتا جيمي صحّ؟"
                       eng="you're Jimmy, right?"/>
        <x-dialog-line arb="اللي صبغتي شعرك عشانه يا ستّ رنا"
                       eng="the one you dyed your hair for, Miss Rana"/>
        <x-dialog-line speaker="رنا" arb="شو؟"
                       eng="what?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="هاتي أشوف شو كاتبلك عالكرت سيّد روميو"
                       eng="give me to see what Mr. Romeo has written you the card"/>
        <x-dialog-line speaker="أبو يزن" arb="سوري بابا إنّي ما انتبهت على شعراتك الحلوين"
                       eng="sorry darling for not noticing your beautiful hair"/>
        <x-dialog-line arb="أنا بسّ بدّي أقلّك إنّك أحلى بكثير من هذول الوردات — بحبّك، أبوكي"
                       eng="I just want to tell you that you're much more beautiful than these flowers — I love you, Dad"/>
        <x-dialog-line speaker="زلمة" arb="تمام هيك؟ بقدر أروح؟"
                       eng="is this good? can I go?"/>
        <x-dialog-line speaker="رنا" arb="آه، يسلمو إيديك"
                       eng="yes, thank you"/>
        <x-dialog-line arb="آه هلّأ فهمت — بليز آنتي، خبّري الماما إنّه جيمي هو المغنّي المفضّل عندي، مش صاحبي"
                       eng="ah, now I understood — please auntie, let Mom know that Jimmy is my favorite singer, not my boyfriend"/>
        <x-dialog-line speaker="سالي" arb="يي مأزكاه العمّه — عنجدّ كثير حلوين هالوردات رنا"
                       eng="oh, how sweet Uncle (Abu Yazan) is — these flowers are really so beautiful, Rana"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 8">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ يزن" arb="إنتي ليش ما بتخبّريني عنّك"
                       eng="why don't you tell me about yourself?"/>
        <x-dialog-line arb="شو بتحبّي أغاني ومغنّيين"
                       eng="what songs & singers you like"/>
        <x-dialog-line arb="كان ما صار اللي صار"
                       eng="what happened wouldn't have happened"/>
        <x-dialog-line speaker="رنا" arb="ماما، كلّ هاذا عملتيه وإنتي مش متأكّدة أصلًا مين هو جيمي"
                       eng="Mom, you did all of this while not even being sure who Jimmy is"/>
        <x-dialog-line arb="وما سألتيني شخصيًّا عنّه"
                       eng="& you didn't ask me personally about him"/>
        <x-dialog-line arb="كيف بدّك ايّاني أشاركك بأفكاري وشو بصير معي إذا إنتي هيك ردّة فعلك دايمًا؟"
                       eng="how do you want me to share with you my thoughts & what happens in my life if this is always your reaction?"/>
        <x-dialog-line speaker="إمّ يزن" arb="رنّوش، أنا بعتذر منّك، أنا بالغت"
                       eng="Rannoush, I apologize, & I exaggerated"/>
        <x-dialog-line arb="ومزبوط كان لازم أحكي معك وأسمع منّك"
                       eng="& right, I should have spoken with you & heard from you"/>
        <x-dialog-line arb="شو رأيك نطلع شوبنج مع بعض"
                       eng="how about we go out shopping together"/>
        <x-dialog-line speaker="رنا" arb="على شرط — تعزميني على شاورما"
                       eng="on one condition — you invite me to shawarma"/>
        <x-dialog-line speaker="إمّ يزن" arb="اتّفقنا — طيّب خبّريني شو أسماء ولاد صفّك"
                       eng="deal — alright then, tell me what the names of your classmates are"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 9">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ سند" arb="ولادنا صارو بعمر المراهقة وهاي المرحلة صعبة"
                       eng="our children are now in the teenage years & this is a difficult stage"/>
        <x-dialog-line arb="ولازم نرافقهم فيها"
                       eng="& we must accompany them through it"/>
        <x-dialog-line arb="عشان هيك، لازم نخلّي قنوات التواصل والحوار دايمًا مفتوحة"
                       eng="because of that, we should keep the channels of communication & dialogue always open"/>
        <x-dialog-line speaker="إمّ يزن" arb="وبلاها المبالغة بردود أفعالنا — لإنّها بتبني حواجز كثير بيننا وبينهم"
                       eng="& let's not exaggerate in our reactions, because it raises many barriers between us & them"/>
        <x-dialog-line speaker="أبو يزن" arb="وكثير مهمّ إحنا كآباء نعبّر عن محبّتنا لبناتنا"
                       eng="& it's very important that we as fathers express our affection for our daughters"/>
        <x-dialog-line arb="بالكلام الحلو والورد والهدايا والوقت"
                       eng="with nice words, flowers, gifts & time"/>
        <x-dialog-line arb="ونخفّف انتقادات وتكون تعليقاتنا إيجابيّة، ترفع معنوياتهم وتقرّبنا منّهم أكثر"
                       eng="& that we tone down criticisms & that our comments be positive, lift their spirits & bring us closer to them"/>
        <x-dialog-line arb="ولّا لأ يا أبو سند"
                       eng="isn't that so, Abu Sanad?"/>
    </div>
</x-activity-area>
