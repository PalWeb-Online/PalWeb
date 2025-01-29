<x-page-head title="True Friends"
             blurb="Not just anyone should be your friend. Sally & Sanad learn to stay true to themselves despite the bad influences around them.">
    <x-link :href="route('dialogs.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('dialogs.show', '07')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/OldaBo1rqBI" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    <x-term-item arb="خلّط" eng="to mix"/>
    <x-term-item arb="ردّ على" eng="to listen to (i.e. obey), to do as (someone) says"/>
    <x-term-item arb="صاحب" eng="to befriend"/>
    {{--            <x-term-item arb="صوّر" eng="to film, to photograph"/>--}}
    <x-term-item arb="ضاف" eng="to attach; (online) to add"/>
    <x-term-item arb="غار" eng="to be jealous"/>
    <x-term-item arb="غيران" eng="jealous"/>
    <x-term-item arb="فصل" eng="to separate"/>
    {{--            <x-term-item arb="قسّم" eng="to divide, to split up"/>--}}
    {{--            <x-term-item arb="قدّم" eng="to present, to offer"/>--}}
    <x-term-item arb="قضى" eng="to spend (time)"/>
    <x-term-item arb="لاحظ" eng="to notice, to realize"/>
    <x-term-item arb="وثق" eng="to trust"/>
    {{--            <x-term-item arb="وصّل" eng="to take (somewhere); to deliver"/>--}}
    {{--            <x-term-item arb="توقّع" eng="to expect"/>--}}
    <x-term-item arb="أثّر" eng="to affect"/>
    {{--            <x-term-item arb="مجموعة" eng="group"/>--}}
    <x-term-item arb="رسالة" eng="message"/>
    <x-term-item arb="شخصيّة" eng="personality; character"/>
    {{--            <x-term-item arb="مشروع" eng="project"/>--}}
    <x-term-item arb="تصرّف" eng="behavior"/>
    <x-term-item arb="طفولة" eng="childhood"/>
    <x-term-item arb="معظم" eng="majority; most"/>
    {{--            <x-term-item arb="فرصة" eng="opportunity"/>--}}
    <x-term-item arb="قصد" eng="intention; (intended) meaning"/>
    <x-term-item arb="إحتياط" eng="precaution"/>
    <x-term-item subterm arb="للإحتياط" eng="just in case"/>
    <x-term-item arb="رأي" eng="opinion"/>
    <x-term-item subterm arb="شو رأيك (بـ)" eng="how about ...; what do you think (of ...)"/>
    <x-term-item arb="بعض" eng="each other"/>
    <x-term-item arb="مزعج" eng="upsetting"/>
    <x-term-item arb="عشوائيّ" eng="random"/>
    <x-term-item arb="فخور" eng="proud"/>
    <x-term-item arb="مش بس هيك" eng="that's not all"/>
</x-vocabulary>

{{--                <p>1. Partner up with a friend and fill in the following dialogue.</p>--}}
{{--                <div class="arbtr"><span class="char orn">أنا</span> هلا فلان، كيفك؟ شو الأخبار؟</div>--}}
{{--                <div class="arbtr"><span class="char blu">فلان</span> تمام، ماشي الحال.</div>--}}
{{--                <div class="arbtr"><span class="char orn">أنا</span> بدّي أسألك، شو رأيك بـ--}}
{{--                    <mark>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</mark>--}}
{{--                    ؟--}}
{{--                </div>--}}
{{--                <div class="arbtr"><span class="char blu">فلان</span> هادا سؤال منيح — شَخصيًّا بَعتقِد إنّه--}}
{{--                    <mark>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</mark>--}}
{{--                    .--}}
{{--                </div>--}}
{{--                <div class="attribute small right agree"></div>--}}
{{--                <div class="arbtr"><span class="char orn">أنا</span> والله معك حقّ.</div>--}}
{{--                <div class="arbtr"><span class="char blu">فلان</span> آه، ومش بس هيك — برضه،--}}
{{--                    <mark>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</mark>--}}
{{--                    .--}}
{{--                </div>--}}
{{--                <div class="attribute small right disagree"></div>--}}
{{--                <div class="arbtr"><span class="char orn">أنا</span> عن جد؟ بالنسبة إلي،--}}
{{--                    <mark>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</mark>--}}
{{--                    .--}}
{{--                </div>--}}
{{--                <div class="arbtr"><span class="char blu">فلان</span> ما بعرف — أنا مش شايفه هيك.</div>--}}

