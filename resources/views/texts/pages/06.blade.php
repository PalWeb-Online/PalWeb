<x-page-head title="the Big Game"
             blurb="Everyone is late to the game — literally! But not everyone in town is a responsible driver, especially not when they're in a rush!">
    <x-link :href="route('texts.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('texts.show', '06')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/qu4jnh5MHlc" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    <x-term-item arb="حافظ" eng="to take care of, to keep safe"/>
    <x-term-item arb="تحلّل" eng="to dissolve, to degrade"/>
    <x-term-item arb="دبّر" eng="to get, to find, to obtain"/>
    <x-term-item arb="دبّر حاله" eng="to manage, to get by; to figure it out on one's own"/>
    <x-term-item arb="زاح" eng="to move over, to scoot over"/>
    <x-term-item arb="شدّ على" eng="to motivate, to encourage, to pressure (someone)"/>
    <x-term-item arb="ضوى" eng="to turn on"/>
    <x-term-item arb="طفى" eng="to turn off"/>
    <x-term-item arb="عرّض" eng="to expose (to something)"/>
    <x-term-item arb="لمّ" eng="to collect, to pick up (e.g. garbage)"/>
    <x-term-item arb="نقذ" eng="to save (someone)"/>
    <x-term-item arb="مباراة" eng="match"/>
    <x-term-item arb="بيئة" eng="environment (i.e. nature)"/>
    <x-term-item arb="خطر" eng="danger"/>
    <x-term-item arb="زينة" eng="decoration; ornament"/>
    <x-term-item arb="إسعاف" eng="first aid">
        <x-term-item subterm arb="(سيّارة) إسعاف" eng="ambulance"/>
    </x-term-item>
    <x-term-item arb="عصبيّة" eng="anger, wrath"/>
    <x-term-item arb="علم" eng="flag"/>
    <x-term-item arb="فكر" eng="thought, belief, opinion, consideration"/>
    <x-term-item arb="لزوم" eng="what is necessary; must-have, essential">
        <x-term-item subterm arb="زيادة عن اللزوم" eng="more than (what's) necessary; too much"/>
    </x-term-item>
    <x-term-item arb="هجمة" eng="attack"/>
    <x-term-item arb="استهتار" eng="recklessness"/>
    <x-term-item arb="خطير" eng="dangerous"/>
    <x-term-item arb="بعد" eng="still"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="activity-dialog">
        <x-dialog-line speaker="حجّة" arb="أنا عارفة يمّا — ما فيه داعي آجي معك"
                       eng="man, I don't know — there's no need for me to come with you"/>
        <x-dialog-line arb="روح إنتا انبسط، وبلاش تقعد تتغلّب فيّي"
                       eng="you go have fun & don't go on bothering yourself with me"/>
        <x-dialog-line speaker="زلمة" arb="ما بنبسط من دونك"
                       eng="I don't have fun without you"/>
        <x-dialog-line arb="وما تقلقي، كلّشي مرتّب وإحنا طالعين قبل بوقت كمان"
                       eng="& don't worry, everything is set up & we've left ahead of time as well"/>
        <x-dialog-line speaker="حجّة" arb="قبل بكثير زي الأجانب"
                       eng="ahead by a lot, like foreigners"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 2">
    <div class="activity-dialog">
        <x-dialog-line speaker="أبو إنصاف" arb="لشو كلّ هاذ — هو إحنا طالعين رحلة؟"
                       eng="what's all this for? is it that we're going on a trip?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="هاذا إسمه سناكس — لزوم القعدة"
                       eng="this is called 'snacks' — essentials for the gathering"/>
        <x-dialog-line arb="يا الله، لأيمتى بدّي أضلّ أفهّم فيك يا أبو إنصاف؟"
                       eng="my God, how long do I need to keep explaining (things) to you, Abu Insaf?"/>
        <x-dialog-line speaker="أبو إنصاف" arb="سناكس؟ قصدك تفاريح — تفاريح يا أم إنصاف"
                       eng="'snacks'? you mean refreshments — refreshments, Umm Insaf"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 3">
    <div class="activity-dialog">
        <x-dialog-line speaker="كهربجي" arb="يلّا يا ولاد تأخّرنا"
                       eng="come on kids, we're late"/>
        <x-dialog-line speaker="ولاد" arb="مرحبا خاله"
                       eng="hello Uncle"/>
        <x-dialog-line speaker="كهربجي" arb="وين الأعلام، وين الزينة، وين ربطات الشعر؟"
                       eng="where are the flags, where's the decoration, where are the hair ties?"/>
        <x-dialog-line speaker="ولد" arb="مإنتا هلّأ رح تشتريلنا عالطريق"
                       eng="well, you're going to buy (it) for us now on the way"/>
        <x-dialog-line speaker="كهربجي" arb="شو بتقول؟ أنا أشتري؟"
                       eng="what are you saying? I'm to buy (it)?"/>
        <x-dialog-line arb="مهو ثلثين الولد لخاله"
                       eng="he takes after his uncle (lit. two thirds of the child is his uncle's)"/>
        <x-dialog-line arb="يلّا إطلعو"
                       eng="come on, get in"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 4">
    <div class="activity-dialog">
        <x-dialog-line speaker="يزن" arb="بابا، مش قادر أصدّق إنه رح نحضر هاذي المباراة لايف"
                       eng="Dad, I can't believe that we're going to watch this match live"/>
        <x-dialog-line speaker="رنا" arb="أنا كثير جوعانة — ما أكلت إشي بالمدرسة"
                       eng="I'm really hungry — I didn't eat anything at school"/>
        <x-dialog-line speaker="أبو يزن" arb="ما فيه وقت نوقّف محلّ"
                       eng="there's no time for us to stop anywhere"/>
        <x-dialog-line speaker="إمّ يزن"
                       arb="خدو، هيّ عملتلكم شويّة ساندويشات وهيّ موز وتفّاح، دبّرو حالكم فيهم"
                       eng="here, I've just made you some sandwiches & here are some bananas & apples, use them to get by"/>
        <x-dialog-line speaker="يزن" arb="أعطيني، أنا كمان جوعان"
                       eng="give me, I'm hungry too"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 5">
    <div class="activity-dialog">
        <x-dialog-line speaker="أبو سند" arb="وال — شو هالأزمة هاي؟ قلتلكم لازم نطلع قبل"
                       eng="geez — what is this traffic? I told you we should leave before"/>
        <x-dialog-line speaker="سند" arb="يعني رح نتأخّر"
                       eng="so we're going to be late"/>
        <x-dialog-line speaker="إمّ سند" arb="انشاالله لأ حبيبي، ما تقلق"
                       eng="hopefully not my dear, don't worry"/>
        <x-dialog-line speaker="سالي" arb="بابا، مين فكرك رح يفوز اليوم؟"
                       eng="Dad, who do you think will win today?"/>
        <x-dialog-line speaker="أبو سند" arb="شو هاذا؟ مش معقول"
                       eng="what is this? it's unbelievable"/>
        <x-dialog-line arb="كيف هيك برمو من الشباك؟ شو هالإستهتار هاذا؟"
                       eng="how do they throw (stuff) out the window like this? what's this recklessness?"/>
        <x-dialog-line speaker="إمّ سند" arb="فيه حدا برمي هيك فبيته؟"
                       eng="does anyone throw (stuff) like this in his home?"/>
        <x-dialog-line arb="أيمتى بدّنا نحافظ على بلدنا زي ممنحافظ ع بيوتنا؟"
                       eng="when are we going to take care of our city like we take care of our homes?"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 6">
    <div class="activity-dialog">
        <x-dialog-line speaker="أبو يزن" arb="مين بالله، مين هاذا اللي شادّ على حاله، مين؟"
                       eng="who, by God, who is this (guy) that's so uptight (lit. pressuring himself), who?"/>
        <x-dialog-line arb="مدير البيئة شكله ورانا — مشّيها يا عمّي"
                       eng="it seems the manager of the environment is behind us — move along, bro"/>
        <x-dialog-line arb="فيه ناس أصلاً شغلها تلمّ — ومع هالشوب، كلّشي بتحلّل"
                       eng="there are people who's job is to collect (garbage) anyway — & with this heat, everything (bio)degrades"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 7">
    <div class="activity-dialog">
        <x-dialog-line speaker="ولد" arb="خاله، صار دوري"
                       eng="Uncle, it's my turn"/>
        <x-dialog-line speaker="كهربجي" arb="يلّا أعطي العلم لأختك بسرعة"
                       eng="come on, give the flag to your sister, quickly"/>
        <x-dialog-line arb="أنا مجنون أشتري علمين؟"
                       eng="am I crazy (enough) to buy two flags?"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 8">
    <div class="activity-dialog">
        <x-dialog-line speaker="أبو إنصاف" arb="يلّا عاد تحرّك — ماشي ع بيض؟"
                       eng="come on, get moving again — are you walking on eggs?"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="طوّل بالك، مش شايفه زلمة كبير بالعمر؟"
                       eng="be patient, don't you see he's an old man?"/>
        <x-dialog-line speaker="أبو إنصاف" arb="إنتي شايفة ليش أنا مش مطوّل بالي؟"
                       eng="are you're seeing why I'm not patient?"/>
        <x-dialog-line arb="هيّها طفت — عجبك؟"
                       eng="it just turned off — happy now? (lit. you liked it?)"/>
        <x-dialog-line speaker="إمّ إنصاف" arb="طبّ توكل شوكلاتة؟"
                       eng="well, (will) you eat some chocolate?"/>
        <x-dialog-line speaker="أبو إنصاف" arb="لأ شكرًا"
                       eng="no thank you"/>
        <x-dialog-line arb="وإذا بنضلّ بأجواء الرحلة والسناكس تبعك رح تخلص المباراة وإحنا بعدنا عالطريق"
                       eng="& if we stay in the spirit(s) of the trip & your 'snacks', the match is going to end while we're still on the way"/>
        <x-dialog-line arb="يلّا تحرك — مش شايفها ضوت؟"
                       eng="come on, move — don't you see it turned on?"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 9">
    <div class="activity-dialog">
        <x-dialog-line speaker="إمّ يزن" arb="زيح أبو يزن بسرعة"
                       eng="move over, Abu Yazan, quickly"/>
        <x-dialog-line speaker="أبو يزن" arb="طبعًا رح أزيح — بسّ عشان تفتحلي طريق"
                       eng="of course I'm going to move over — but so it'll open a way for me"/>
        <x-dialog-line arb="هيك أكيد ما رح نتأخّر"
                       eng="this way we definitely won't be late"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 10">
    <div class="activity-dialog">
        <x-dialog-line speaker="سند" arb="بابا، بلّشت المباراة"
                       eng="Dad, the game started"/>
        <x-dialog-line speaker="أبو سند" arb="شو؟ لسا بدّنا ربع ساعة عالأقلّ — إفتح من موبايلك"
                       eng="what? we still need 15 minutes at least — open (it) from your phone"/>
        <x-dialog-line speaker="سند" arb="يلّا"
                       eng="come on"/>
        <x-dialog-line speaker="أبو سند" arb="حطّه هون، خلّينا نشوف"
                       eng="put it here, let us see"/>
        <x-dialog-line speaker="إمّ سند" arb="عصام، انتبه عالسواقة"
                       eng="Isam, pay attention to the driving"/>
        <x-dialog-line speaker="أبو سند" arb="ما تخافي، صارلي أكثر من ٣٠ سنة بسوق"
                       eng="don't be afraid, I've been driving for over 30 years"/>
        <x-dialog-line speaker="سند" arb="هجمة"
                       eng="a move from the offense (lit. an attack)"/>
        <x-dialog-line speaker="أبو سند" arb="شو؟ أشوف"
                       eng="what? let me see"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 11">
    <div class="activity-dialog">
        <x-dialog-line speaker="كهربجي" arb="لا"
                       eng="no"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 12">
    <div class="activity-dialog">
        <x-dialog-line speaker="حجّة" arb="جول — أيوى يا شباب"
                       eng="goal — yeah guys"/>
    </div>
