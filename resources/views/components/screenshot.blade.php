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
        <x-sentence-item eng="you aren't Akram">
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="أكرم" eng="Akram"/>
        </x-sentence-item>
        <x-sentence-item eng="you aren't from Palestine">
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="من" eng="from" :term="$terms['min'] ?? null"/>
            <x-sentence-term arb="فلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence-item>

        <x-vocabulary title="particles">
            <x-term-item arb="مش" eng="(nominal negation particle) is not"/>
        </x-vocabulary>
    </div>

    <div class="box vocab screenshot">
        <x-sentence-item eng="it's daytime in Palestine">
            <x-sentence-term arb="الدنيا" eng="(it)" :term="$terms['dinya'] ?? null"/>
            <x-sentence-term arb="نهار" eng="daytime" :term="$terms['nhār'] ?? null"/>
            <x-sentence-term arb="بـ" eng="in" :term="$terms['b-'] ?? null"/>
            <x-sentence-term arb="ـفلسطين" eng="Palestine" :term="$terms['falasṭīn'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="is it hot there?">
            <x-sentence-term arb="الدنيا" eng="(it)" :term="$terms['dinya'] ?? null"/>
            <x-sentence-term arb="شوب" eng="heat" :term="$terms['šōb'] ?? null"/>
            <x-sentence-term arb="هناك؟" eng="there" :term="$terms['hunāk'] ?? null"/>
        </x-sentence-item>

        <x-vocabulary title="nouns">
            <x-term-item arb="دنيا" eng="(dummy pronoun) it"/>
            <x-term-item arb="طقس" eng="weather"/>
            <x-term-item arb="نهار" eng="daytime"/>
            <x-term-item arb="ليل" eng="nighttime"/>
            <x-term-item arb="شوب" eng="heat"/>
            <x-term-item arb="برد" eng="cold, coldness"/>
            <x-term-item arb="شمس" eng="sun"/>
            <x-term-item arb="غيم" eng="clouds"/>
            <x-term-item arb="ثلج" eng="snow"/>
            <x-term-item arb="شتا" eng="rain"/>
            <x-term-item arb="شتا" eng="winter"/>
            <x-term-item arb="صيف" eng="summer"/>
        </x-vocabulary>
        <x-vocabulary title="adjectives">
            <x-term-item arb="صحّ" eng="correct, right, true"/>
            <x-term-item arb="غلط" eng="incorrect, wrong"/>
        </x-vocabulary>
        <x-vocabulary title="adverbs">
            <x-term-item arb="هون" eng="here"/>
            <x-term-item arb="هناك" eng="there"/>
        </x-vocabulary>
        <x-vocabulary title="conjunctions">
            <x-term-item arb="بسّ" eng="but"/>
            <x-term-item arb="ولّا" eng="(mutually exclusive) or"/>
        </x-vocabulary>
        <x-vocabulary title="questions">
            <x-term-item arb="كيف" eng="how"/>
        </x-vocabulary>
    </div>

    <div class="box dialog screenshot">
        <x-sentence-item eng="it's hot, right?">
            <x-sentence-term arb="شوب" eng="heat" :term="$terms['šōb'] ?? null"/>
            <x-sentence-term arb="صحّ؟" eng="right" :term="$terms['ṣaħħ'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="you're Akram, aren't you?">
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="أكرم" eng="Akram"/>
            <x-sentence-term arb="ولّا؟" eng="or" :term="$terms['willa'] ?? null"/>
        </x-sentence-item>
        <x-sentence-item eng="really? you're not Akram?">
            <x-sentence-term arb="عن جدّ؟" eng="really" :term="$terms['ʕan jadd'] ?? null"/>
            <x-sentence-term arb="إنتا" eng="you.M" :term="$terms['ʔinta'] ?? null"/>
            <x-sentence-term arb="مش" eng="not" :term="$terms['miš'] ?? null"/>
            <x-sentence-term arb="أكرم؟" eng="Akram"/>
        </x-sentence-item>

        <x-vocabulary title="adjectives">
            <x-term-item arb="تمام" eng="good, well"/>
        </x-vocabulary>
        <x-vocabulary title="phrases">
            <x-term-item arb="كيف الحال" eng="how's it going?"/>
            <x-term-item arb="حمدلله" eng="thank God"/>
            <x-term-item arb="عن جدّ" eng="really, seriously"/>
            <x-term-item arb="والله" eng="really, seriously"/>
        </x-vocabulary>
    </div>

@endsection