<x-activity-area title="{{ __('scene') }} 1">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="أهلين بالحلوين — شو، كيف أوّل يوم مدرسة؟"
                       eng="hello sweeties — so, how's the first day of school?"/>
        <x-dialog-line speaker="سالي"
                       arb="زي متوقّعت — خربشو كلّ الصفوف ورنا طلعت مش بصفّي"
                       eng="as I expected — they mixed up all the classes & Rana turned out not in my class"/>
        <x-dialog-line speaker="سند"
                       arb="وإحنا كمان خلّطونا، بسّ المهمّ إنّه نديم طلع معي بالصفّ"
                       eng="they mixed us too, but what matters is that Nadim turned out with me in class"/>
        <x-dialog-line arb="صحابي معي وإنتي لأ"
                       eng="my friends are with me & you aren't"/>
        <x-dialog-line speaker="سالي" arb="ماما، شوفي سند"
                       eng="Mom, look at Sanad"/>
        <x-dialog-line speaker="إمّ سند"
                       arb="حبيبتي، أنا بعرف إنّه مزعج إنّه فصلو الصحاب عن بعض"
                       eng="my dear, I know it's upsetting that they separated friends from each other"/>
        <x-dialog-line arb="بسّ ممكن هاي فرصة تتعرّفي على صحاب جداد"
                       eng="but it's possible this is a chance for you to meet new friends"/>
        <x-dialog-line speaker="سند" arb="إحنا ما أعطونا واجبات، أنا داخل ألعب"
                       eng="they didn't give us homework, I'm going in to play"/>
        <x-dialog-line speaker="سالي" arb="ومش بسّ هيك، قسّمونا مجموعات عشوائيّة"
                       eng="& that's not all, they split us (into) random groups"/>
        <x-dialog-line arb="ولازم نبدا نشتغل عالمشروع الإنجليزيّ"
                       eng="& we have to start working on the English project"/>
        <x-dialog-line speaker="إمّ سند" arb="طيّب شو رأيك بهالفكرة — تعزمي المجموعة عندك"
                       eng="OK, what do you think of this idea — you invite the group to your place"/>
        <x-dialog-line arb="منّها بتشتغلو عالمشروع ومنّها بتتعرّفي عليهم"
                       eng="that way, you work on the project & get to know them"/>
        <x-dialog-line arb="وأنا بعملكم أشياء زاكية"
                       eng="& I'll make you some treats"/>
        <x-dialog-line speaker="سالي" arb="أوك ماما، ماشي"
                       eng="alright Mom, sounds good"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 2">
    <div class="dialog-body">
        <x-dialog-line speaker="سالي" arb="أنا بقول لبكرا خلّينا نقدّم هدول الثلاث شخصيّات"
                       eng="I say for tomorrow let's present these three characters"/>
        <x-dialog-line arb="ونستنّى نشوف شو بتحكي المسّ"
                       eng="& wait to see what the teacher says"/>
        <x-dialog-line speaker="صاحبة" arb="أوك يلّا نزبّطها، لإنّه بعد شويّ بوصلو أهلي"
                       eng="OK let's fix it, because my parents are arriving soon"/>
        <x-dialog-line arb="لبنى، يلّا تعالي — شو بتعملي؟"
                       eng="Lubna, let's go, come — what are you doing?"/>
        <x-dialog-line speaker="لبنى" arb="بتصوّر، بدّي أنزّل بوست جروب وورك"
                       eng="I'm taking selfies, I want to upload a 'group work' post"/>
        <x-dialog-line arb="بسّ ما عمّ تطبع معي الصورة زي مبدّي"
                       eng="but the picture isn't coming out for me like I want"/>
        <x-dialog-line speaker="إمّ سند" arb="تفضّلو يا صبايا"
                       eng="help yourselves, girls"/>
        <x-dialog-line speaker="صاحبة" arb="شكرًا آنتي، غلّبتي حالك"
                       eng="thank you auntie, you troubled yourself"/>
        <x-dialog-line speaker="سالي" arb="شكرًا ماما" eng="thanks Mom"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 3">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="دير بالك فراس، هيّ فيه واحد طالع من ورا الشجرة"
                       eng="be careful Firas, there's one coming out now from behind the tree"/>
        <x-dialog-line speaker="إمّ سند" arb="سند، مين هاذا فراس؟"
                       eng="Sanad, who is this Firas?"/>
        <x-dialog-line arb="مش حكينا إنّه ما منضيف حدا ما منعرفه؟"
                       eng="isn't it so that we said that we don't add anyone we don't know?"/>
        <x-dialog-line speaker="سند" arb="ماما، هاذا ولد جديد بصفّي"
                       eng="Mom, this is a new kid in my class"/>
        <x-dialog-line speaker="إمّ سند"
                       arb="طبّ يّلا، خلص وقتك عالجيمز — إنهي معه وروح إقرألك كتاب"
                       eng="alright let’s go, your time on games is over — finish with him & go read yourself a book"/>
        <x-dialog-line speaker="سند" arb="ماما، بسّ خمس دقايق — أوك ماشي"
                       eng="Mom, just five minutes — OK, fine"/>
        <x-dialog-line arb="يلّا فراس، أنا لازم أروح — خلص وقتي"
                       eng="alright Firas, I have to go — my time is over"/>
        <x-dialog-line speaker="فراس" arb="خلص وقتك؟ أنتا بتردّ ع أهلك يا زلمة؟"
                       eng="your time is up? you obey your parents, man?"/>
        <x-dialog-line arb="أنا بلعب قدّ مبدّي — إنسى، شوف هالحركة"
                       eng="I play as much as I want — forget it, check out this move"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 4">
    <div class="dialog-body">
        <x-dialog-line speaker="صاحبة" arb="باي آنتي"
                       eng="bye auntie"/>
        <x-dialog-line speaker="لبنى"
                       arb="يلّا باي — سالي، ما تنسي تحطّي إسمي عالمشروع، آه؟"
                       eng="alright bye — Sally, don't forget to write my name on the project, yeah?"/>
        <x-dialog-line speaker="سالي" arb="ماما، لبنى ما قبلت تشتغل معنا ولا إشي"
                       eng="Mom, Lubna didn't agree to work on anything with us"/>
        <x-dialog-line arb="ضلّت تصوّر حالها زي كإنّها بتشتغل معنا"
                       eng="she kept taking pictures of herself as though she were working with us"/>
        <x-dialog-line speaker="إمّ سند" arb="آه، لاحظت عليها لمّا فتت عندكم"
                       eng="yeah, I realized that about her when I came in"/>
        <x-dialog-line speaker="سالي" arb="بسّ هي ماما كثير منيحة وعندها كثير صحاب"
                       eng="but Mom she's great & has many friends"/>
        <x-dialog-line arb="وبتعرف أخبار الكلّ — بتصدّقي إنّها بتقلّي إنّه رنا قالتلها"
                       eng="& she knows about everyone — can you believe she tells me that Rana told her"/>
        <x-dialog-line arb="إنّي أنا بغار منّها لإنّه صوتها حلو؟ وهي زعلانة من رنا كيف هيك بتقول عنّي"
                       eng="that I'm jealous of her because her voice is nice? & she's upset at Rana (about) how she could say that about me"/>
        <x-dialog-line speaker="إمّ سند"
                       arb="معقول، رنا؟ رنا، صديقة الطفولة؟ ما أظنّ رنا بتحكي هيك إشي"
                       eng="really, Rana? Rana, the childhood friend? I don't think Rana would say such a thing"/>
        <x-dialog-line arb="أصلًا أنا هاي لبنى ما عجبتني أبدًا — تصرّفاتها بالمرّة ما عجبتني"
                       eng="I wasn't impressed by this Lubna at all in the first place — I wasn't impressed by her behavior at all"/>
        <x-dialog-line speaker="سالي" arb="شكله خلص — بعد مغيّرو الصفوف، تغيّر كلّ إشي"
                       eng="it seems like that's it — after they changed the classes, everything changed"/>
        <x-dialog-line speaker="إمّ سند" arb="سند، شو حكينا؟"
                       eng="Sanad, what did we say?"/>
        <x-dialog-line speaker="سند" arb="أوك"
                       eng="OK"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 5">
    <div class="dialog-body">
        <x-dialog-line speaker="سالي" arb="ماما، بابا، بصير حدا يوصّلني عند لبنى بسرعة؟"
                       eng="Mom, Dad, could someone take me to Lubna's really quick?"/>
        <x-dialog-line speaker="أبو سند" arb="مين لبنى"
                       eng="who's Lubna?"/>
        <x-dialog-line speaker="سالي" arb="صاحبتي الجديدة"
                       eng="my new friend"/>
        <x-dialog-line speaker="إمّ سند" arb="آه لبنى؟ خلص، أنا بوصّلك"
                       eng="oh, Lubna? alright, I'll take you"/>
        <x-dialog-line arb="سالي، إنتي أوّل مرّة بتروحي عند لبنى"
                       eng="Sally, it's the first time you're going to Lubna's"/>
        <x-dialog-line arb="وإحنا لا منعرفها، ولا منعرف أهلها، بسّ أنا بثق فيكي"
                       eng="& we don't know her, nor do we know her parents, but I trust you"/>
        <x-dialog-line speaker="سالي" arb="ماما، شو بدّه يصير يعني؟"
                       eng="Mom, what's going to happen?"/>
        <x-dialog-line speaker="إمّ سند" arb="لأ، ولا إشي — بسّ للإحتياط للإحتياط"
                       eng="no, nothing — but just in case"/>
        <x-dialog-line arb="شو رأيك نعمل رسالة سرّيّة بيني وبينك؟"
                       eng="how about we create a secret message between us?"/>
        <x-dialog-line arb="إذا فيه أيّ إشي ما ارتحليله، إبعتيلي ثلاث قلوب خضر"
                       eng="if there’s anything you’re not comfortable with, send me three green hearts"/>
        <x-dialog-line arb="هيك بفهم عليكي إنّه قصدك (إحكي معي هلّأ، قوليلي لازم تروّحيني)"
                       eng="that way I'll understand that you mean 'talk with me now, say you have to take me home'"/>
        <x-dialog-line speaker="سالي" arb="حاسّة حالي قاعدة مع المحقّق كونان"
                       eng="I feel like I'm sitting with Detective Conan"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 6">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="أيوى، برافو فراس"
                       eng="yeah, good job Firas"/>
        <x-dialog-line arb="إسمع استنّوني شويّ، بدّي أروح عالحمّام وهلّأ برجع"
                       eng="hey wait a bit for me, I'm going to the bathroom & I'll be right back"/>
        <x-dialog-line speaker="فراس" arb="وين سند الـ — يلّا، شو صارله هالـ؟"
                       eng="where is Sanad the *** — come on, what happened to that ***?"/>
        <x-dialog-line arb="مش عارف يلعب — شوف هالـ"
                       eng="he doesn't know how to play — check out this ***"/>
        <x-dialog-line speaker="سند" arb="بابا، إيش عمّ بتعمل؟"
                       eng="Dad, what are you doing?"/>
        <x-dialog-line speaker="أبو سند"
                       arb="بابا، مين هاذا — وشو هالألفاظ السيّئة اللي بحكيها؟"
                       eng="son, who is this? & what are these bad words he's saying?"/>
        <x-dialog-line speaker="سند"
                       arb="هاذا ولد جديد بالصفّ — هو بضلّه يسبسب، بسّ كثير شاطر بالجيمز"
                       eng="this is a new kid in class — he keeps cursing, but he's so good at games"/>
        <x-dialog-line arb="يعني قلتلّه ألف مرّة ما يضلّه يسبسب، وما يغلط بالحكي، بسّ بابا"
                       eng="I told him a thousand times to not keep cursing, & not be insulting, but Dad"/>
        <x-dialog-line speaker="أبو سند"
                       arb="بابا، ما تسمح لحدا مين مكان يغلط عليك، حتّى لو بالمزح"
                       eng="son, don't let anyone — whoever it may be — insult you, even if as a joke"/>
        <x-dialog-line arb="وما بحبّ تصاحب ولاد بستخدمو ألفاظ سيّئة"
                       eng="& I don't want you to befriend kids who use cusswords"/>
        <x-dialog-line arb="لإنّه الناس اللي بتقضي معهم معظم وقتك، همّه اللي بأثرو عليك"
                       eng="because the people with whom you spend most of your time are the ones who affect you"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 7">
    <div class="dialog-body">
        <x-dialog-line speaker="سالي" arb="لبنى، من أوّل مإجيت وإنتي بتحطّي ميكاب"
                       eng="Lubna, from the moment I arrived you've been putting on makeup"/>
        <x-dialog-line arb="فيه حدا جاية غيري؟"
                       eng="is anyone coming other than me?"/>
        <x-dialog-line speaker="لبنى" arb="حبيبتي إحنا رح نطلع، ما رح نضلّ بالبيت"
                       eng="my dear we're going out, we're not going to stay at home"/>
        <x-dialog-line arb="ورح أعرّفك ع شلّة أكبر منّنا"
                       eng="& I'm going to introduce you to a clique older than us"/>
        <x-dialog-line arb="مش من مدرستنا"
                       eng="not from our school"/>
        <x-dialog-line speaker="سالي" arb="بس إنتي ما قلتيلي هيك، وأنا ما خبّرت أهلي"
                       eng="but you didn't tell me that, & I didn't let my parents know"/>
        <x-dialog-line speaker="لبنى" arb="عنجدّ؟ إنتي لسّا بتخبّري أهلك؟"
                       eng="really? you still let your parents know?"/>
        <x-dialog-line arb="خلص، مهمّه بفكّروكي عندي، شو رح يفرق يعني؟"
                       eng="enough, they think you're at my place, so what difference will it make?"/>
        <x-dialog-line arb="يلّا رح أروح أجيب شنطتي بسرعة"
                       eng="alright, I'm going to go get my backpack really quick"/>
        <x-dialog-line arb="قال ما خبّرت أهلي قال"
                       eng="'I didn't let my parents know'"/>
        <x-dialog-line arb="مش عارفة أيمتى آخر مرّة سمعت هاي الجملة"
                       eng="I don't know when the last time I heard that sentence was"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 8">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="مش معقول، شو هاذ؟ ساكنة بآخر الدنيا؟"
                       eng="unbelievable, what is this? do I live at the end of the world?"/>
        <x-dialog-line arb="ألو ماما سالي؟ أنا لازم آجي آخدك هلّأ — حضّري حالك"
                       eng="hello Sally dear? I have to come get you now — get ready"/>
        <x-dialog-line arb="ماما، شو صار؟"
                       eng="what happened, dear?"/>
        <x-dialog-line speaker="سالي"
                       arb="كانت بدّها ايّانا نروح معها ع محلّ ما بعرفه، مع ناس ما بعرفهم"
                       eng="she wanted us to go with her to a place I don't know, with people I don't know"/>
        <x-dialog-line arb="بدون مأحكيلكم — وأنا بصراحة بالمرّة ما ارتحت"
                       eng="without telling you guys — & honestly I wasn't comfortable at all"/>
        <x-dialog-line speaker="إمّ سند"
                       arb="برافو حبيبتي إنّك أخدتي القرار الصحّ — أنا فخورة فيكي كثير"
                       eng="good job my dear for making the right decision — I'm very proud of you"/>
        <x-dialog-line arb="شو رأيك هلّأ تحكي مع رنا وأوصّلك عندها"
                       eng="how about now you go speak with Rana & I'll take you to her place"/>
        <x-dialog-line speaker="سالي" arb="أظنّ هيك رح أعمل"
                       eng="I think that's what I'll do"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 9">
    <div class="dialog-body">
        <x-dialog-line speaker="سالي" arb="فكرة الماما إنّه يكون بيناتنا رسالة سرّيّة"
                       eng="Mom's idea that there be a secret message between us"/>
        <x-dialog-line arb="لمّا أكون مش مرتاحة، فكرة عبقريّة"
                       eng="when I'm not comfortable, is a genius idea"/>
        <x-dialog-line speaker="إمّ سند" arb="وأنا بشجّعكم تخترعو رمز سرّيّ إنتو وأولادكم"
                       eng="& I encourage you & your children to invent a secret code"/>
        <x-dialog-line arb="يبعتولكم ايّاه لمّا يحسّو حالهم بمواقف مش مريحة"
                       eng="for them to send you when they feel they're in uncomfortable situations"/>
        <x-dialog-line arb="وإذا بدّكم أفكار أكثر عن الموضوع، إضغطو هون"
                       eng="& if you want more ideas on the topic, press here"/>
        <x-dialog-line speaker="سالي" arb="وما تنسو تشاركو الفديو مع أصحابكم"
                       eng="& don't forget to share the video with your friends"/>
    </div>
</x-activity-area>