</x-activity-area>
<x-activity-area title="{{ __('scene') }} 13">
    <div class="activity-dialog">
        <x-dialog-line speaker="أبو سند" arb="هالمرّة، ما تعملو مثلنا"
                       eng="this time, don't do as we (did)"/>
        <x-dialog-line arb="عمرك ما تلتهي عن الطريق وأوعك تمسك موبايلك وإنتا بتسوق"
                       eng="don't ever get distracted from the road & be careful not to grab your phone while you're driving"/>
        <x-dialog-line speaker="كهربجي"
                       arb="وبيني وبينكو، قصّة الولاد الطالعين برّا الشبابيك كثير خطيرة"
                       eng="& between you & I, the thing where kids put themselves out the window is very dangerous"/>
        <x-dialog-line arb="الله ستر هالمرّة"
                       eng="God shielded (us) this time"/>
        <x-dialog-line speaker="أبو يزن" arb="بتعرفو، أنا تعلّمت درس مرتّب"
                       eng="you know, I learned a nice lesson"/>
        <x-dialog-line arb="المداقرة والعصبيّة بالسواقة بتحرق أعصابك وبتعرّضك وبتعرّض اللي معك للخطر"
                       eng="one-upmanship & anger in driving burns your nerves & exposes you & exposes those with you to danger"/>
        <x-dialog-line arb="إخي اعتبر إنّه اللي بالسيّارات اللي جنبك من أهلك وحافظ عليهم"
                       eng="my brother, consider that the one in the car that's next to you is from your family & keep them safe"/>
        <x-dialog-line arb="واعتبر الشارع مثل بيتك وحافظ عليه كمان"
                       eng="& consider the street like your home & take care of it as well"/>
        <x-dialog-line speaker="إمّ يزن"
                       arb="والله يطوّل بأعماركم ويعطيكم الصحّة — أفتحو مجال لسيّارات الإسعاف"
                       eng="& God lengthen your life & give you health — open space for ambulances"/>
        <x-dialog-line arb="ممكن تنقذو حياة"
                       eng="you could save a life"/>
        <x-dialog-line arb="لا تسكّرو الطريق عليهم، ولا تتشاطر وتلحقهم"
                       eng="don't close off the road for them, & don't be sneaky & follow them"/>
        <x-dialog-line speaker="زلمة"
                       arb="نصيحة منّي — إطلعو دايمًا قبل بوقت عشان تسوقو براحتكم"
                       eng="some advice from me — always leave ahead of time so you drive at your own pace"/>
        <x-dialog-line arb="وما يروح عليكم إشي — ولّا شو يمّا؟"
                       eng="& you don't miss out on anything — right Mom?"/>
        <x-dialog-line speaker="حجّة" arb="ميّة بالمية — هي أيمتى المباراة الجاية؟"
                       eng="100% — when is it, the next match?"/>
    </div>
</x-activity-area>
