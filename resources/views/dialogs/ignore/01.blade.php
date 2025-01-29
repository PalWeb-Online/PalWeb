<x-page-head title="Coronavirus!"
             blurb="The Abu Sanad family offers some advice & talk about how they're going to stay safe & healthy during the COVID-19 pandemic.">
    <x-link :href="route('dialogs.index')">{{ __('texts') }}</x-link>
    <x-link :href="route('dialogs.show', '01')">{{ __('view') }}</x-link>
</x-page-head>

<iframe src="https://www.youtube.com/embed/U-Hcuclljkw" allowfullscreen></iframe>

<x-vocabulary title="vocabulary">
    <x-term-item arb="حمى" eng="to protect"/>
    <x-term-item arb="اضطرّ" eng="to be obligated"/>
    <x-term-item arb="عطس" eng="to sneeze"/>
    <x-term-item arb="منع" eng="to forbid, to prevent"/>
    <x-term-item arb="تمنّى" eng="to hope"/>
    <x-term-item arb="نقل" eng="to move, to transport; to pass on"/>
    <x-term-item arb="جماعة" eng="community, people; everyone, folks"/>
    <x-term-item arb="تجمّع" eng="gathering"/>
    <x-term-item arb="حرارة" eng="fever"/>
    <x-term-item arb="حلق" eng="throat"/>
    <x-term-item arb="سطح" eng="surface"/>
    <x-term-item arb="مسؤوليّة" eng="responsibility"/>
    <x-term-item arb="أصبع" eng="finger"/>
    <x-term-item arb="أضفر" eng="nail"/>
    <x-term-item arb="عدوى" eng="infection"/>
    <x-term-item arb="فيروس" eng="virus"/>
    <x-term-item arb="قاعدة" eng="rule"/>
    <x-term-item arb="كمّامة" eng="facemask"/>
    <x-term-item arb="مشوار" eng="errand, outing"/>
    <x-term-item arb="طارئ" eng="emergency"/>
    <x-term-item arb="معدي" eng="infectious"/>
    <x-term-item arb="ممنوع" eng="forbidden"/>
    <x-term-item arb="أشكال وألوان" eng="of all kinds, types, sorts"/>
    <x-term-item arb="لا سمح الله" eng="God forbid"/>
</x-vocabulary>

