@extends ('layouts.main')

@section('content')

    <style>
        body {
            background: none;
        }

        #main {
            background: none;
        }
    </style>

    <div class="box grammar screenshot">
        <x-sentence eng="you aren't Akram">
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="أكرم" eng="Akram"/>
        </x-sentence>
        <x-sentence eng="you aren't from Palestine">
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
            <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence>

        <x-vocabulary title="particles">
            <x-term arb="مش" eng="(nominal negation particle) is not"/>
        </x-vocabulary>
    </div>

    <div class="box vocab screenshot">
        <x-sentence eng="it's daytime in Palestine">
            <x-sentence-term arb="الدنيا" eng="(it)" :term="$terms['dinya'] ?? null"/>
            <x-sentence-term arb="نهار" eng="daytime" :term="$terms['nhār'] ?? null"/>
            <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
            <x-sentence-term arb="ـفلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence>
        <x-sentence eng="is it hot there?">
            <x-sentence-term arb="الدنيا" eng="(it)" :term="$terms['dinya'] ?? null"/>
            <x-sentence-term arb="شوب" eng="heat" :term="$terms['šōb'] ?? null"/>
            <x-sentence-term arb="هناك؟" eng="there" :term="$terms['hunāk'] ?? null"/>
        </x-sentence>

        <x-vocabulary title="nouns">
            <x-term arb="دنيا" eng="(dummy pronoun) it"/>
            <x-term arb="طقس" eng="weather"/>
            <x-term arb="نهار" eng="daytime"/>
            <x-term arb="ليل" eng="nighttime"/>
            <x-term arb="شوب" eng="heat"/>
            <x-term arb="برد" eng="cold, coldness"/>
            <x-term arb="شمس" eng="sun"/>
            <x-term arb="غيم" eng="clouds"/>
            <x-term arb="ثلج" eng="snow"/>
            <x-term arb="شتا" eng="rain"/>
            <x-term arb="شتا" eng="winter"/>
            <x-term arb="صيف" eng="summer"/>
        </x-vocabulary>
        <x-vocabulary title="adjectives">
            <x-term arb="صحّ" eng="correct, right, true"/>
            <x-term arb="غلط" eng="incorrect, wrong"/>
        </x-vocabulary>
        <x-vocabulary title="adverbs">
            <x-term arb="هون" eng="here"/>
            <x-term arb="هناك" eng="there"/>
        </x-vocabulary>
        <x-vocabulary title="conjunctions">
            <x-term arb="بسّ" eng="but"/>
            <x-term arb="ولّا" eng="(mutually exclusive) or"/>
        </x-vocabulary>
        <x-vocabulary title="questions">
            <x-term arb="كيف" eng="how"/>
        </x-vocabulary>
    </div>

    <div class="box dialog screenshot">
        <x-sentence eng="it's hot, right?">
            <x-sentence-term arb="شوب" eng="heat" :term="$terms['šōb'] ?? null"/>
            <x-sentence-term arb="صحّ؟" eng="right" :term="$terms['ṣaħħ'] ?? null"/>
        </x-sentence>
        <x-sentence eng="you're Akram, aren't you?">
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="أكرم" eng="Akram"/>
            <x-sentence-term arb="ولّا؟" eng="or" :term="$terms['willa'] ?? null"/>
        </x-sentence>
        <x-sentence eng="really? you're not Akram?">
            <x-sentence-term arb="عن جدّ؟" eng="really" :term="$terms['ʕan jadd'] ?? null"/>
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="أكرم؟" eng="Akram"/>
        </x-sentence>

        <x-vocabulary title="adjectives">
            <x-term arb="تمام" eng="good, well"/>
        </x-vocabulary>
        <x-vocabulary title="phrases">
            <x-term arb="كيف الحال" eng="how's it going?"/>
            <x-term arb="حمدلله" eng="thank God"/>
            <x-term arb="عن جدّ" eng="really, seriously"/>
            <x-term arb="والله" eng="really, seriously"/>
        </x-vocabulary>
    </div>

@endsection
