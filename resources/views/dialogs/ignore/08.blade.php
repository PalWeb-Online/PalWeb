<x-page-head title="Thief!"
             blurb="Is someone stealing from the Abu Sanad family? Abu Sanad sets out to investigate what's really going on.">
    <x-link :href="route('dialogs.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('dialogs.show', '08')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/ZEpASYe3s0k" allowfullscreen></iframe>

<x-vocabulary title="verbs">
    <x-term-item arb="حسّ بـ ~" eng="to sympathize with ~"/>
    <x-term-item arb="داق" eng="to taste"/>
    <x-term-item arb="ربط" eng="to tie, to connect; to make a connection"/>
    <x-term-item arb="رفع" eng="to raise"/>
    <x-term-item arb="شرّب" eng="to give (someone something) to drink"/>
    <x-term-item arb="ضحّى" eng="to sacrifice"/>
    <x-term-item arb="كبر" eng="to grow"/>
    <x-term-item arb="لحّق" eng="to manage (to do something) on time"/>
    <x-term-item arb="لخّص" eng="to summarize"/>
    <x-term-item arb="تلخيص" eng="summary"/>
    <x-term-item arb="وتّر" eng="to stress (someone) out"/>
    <x-term-item arb="داعي" eng="call, need, reason (to do something)"/>
    <x-term-item arb="خطوة" eng="step"/>
    <x-term-item arb="تفصيل" eng="detail"/>
    <x-term-item arb="فنّان" eng="artist"/>
    <x-term-item arb="قراءة" eng="reading"/>
    <x-term-item arb="قالب" eng="mold"/>
    <x-term-item arb="قناة" eng="channel"/>
    <x-term-item arb="وحش" eng="beast"/>
    <x-term-item arb="وصفة" eng="recipe"/>
    <x-term-item arb="رهيب" eng="awesome"/>
    <x-term-item arb="محرّج" eng="embarassed"/>
    <x-term-item arb="مثل" eng="(Syria, Lebanon) like"/>
    <x-term-item arb="من ورا" eng="behind (someone's) back"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="شو سالي — كيف ماشية ماما؟"
                       eng="hey Sally — how are you doing, darling?"/>
        <x-dialog-line speaker="سالي" arb="مش منيح — الرواية كثير طويلة وما عمّ بتخلص"
                       eng="not good — the novel is very long & it's not ending"/>
        <x-dialog-line speaker="إمّ سند" arb="طبّ رح تلحّقي يعني؟"
                       eng="well, will you manage to do it (on time)?"/>
        <x-dialog-line speaker="سالي" arb="ماما، معليش بلاش هاي الأسئلة؟ بتوتّرني"
                       eng="Mom, would you mind not (asking) these questions? they stress me out"/>
        <x-dialog-line speaker="إمّ سند" arb="ألو؟ أهلًا إمّ يزن — شو، وين غاطسة؟ كيفكم؟"
                       eng="hello? hello Imm Yazan — what's going on, where've you been (submerged)? how are you?"/>
        <x-dialog-line speaker="إمّ يزن" arb="هيّنا، بنشكر الله"
                       eng="we're here, thank God"/>
        <x-dialog-line arb="إمّ سند، بدّي أسألك عن طريقة الكبّة"
                       eng="Imm Sanad, I need to ask you about the way of (making) kubbeh"/>
        <x-dialog-line speaker="إمّ سند" arb="شو إمّ يزن، صايرة تتفنّني بالطبخ من ورانا"
                       eng="what's going on Imm Yazan, you've started being creative with (your) cooking behind our backs"/>
        <x-dialog-line speaker="إمّ يزن"
                       arb="هو أنا شاطرة بالطبخ، بسّ فيه أشياء من زمان ما عملتها ونسيتها"
                       eng="(the thing is) I'm good at cooking, but there are things I haven't made in a long time & forgot"/>
        <x-dialog-line speaker="إمّ سند" arb="طيّب، خلّيني أشرحلك الخطوات بالتفصيل"
                       eng="OK, let me explain the steps to you in detail"/>
        <x-dialog-line arb="وفيه قناة عاليوتوب، بدلّك عليها — رهيبة"
                       eng="& there's a channel on Youtube, I'll show you to it — it's awesome"/>
        <x-dialog-line speaker="إمّ يزن"
                       arb="طيّب، دقيقة وحدة أجيب ورقة وقلم، بدّي آخد كمان أكم وصفة"
                       eng="OK, (give me) one minute to bring a pen & paper, I'd like to get a few more recipes"/>
        <x-dialog-line speaker="إمّ سند" arb="أكم وصفة؟ كلّه بسعره إمّ يزن"
                       eng="a few recipes? all of it for its price Imm Yazan"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 2">
    <div class="dialog-body">
        <x-dialog-line speaker="أبو سند" arb="مرحبا يا جماعة"
                       eng="hello everyone"/>
        <x-dialog-line speaker="سند" arb="أهلين بابا — جبت تفّاح؟"
                       eng="hey Dad — did you bring apples?"/>
        <x-dialog-line speaker="أبو سند" arb="جبت — أنا مش فاهم إنتا من أيمتى صرت تحبّ التفّاح"
                       eng="I did — I don't understand since when you started liking apples"/>
        <x-dialog-line speaker="سند" arb="وهيّ الخيار وشبس الكاتشاب وشوكلاطة — شكرًا بابا"
                       eng="& here's the cucumber, the ketchup chips & chocolate — thanks Dad"/>
        <x-dialog-line speaker="أبو سند" arb="سمالله، مش ملحّق عليكم — سحّيبة صايرين الشباب"
                       eng="bless your heart, I'm not keeping up with you — the youngsters have become greedy"/>
        <x-dialog-line speaker="إمّ سند" arb="يعطيك العافية أبو سند — أعطيني عنّك"
                       eng="God bless your work Abu Sanad, let me take something (lit. give me from you)"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 3">
    <div class="dialog-body">
        <x-dialog-line speaker="ستّ" arb="وهيك بتكبكب الكبّة، عشان تطلع كلّ وحدة مثل إختا بالضبط"
                       eng="& that's how you roll the kubbeh, so each one comes out exactly like the other (lit. its sister)"/>
        <x-dialog-line speaker="إمّ يزن" arb="مثل أختها أكيد — خالق الناطق"
                       eng="like the other, sure ..."/>
        <x-dialog-line arb="يا ربّي، كيف بتطلع معهم هيك؟"
                       eng="my God, how does it come out like that for them?"/>
        <x-dialog-line speaker="ستّ" arb="لا تقولي كيف بتطلع هيك"
                       eng="don't say 'how does it come out like that'"/>
        <x-dialog-line speaker="إمّ يزن" arb="يمّا"
                       eng="oh my"/>
        <x-dialog-line speaker="ستّ" arb="مرّة على مرّة إيدك بتاخد عالشغل"
                       eng="with each time your hand will take to the work"/>
        <x-dialog-line arb="وإذا ما زبطت، استخدمي مثل هالقالب"
                       eng="& if (that) didn't work, use a mold like this"/>
        <x-dialog-line speaker="إمّ يزن" arb="طبّ قولي هيك من أوّل"
                       eng="then say that from the start"/>
        <x-dialog-line speaker="ستّ" arb="عمّ قول استخدمي القالب"
                       eng="I'm saying use the mold"/>
        <x-dialog-line speaker="إمّ يزن" arb="كيف هاي سامعتني؟"
                       eng="how is this (lady) hearing me?"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 4">
    <div class="dialog-body">
        <x-dialog-line speaker="سالي" arb="كثير طويل الواجب"
                       eng="the assignment is so long"/>
        <x-dialog-line arb="هاي رنا — بتصدّقي إنّه لهلّأ ما خلّصت التلخيص؟"
                       eng="hey Rana — can you believe that until now I (still) haven't finished the summary?"/>
        <x-dialog-line arb="إنتي شو صار معك؟ ما بديتي؟ كيف رح تلحّقي؟"
                       eng="what happened to you? you haven't started? how will you manage (to do it on time)?"/>
        <x-dialog-line arb="ما شريتي الرواية؟ صارلها أسبوع المسّ طالبة نلخّصها"
                       eng="you didn't buy the novel? the teacher has been asking us to summarize it for a week"/>
        <x-dialog-line arb="مهو شو؟ كيف يعني؟"
                       eng="because of what? how?"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 5">
    <div class="dialog-body">
        <x-dialog-line speaker="أبو سند" arb="حبيبي سند، مش صعبة كلمة (شطآن)"
                       eng="my dear Sanad, the word 'shores' isn't hard"/>
        <x-dialog-line arb="[...]"
                       eng="[...]"/>
        <x-dialog-line arb="(الشطآن) جمع (شاطئ)"
                       eng="'the shores' is the plural of 'shore'"/>
        <x-dialog-line speaker="سند" arb="بابا أنا مش مركّز — جوعان"
                       eng="Dad, I'm not focused — I'm hungry"/>
        <x-dialog-line speaker="أبو سند" arb="طبّ روح كل إشي وإرجعلي — أركض"
                       eng="OK, go eat something & come back — run"/>
        <x-dialog-line speaker="سند"
                       arb="بابا ما عندنا إشي نوكله — بدّنا فواكه وخضار وتشبس وشوكلاطة"
                       eng="Dad, we don't have anything to eat — we need fruit & vegetables & chips & chocolate"/>
        <x-dialog-line speaker="أبو سند" arb="شو؟ لسّا مبارح جايب أغراض"
                       eng="what? just yesterday I've brought stuff"/>
        <x-dialog-line speaker="سند" arb="مش إنتو بتقولولنا كلو صحّتين، لازم توكلو عشان تكبرو"
                       eng="aren't you the ones who say to us 'eat up, you must eat so you can grow'"/>
        <x-dialog-line speaker="أبو سند" arb="مزبوط — خد الكتاب وراجع القراءة بسرعة"
                       eng="right — take the book & go over the reading quickly"/>
        <x-dialog-line arb="دينا — تعالي شويّ، بدّي أحكيلك إشي"
                       eng="Dina — come for a sec, I want to tell you something"/>
        <x-dialog-line speaker="إمّ سند" arb="شو فيه — إيش مالك؟"
                       eng="what is it — what's wrong?"/>
        <x-dialog-line speaker="أبو سند" arb="إسمعي، ولا يمكن يكونو الأغراض اللي جبتهم مبارح خلّصو"
                       eng="listen, there's no way the stuff I brought yesterday could be finished"/>
        <x-dialog-line arb="شو إحنا مربّيين وحوش؟ فيه إنَّ"
                       eng="what, have we raised beasts? there's a mystery (lit. an 'if')"/>
        <x-dialog-line arb="رح أروح أشتري أغراض، ورح أعرف شو القصّة"
                       eng="I'm going to go buy stuff, & I'll find out what the story is"/>
        <x-dialog-line speaker="إمّ سند" arb="طيّب معليش، بسّ خلّيني أروح أشوف مين عالباب وبرجعلك"
                       eng="OK fine, but let me go see who's at the door & I'll come back to you"/>
        <x-dialog-line speaker="أبو سند" arb="[...]"
                       eng="[...]"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 6">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="أهلا وسهلا إمّ يزن، تفضّلي — شو معاكي، أشوف؟"
                       eng="welcome Imm Yazan, come in — what do you have with you, can I see?"/>
        <x-dialog-line speaker="إمّ يزن" arb="لا، بسّ جاية أعطيكي هالأكمّ حبّة كبّة تدوقيهم"
                       eng="please, I've just come to give you these few kubbehs for you to taste"/>
        <x-dialog-line arb="طلعو بشهّو وكلّ حبّة مثل إختا"
                       eng="they came out delicious & each one like the other"/>
        <x-dialog-line speaker="إمّ سند" arb="ياي مأزكى ريحتهم، وشكلهم مرتّب كثير — تسلم هالإيدين"
                       eng="wow how delicious their smell, & their form is really nice — bless these hands"/>
        <x-dialog-line arb="ما كان فيه داعي تغلّبي حالك إمّ يزن"
                       eng="there was no need for you to trouble yourself Imm Yazan"/>
        <x-dialog-line arb="يعني مزبوط أعطيتك الوصفة، بسّ يعني فش داعي للغلبة"
                       eng="it's true I gave you the recipe, but there's no need for the trouble"/>
        <x-dialog-line speaker="إمّ يزن" arb="إذا عجبوكي بقدر أعمل كمان، يعني إذا بدّك مساعدة"
                       eng="if you like them I can make more, if you need help"/>
        <x-dialog-line arb="وصرت أعمل كمان تبّولة وورق دوالي ومحاشي"
                       eng="& I started making tabbouleh, grape leaves & stuffed vegetables as well"/>
        <x-dialog-line speaker="إمّ سند" arb="طول عمرك فنّانة إمّ يزن"
                       eng="an artist your whole life, Umm Yazan"/>
        <x-dialog-line arb="طيّب أدخلي أشرّبك إشي — شو دّنا نضلّنا واقفين عالباب؟"
                       eng="alright come in so I give you something to drink — what, are we going to keep standing at the door?"/>
        <x-dialog-line speaker="إمّ يزن"
                       arb="لا، فيه أكمّ صحن بدّي أعطيهم لإمّ إنصاف والجيران، دواقة كمان"
                       eng="no, there are some plates I need to give to Umm Insaf & the neighbors, sampling as well"/>
        <x-dialog-line arb="خلّيني أوصّلهم قبل ميبردو — سلام"
                       eng="let me deliver them before they get cold — bye"/>
        <x-dialog-line speaker="إمّ سند" arb="سلام حبيبتي — يمي، بشهّو"
                       eng="bye, my dear — yummy, they're delicious"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 7">
    <div class="dialog-body">
        <x-dialog-line speaker="أبو سند" arb="[...]"
                       eng="[...]"/>
        <x-dialog-line speaker="إمّ سند" arb="صفر؟ سالي؟"
                       eng="zero? Sally?"/>
        <x-dialog-line speaker="أبو سند" arb="وقّف عندك وإرفع إيديك — سند؟"
                       eng="stop where you are & raise your hands — Sanad?"/>
        <x-dialog-line speaker="إمّ سند" arb="إحنا هلّأ بدّنا نفهم شو اللي عمّ بصير"
                       eng="we need to understand what's going on right now"/>
        <x-dialog-line speaker="أبو سند" arb="إنتا وين عمّ بتروح بكلّ هاذ الأكل؟"
                       eng="where are you going with all this food?"/>
        <x-dialog-line speaker="إمّ سند" arb="وإنتي سالي — كيف أخدتي صفر فالتلخيص بعد كلّ هالتعب؟"
                       eng="& you, Sally — how did you get a zero on the summary after all that work (lit. exhaustion)?"/>
        <x-dialog-line speaker="سند" arb="بصراحة، يزن صارله فترة مش عمّ بجيب معه أكل على المدرسة"
                       eng="honestly, Yazan hasn't been bringing food to school for some time"/>
        <x-dialog-line arb="ولا الأشياء اللي بحبّها، وكلّ مأسأله ليش، ما بردّ"
                       eng="nor any of the stuff that he likes, & every time I ask him why, he doesn't answer"/>
        <x-dialog-line arb="فأنا عملت هيك عشان أساعده"
                       eng="so I did this to help him"/>
        <x-dialog-line speaker="سالي" arb="وأنا رنا قالتلي إنّها مش قادرة تشتري الرواية"
                       eng="& as for me, Rana told me that she can't buy the novel"/>
        <x-dialog-line arb="عشان هيك ما لخّصت"
                       eng="that's why she didn't summarize"/>
        <x-dialog-line arb="وكانت كثير محرّجة وقلقانة شو رح تحكي للمعلّمة قدّام الصفّ"
                       eng="& she was very embarrassed & anxious (about) what she'd say to the teacher in front of the class"/>
        <x-dialog-line arb="فأعطيتها كتابي وما لحّقت أنا ألخّص"
                       eng="so I gave her my book & I didn't manage to summarize"/>
        <x-dialog-line speaker="أبو سند" arb="يا ولاد، أنا عنجدّ مبسوط فيكم وباللي عملتوه"
                       eng="kids, I'm very pleased with you & what you did"/>
        <x-dialog-line arb="إنتو حسّيتو فصحابكم وضحّيتو عشانهم"
                       eng="you felt for your friends & made a sacrifice for them"/>
        <x-dialog-line arb="ولو حكيتولنا كنّا منقدر نساعد أكثر كمان"
                       eng="if you had told us, we would've been able to help more as well"/>
        <x-dialog-line speaker="إمّ سند" arb="مزبوط، الواحد لازم يساعد — بسّ سالي، لو حكيتيلنا"
                       eng="true, one must help — but Sally, if you'd told us"/>
        <x-dialog-line arb="كنت أنا رح أشتريلها الكتاب، وما أخدتي صفر"
                       eng="I would've bought her the book, & you wouldn't have gotten a zero"/>
        <x-dialog-line arb="دقيقة — معقول عشان هيك إمّ يزن عمّ تطبخ وبتبعتلنا ندوق كلّ شويّ؟"
                       eng="one minute — could it be this is why Umm Yazan is cooking & sending us (things) to taste every so often?"/>
        <x-dialog-line arb="كيف ما انتبهت ولا حتّى ربطت؟"
                       eng="how didn't I notice or even make the connection?"/>
        <x-dialog-line speaker="سند" arb="ماما، إحنا لازم نعمل إشي"
                       eng="Mom, we should do something"/>
        <x-dialog-line arb="همّه ما بقولو بسّ أكيد محتاجين مساعدتنا"
                       eng="they don't say (so), but of course they need our help"/>
        <x-dialog-line speaker="إمّ سند" arb="خلّي الموضوع عليّ، أنا بعرف شو رح أعمل"
                       eng="leave the issue to me, I know what I'll do"/>
    </div>