<x-activity-area title="{{ __('scene') }} 1">
    <div class="dialog-body">
        <x-dialog-line speaker="إمّ سند" arb="مرحبا يا جماعة"
                       eng="hello everyone"/>
        <x-dialog-line arb="عمّ نسمع عن أمراض وفيروسات أشكال وألوان"
                       eng="we're hearing about diseases and viruses of all kinds"/>
        <x-dialog-line arb="إنفلونزا الطيور وإنفلونزا الخنازير"
                       eng="avian flu & swine flu"/>
        <x-dialog-line arb="وهلّا الكلّ بحكي عن فيروس كورونا"
                       eng="& now everyone is talking about the coronavirus"/>
        <x-dialog-line speaker="أبو سند" arb="بالنسبة إلنا عيلة أبو سند عملنا إجتماع طارئ"
                       eng="as for us, the Abu Sanad family, we had an emergency meeting"/>
        <x-dialog-line arb="وقرّرنا نعمل اللي علينا ونتّكل على ربّنا"
                       eng="& we decided to do what we must & trust in God"/>
        <x-dialog-line speaker="إمّ سند" arb="مثلًا، سند دايمًا دايمًا بنسى يغسّل إيديه"
                       eng="for example, Sanad always forgets to wash his hands"/>
        <x-dialog-line arb="فقرّرنا إنّه يكون عندنا قاعدة"
                       eng="so we decided that we would have a rule"/>
        <x-dialog-line arb="ممنوع حد يوكل أيّ إشي إلّا بعد ما يغسّل إيديه منيح"
                       eng="nobody may eat anything except after washing their hands well"/>
        <x-dialog-line speaker="أبو سند" arb="ممنوع حدا يمسك وجهه أو أنفه أو ثمّه أو عينيه"
                       eng="nobody may touch their face, nose, mouth, or eyes"/>
        <x-dialog-line arb="من دون ميكون مغسّل إيديه"
                       eng="without having washed their hands"/>
        <x-dialog-line speaker="إمّ سند" arb="هلّا منيجي لطريقة صحيحة لغسل إيدين"
                       eng="now we come to a correct way of washing hands"/>
        <x-dialog-line arb="لازم نغسّل إيدينا كثير منيح عالقليلة عشرين ثانية"
                       eng="we must wash our hands very well for at least twenty seconds"/>
        <x-dialog-line arb="وننظّف منيح بين أصابعنا وتحت اظافرنا وإيدينا من قدّام ومن ورا"
                       eng="& clean well between our fingers & under our nails & our hands front & back"/>
        <x-dialog-line speaker="أبو سند" arb="ثانيًا، قرّرنا إنّه ما نروح على تجمّعات كبيرة"
                       eng="secondly, we decided that we not go to large gather swine flu"/>
        <x-dialog-line arb="وهلّا الكلّ بحكي عن فيروس كورونا"
                       eng="& now everyone is talking about the coronavirus"/>
        <x-dialog-line speaker="أبو سند" arb="بالنسبة إلنا عيلة أبو سند عملنا إجتماع طارئ"
                       eng="as for us, the Abu Sanad family, we had an emergency meeting"/>
        <x-dialog-line arb="وقرّرنا نعمل اللي علينا ونتّكل على ربّنا"
                       eng="& we decided to do what we must & trust in God"/>
        <x-dialog-line speaker="إمّ سند" arb="مثلًا، سند دايمًا دايمًا بنسى يغسّل إيديه"
                       eng="for example, Sanad always forgets to wash his hands"/>
        <x-dialog-line arb="فقرّرنا إنّه يكون عندنا قاعدة"
                       eng="so we decided that we would have a rule"/>
        <x-dialog-line arb="ممنوع حد يوكل أيّ إشي إلّا بعد ما يغسّل إيديه منيح"
                       eng="nobody may eat anything except after washing their hands well"/>
        <x-dialog-line speaker="أبو سند" arb="ممنوع حدا يمسك وجهه أو أنفه أو ثمّه أو عينيه"
                       eng="nobody may touch their face, nose, mouth, or eyes"/>
        <x-dialog-line arb="من دون ميكون مغسّل إيديه"
                       eng="without having washed their hands"/>
        <x-dialog-line speaker="إمّ سند" arb="هلّا منيجي لطريقة صحيحة لغسل إيدين"
                       eng="now we come to a correct way of washing hands"/>
        <x-dialog-line arb="لازم نغسّل إيدينا كثير منيح عالقليلة عشرين ثانية"
                       eng="we must wash our hands very well for at least twenty seconds"/>
        <x-dialog-line arb="وننظّف منيح بين أصابعنا وتحت اظافرنا وإيدينا من قدّام ومن ورا"
                       eng="& clean well between our fingers & under our nails & our hands front & back"/>
        <x-dialog-line speaker="أبو سند" arb="ثانيًا، قرّرنا إنّه ما نروح على تجمّعات كبيرة"
                       eng="secondly, we decided that we not go to large gatherings"/>
        <x-dialog-line arb="وإذا اضطرّينا نطلع، بلاها عادة التبويس لمّا نسلّم على بعض"
                       eng="& if we had to go out, that we do without the custom of kissing when we greet each other"/>
        <x-dialog-line arb="ونتذّكر نغسل إيدينا منيح بعد المشوار"ings"/>
        <x-dialog-line arb="وإذا اضطرّينا نطلع، بلاها عادة التبويس لمّا نسلّم على بعض"
                       eng="& if we had to go out, that we do without the custom of kissing when we greet each other"/>
        <x-dialog-line arb="ونتذّكر نغسل إيدينا منيح بعد المشوار"
                       eng="& that we remember to wash our hands after the outing"/>
        <x-dialog-line speaker="إمّ سند" arb="ولازم نأكّد ع ولادنا إنّه ما يمسكو أيّ أسطح"
                       eng="& we must ensure that our children not touch any surfaces"/>
        <x-dialog-line arb="أو يحطّو وجوههم أو ثمّهم على أيّ إشي بمكان عامّ"
                       eng="or put their faces or mouth(s) on anything in a public place"/>
        <x-dialog-line speaker="أبو سند" arb="ثالث شي، سالي لمّا تعطس بتحمّمنا كلّنا"
                       eng="third, Sally showers all of us when she sneezes"/>
        <x-dialog-line arb="فقرّرنا إنّه أيّ حدا بعطس"
                       eng="so we decided that anyone who sneezes"/>
        <x-dialog-line arb="لازم يستخدم محرمة وبعدين يكبّها بالزبالة ويغسّل إيديه منيح"
                       eng="must use a napkin & toss it in the trash & wash their hands well"/>
        <x-dialog-line arb="وسالي صارت تعمل هيك وخبّرت صاحباتها يعملو نفس الإشي"
                       eng="& Sally started doing so & told her friends to do the same thing"/>
        <x-dialog-line arb="عشان نمنع إنتشار العدوى"
                       eng="so that we prevent the spread of infection"/>
        <x-dialog-line speaker="إمّ سند" arb="وإذا فيه حدا مريض — لا سمح الله — لازم يلبس كمّامة طبّيّة"
                       eng="& if there's someone sick — God forbid — they must wear a medical facemask"/>
        <x-dialog-line arb="عشان ما ينقل العدوى لللي حواليه"
                       eng="so as not to pass on the infection to those around them"/>
        <x-dialog-line arb="والكمّامة لازم تتغيّر وتنكبّ كلّ ثمن لعشر ساعات"
                       eng="& the facemask must be changed & tossed every eight to ten hours"/>
        <x-dialog-line speaker="أبو سند" arb="إذا لا سمح الله أيّ حدا فينا صار عنده حرارة ووجع بالحلق"
                       eng="if, God forbid, anyone among us came to have a fever or pain in the throat"/>
        <x-dialog-line arb="ووجع راس وقحّة أو حسّ بالغثيان أو تقيّؤ أو ضيق التنفّس"
                       eng="& a headache & coughing, or felt nausea or vomiting or shortness of breath"/>
        <x-dialog-line arb="لازم نوخذه على الطوارئ بسرعة وما يروح على الشغل أو المدرسة"
                       eng="we should take them to the Emergency Room quickly & they (should) not go to work or school"/>
        <x-dialog-line arb="حتّى يحمي حاله وما ينقل العدوى للآخرين"
                       eng="so as to protect themselves & not pass the infection onto others"/>
        <x-dialog-line speaker="إمّ سند" arb="مسؤوليّتنا إنّه نحمي حالنا ونساعد بعدم إنتشار الأمراض المعدية"
                       eng="our responsibility is that we protect ourselves & aid in the non-spread of infectious diseases"/>
        <x-dialog-line arb="ما تشوفو شرّ إنشالله وبتمنّى السلامة للجميع"
                       eng="may you see no evil & I hope for well-being for everyone"/>
    </div>
</x-activity-area>
