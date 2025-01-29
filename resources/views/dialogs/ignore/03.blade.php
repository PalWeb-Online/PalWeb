<x-page-head title="Stranger Danger"
             blurb="Sanad learns about stranger danger the hard way after Sally saves him from getting kidnapped.">
    <x-link :href="route('dialogs.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('dialogs.show', '03')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/jg9CHjnI1GM" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    <x-term-item arb="بلّغ" eng="to report"/>
    <x-term-item arb="بهدل" eng="to scold, to tell off"/>
    <x-term-item arb="حزر" eng="to guess"/>
    <x-term-item arb="حزّن" eng="to sadden">
        <x-term-item subterm arb="حزّن ~ على ~" eng="to make ~ feel sorry for ~"/>
    </x-term-item>
    <x-term-item arb="خطف" eng="to kidnap"/>
    <x-term-item arb="تخوّت (على)" eng="to joke around; to poke fun (at)"/>
    <x-term-item arb="دلّ" eng="to guide, to show (someone) the way"/>
    <x-term-item arb="ركب" eng="to get on or ride (a vehicle, etc.)"/>
    <x-term-item arb="طنّش" eng="to ignore"/>
    <x-term-item arb="غلط" eng="to be wrong, to make a mistake"/>
    <x-term-item arb="عاد" eng="to do (something) again; to redo, to repeat"/>
    <x-term-item arb="وثق" eng="to trust"/>
    <x-term-item arb="فضح" eng="to embarrass (someone) in front of others"/>
    <x-term-item arb="وزّع" eng="to distribute"/>
    <x-term-item arb="وعي" eng="to be careful, aware, wary">
        <x-term-item subterm arb="أوعك (~)" eng="don't; careful not to ~"/>
    </x-term-item>
    <x-term-item arb="وعّى" eng="to raise (someone's) awareness"/>
    <x-term-item arb="جريمة" eng="crime"/>
    <x-term-item arb="مجرم" eng="criminal"/>
    <x-term-item arb="دور" eng="role"/>
    <x-term-item arb="ذهب" eng="gold"/>
    <x-term-item arb="مصلحة" eng="advantage, benefit, interest, sake">
        <x-term-item subterm arb="لمصلحة ~" eng="for ~'s sake, for ~'s own good"/>
    </x-term-item>
    <x-term-item arb="هوس" eng="obsession"/>
    <x-term-item arb="مسدود" eng="obstructed, blocked, plugged, clogged, sealed"/>
    <x-term-item arb="خفّ على ~" eng="(usually sardonic) give ~ a break, tone it down"/>
    <x-term-item arb="الله ستر" eng="God shielded; thank God (nothing bad happened)"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="سالي، إحزري شو صار معي اليوم"
                       eng="Sally, guess what happened to me today"/>
        <x-dialog-line speaker="سالي" arb="شو؟"
                       eng="what?"/>
        <x-dialog-line speaker="سند"
                       arb="اليوم وديع كان ماشي بالصفّ بتخوّت عليّ بصوت عالي، عامل حاله كول"
                       eng="today Wadee' was walking in class, poking fun at me out loud, acting cool"/>
        <x-dialog-line arb="طنّشته، لفّ لقى المسّ بوجهه — أمّا شو تبهدلّك بهدلة"
                       eng="I ignored him, he turned & found the teacher in his face — how she scolds you a scolding"/>
        <x-dialog-line speaker="سالي" arb="طبعًا، وإنتا شايف المسّ"
                       eng="of course, & you had seen the teacher"/>
        <x-dialog-line speaker="سند" arb="مهو عشان هيك طنّشته أصلًا"
                       eng="that's exactly why I ignored him to begin with"/>
        <x-dialog-line speaker="سالي" arb="يي، شوف مأحلاها"
                       eng="oh, look how cute she is"/>
        <x-dialog-line speaker="سند" arb="أخّ، إنتي وهوسك بالحيوانات"
                       eng="ugh, you & your obsession with animals"/>
        <x-dialog-line speaker="زلمة" arb="مرحبا يا شطّور، شو إسمك؟ إنتا ساكن هون؟"
                       eng="hello young man, what's your name? do you live here?"/>
        <x-dialog-line speaker="سند" arb="آه عمّه، إسمي سند"
                       eng="yes sir, my name is Sanad"/>
        <x-dialog-line speaker="زلمة" arb="وين بيتك؟"
                       eng="where's your house?"/>
        <x-dialog-line speaker="سند" arb="بيتي بالشارع هناك"
                       eng="my house is on the street over there"/>
        <x-dialog-line speaker="زلمة" arb="عمّ بدوّر على صيدليّة — إبني مريض كثير وبدّه دوا ضروريّ"
                       eng="I'm looking for a pharmacy — my son is very sick & needs some essential medicine"/>
        <x-dialog-line arb="بتعرف صيدليّة قريبة؟"
                       eng="do you know a nearby pharmacy?"/>
        <x-dialog-line speaker="سند" arb="آه أكيد، فيه صيدليّة فالشارع الثاني"
                       eng="yes of course, there's a pharmacy on the other street"/>
        <x-dialog-line arb="يعني بتلفّ من هون وبترجع بتدخل يمين"
                       eng="you turn from here & take a right again"/>
        <x-dialog-line speaker="زلمة" arb="مرّيت كثير من هذاك الشارع"
                       eng="I passed by that street a lot"/>
        <x-dialog-line arb="شو رأيك تيجي معي تفرجيني وين؟ وأنا برجّعك على بيتك —"
                       eng="how about you come with me & show me where? & I'll bring you back to your house —"/>
        <x-dialog-line speaker="سالي" arb="سند"
                       eng="Sanad"/>
        <x-dialog-line speaker="سند" arb="شو مالك؟"
                       eng="what's wrong?"/>
        <x-dialog-line speaker="سالي" arb="مين هذول؟ وشو بدّهم فيك؟"
                       eng="who are those (guys)? & what do they need from you?"/>
        <x-dialog-line speaker="سند" arb="فضحتيني كإنّي ولد زغير عاملة حالك المحقّق كونان"
                       eng="you embarrased me in public as though I were a little child, acting like Detective Conan"/>
        <x-dialog-line arb="يا حرام، الزلمة إبنه مريض وبدّه ايّاني أدلّه ع صيدليّة"
                       eng="poor guy, his son is sick & he wants me to show him to a pharmacy"/>
        <x-dialog-line speaker="سالي" arb="سند، إنتا ما بتعرفهم — كيف كنت بدّك تطلع معهم بالسيّارة"
                       eng="Sanad, you don't know them — how were you going to leave with them in the car?"/>
        <x-dialog-line speaker="سند" arb="يعني يموت إبنه وهو بدوّر ع صيدليّة؟"
                       eng="let his son die, then, while he's looking for a pharmacy?"/>
        <x-dialog-line arb="لعبت دور الأخت الكبيرة — خفّي علينا"
                       eng="she played the role of the older sister — give us a break"/>
        <x-dialog-line speaker="سالي" arb="طبّ يلّا بلاش نتأخّر والماما تقلق علينا"
                       eng="OK let's go so we aren't late & Mom doesn't worry about us"/>
        <x-dialog-line arb="بسّ نفسي أفهم كيف كنت رح تطلع معهم"
                       eng="but I want to understand how you were going to leave with them"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 2">
    <div class="dialog-body">
        <x-dialog-line speaker="أبو سند" arb="مرحبا يا حلوين"
                       eng="hello my darlings"/>
        <x-dialog-line speaker="سالي" arb="أهلين بابا"
                       eng="hello Dad"/>
        <x-dialog-line speaker="سند" arb="هاي بابا"
                       eng="hi Dad"/>
        <x-dialog-line speaker="إمّ سند" arb="يعطيك العافية، خلّيني أقوم أجهّزلك الأكل"
                       eng="God bless your work, let me get up & prepare the food for you"/>
        <x-dialog-line speaker="أبو سند"
                       arb="ما تغلّبي حالك حبيبتي، نفسي مسدودة بعد هالخبر اللي سمعته"
                       eng="don't bother yourself my dear, I've lost my appetite after this news I heard"/>
        <x-dialog-line speaker="إمّ سند" arb="خبر شو؟ خير؟"
                       eng="news of what? is everything alright?"/>
        <x-dialog-line speaker="أبو سند"
                       arb="طلال، إبن أبو طلال — بتعرفيهم — اللي عندهم مكتبة بآخر الشارع"
                       eng="Talal, Abu Talal's son — you know them — the ones that have a bookstore at the end of the street"/>
        <x-dialog-line speaker="إمّ سند" arb="آه، شو ماله؟"
                       eng="yeah, what's wrong with him?"/>
        <x-dialog-line speaker="أبو سند"
                       arb="كان بلعب بالشارع جنب المكتبة وإجت سيّارة بتسأله عن صيدليّة قريبة"
                       eng="he was playing in the street next to the bookstore & a car came asking him about a nearby pharmacy"/>
        <x-dialog-line arb="تخيّلي، حاولو يخطفوه — الله ستر إنّه أبوه كان بالمكتبة وانتبه"
                       eng="imagine, they tried to kidnap him — thank God his father was in the store & noticed"/>
        <x-dialog-line arb="ولّا كان راح فيها الولد"
                       eng="otherwise the boy would have gone in it"/>
        <x-dialog-line speaker="إمّ سند" arb="شو بتحكي؟ معقول؟ شو صاير بهالدنيا؟"
                       eng="what are you saying? could it be? what's happening in this world?"/>
        <x-dialog-line speaker="أبو سند" arb="زي مبقلّك، والشرطة هيّها بتدوّر عالسيّارة"
                       eng="as I'm telling you — & the police is looking for the car right now"/>
        <x-dialog-line speaker="سند" arb="معقول نفس السيّارة؟"
                       eng="could it be that it's the same car?"/>
        <x-dialog-line speaker="سالي" arb="يلّا، إحكيلهم شو صار"
                       eng="come on, tell them what happened"/>
        <x-dialog-line speaker="سند" arb="أصلًا أنا أكيد ما كنت رح أطلع معهم"
                       eng="of course I wasn't going to leave with them in the first place"/>
        <x-dialog-line speaker="سالي" arb="أكيد — يا حرام، الزلمة إبنه مريض — يموت يعني؟"
                       eng="sure — 'poor guy, his son is sick — let him die, then?'"/>
        <x-dialog-line speaker="أبو سند" arb="قدّيش مرّة حكينا — أوعك تحكي للغرباء أيّ معلومات عنّك"
                       eng="how many times have we said — be careful not to tell strangers any information about you"/>
        <x-dialog-line speaker="أبو سند"
                       arb="لا إسمك ولا وين بيتك ولا مدرستك، ولا منطلع مع حدا غريب"
                       eng="not your name or where your house or your school is, nor do we leave with a stranger"/>
        <x-dialog-line speaker="سالي" arb="ولا ... وبقول عنّي محقّق كونان كمان"
                       eng="& no ... & he calls me Detective Conan too"/>
        <x-dialog-line speaker="إمّ سند" arb="وحتّى لو حدا حزّنك عليه وطلب منّك تروح معه وتساعده"
                       eng="& even if someone made you feel sorry for them & asked you to go with them to help them"/>
        <x-dialog-line arb="ما لازم تردّ، وبتقلّه روح أطلب مساعدة من حدا كبير"
                       eng="you shouldn't do as they say, & you tell them to go ask for help from an adult"/>
        <x-dialog-line arb="وحتّى لو قلّك البابا أو الماما بقولولك"
                       eng="& even if they told you Dad & Mom are telling you"/>
        <x-dialog-line arb="تعال إطلع معي — ما تردّ عليهم"
                       eng="'come leave with me' — don't do as they say"/>
        <x-dialog-line speaker="أبو سند" arb="برافو عليكي يا بابا إنّك وقّفتيه من إنّه يطلع"
                       eng="bravo honey that you stopped him from going"/>
        <x-dialog-line arb="بس كان لازم تحكي معنا على طول وتخبّرينا"
                       eng="but you should have spoken with us right away & let us know"/>
        <x-dialog-line speaker="سند" arb="أنا غلطت — متأسّف"
                       eng="I made a mistake — I'm sorry"/>
        <x-dialog-line speaker="أبو سند" arb="أوعك تعيدها، مفهوم؟"
                       eng="don't repeat it, understood?"/>
        <x-dialog-line arb="إحنا منحكي هالحكي لمصلحتكم"
                       eng="we're saying this for your own good"/>
        <x-dialog-line arb="خلّيني أروح عالشرطة أبلّغ عن اللي صار معكم"
                       eng="let me go to the police to report on what happened to you"/>
        <x-dialog-line speaker="سند" arb="بابا خدني معك — أنا متذكّر شكل الزلمة، يعني ممكن أساعد"
                       eng="Dad take me with you — I remember what the guy looked like, so I can help"/>
        <x-dialog-line speaker="سالي" arb="وأنا — يمكن عندي صورة السيّارة"
                       eng="& me — I might have the car's picture"/>
        <x-dialog-line speaker="سالي" arb="لمّا كنت بصوّر بالبسّة، مبيّنة السيّارة من بعيد"
                       eng="when I was taking photos of the cat, the car is visible from afar"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 3">
    <div class="dialog-body">
        <x-dialog-line speaker="شرطيّ" arb="وشو كان لون السيّارة؟"
                       eng="& what was the color of the car?"/>
        <x-dialog-line speaker="سند" arb="السيّارة كان لونها أخضر على ذهبيّ"
                       eng="the car's color was metallic (lit. golden) green"/>
        <x-dialog-line speaker="سالي" arb="وهيّ صورتها"
                       eng="& here's its picture"/>
        <x-dialog-line speaker="شرطيّ"
                       arb="شكرًا يا ولاد، هاي معلومات جدًّا مهمّة — هيّ بعتتها للمركز الرئيسيّ"
                       eng="thank you kids, this information is very important — I've just sent it to the main center"/>
        <x-dialog-line arb="وأكيد رح تساعدنا نمسك هالمجرمين — وشكر لأبو سند كمان"
                       eng="& it's definitely going to help us catch these criminals — & thanks to Abu Sanad too"/>
        <x-dialog-line speaker="أبو سند" arb="هاذا واجبنا"
                       eng="this is our duty"/>
        <x-dialog-line speaker="شرطيّ" arb="خدو يا ولاد وراق تعليمات السلامة وإقروها منيح"
                       eng="kids, take the safety information papers & read them well"/>
        <x-dialog-line arb="ووزّعوها على صحابكم وجيرانكم عشان نوعّيهم كيف يتعاملو مع الغرباء"
                       eng="& distribute them to your friends & neighbors so we can warn them of how to interact with strangers"/>
        <x-dialog-line arb="بعض الأشخاص الغرباء يمكن يبيّنو لطيفين، بسّ بكونو خطر على حياتنا"
                       eng="some strangers may appear nice, but would be a danger to our lives"/>
        <x-dialog-line arb="ما بصير أحكي لأيّ شخص غريب معلومات عنّي"
                       eng="I can't tell any stranger information about myself"/>
        <x-dialog-line arb="أو عن بيتي أو عن مدرستي، ولا أركب معه بالسيّارة"
                       eng="or about my house or about my school, or ride in the car with them"/>
        <x-dialog-line arb="ولا أروح معاه أيّ مكان ولا آكل أو أشرب أيّ شي بقدّمولي ايّاه"
                       eng="or go anywhere with them, or eat or drink anything they offer me"/>
        <x-dialog-line arb="حتّى لو قلّي تعال أفرجيك إشي أو ساعدني بهالإشي"
                       eng="even if they tell me 'come so I can show you something' or 'help me with this'"/>
        <x-dialog-line arb="لازم أهرب وأطلب المساعدة من حدا بثق فيه"
                       eng="I should get away & ask for help from someone I trust"/>
        <x-dialog-line arb="أنا جاي فورًا"
                       eng="I'm coming immediately"/>
        <x-dialog-line speaker="أبو سند" arb="شو سيدي؟ لقوه؟"
                       eng="what is it, sir? did they find him?"/>
        <x-dialog-line speaker="شرطيّ"
                       arb="هيّ لقوه، وأنا لازم أتوجّه للموقع — أنا فخور فيكم جدًّا يا ولاد"
                       eng="they've just found him & I must head to the site — I'm very proud of you, kids"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 4">
    <div class="dialog-body">
        <x-dialog-line speaker="سند" arb="وإنتو كمان يا أصحاب، إذا بتحبّو تنزّلو تعليمات السلامة"
                       eng="& you too friends, if you'd like to download the safety information"/>
        <x-dialog-line arb="عشان تستفيدو منّها أو توزّعوها ع صحابكم، إكبسو هون"
                       eng="to learn from it or distribute it to your friends, click here"/>
    </div>
</x-activity-area>