</x-activity-area>

<x-activity-area title="{{ __('scene') }} 8">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ يزن" arb="مش هيك يا أبو يزن — قطّع البقدونس ناعم"
                       eng="not like that Abu Yazan — dice the parsley finely"/>
        <x-dialog-line arb="التبّولة بدّها تقطيع ناعم"
                       eng="tabbouleh requires fine dicing"/>
        <x-dialog-line speaker="أبو يزن" arb="أنعم من هيك؟"
                       eng="finer than this?"/>
        <x-dialog-line speaker="إمّ يزن" arb="هيّ إجانا كمان أوردرين كبّة — حضّر حالك"
                       eng="two more kubbeh orders just came — get ready"/>
        <x-dialog-line speaker="أبو يزن" arb="لأ، مش كبّة"
                       eng="no, not kubbeh"/>
        <x-dialog-line speaker="إمّ يزن"
                       arb="يي، هاي جارتنا في العمارة الثانية — كمان بدّها طبخة محاشي"
                       eng="oh, this is our neighbor in the other building — she wants a plate of stuffed vegetables too"/>
        <x-dialog-line arb="من وقت معملت إمّ سند هالجروب واستلمت التسويق"
                       eng="ever since Imm Sanad made this group & took over the marketing"/>
        <x-dialog-line arb="والشغل نار — الحمدلله"
                       eng="work has been great (lit. fire) — thank God"/>
        <x-dialog-line arb="هيّ إمّ إنصاف بتسأل على الطلبيّة — رح أطلع وأوصّللها ايّاها"
                       eng="Imm Insaf is asking about the order — I'm going to go up & deliver it to her"/>
        <x-dialog-line arb="مش تشلفق وأنا مش هون، قطّع البصل ناعم"
                       eng="don't slack off while I'm not here, dice the onion finely"/>
        <x-dialog-line speaker="أبو يزن" arb="حاضر" eng="yes ma'am"/>
    </div>
</x-activity-area>
