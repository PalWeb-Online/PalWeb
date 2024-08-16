<x-conj.chart>

@if ($form == 'XA')
    <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
    <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}</x-slot>
    <x-slot name="imp1Ptr">mni{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
    <x-slot name="imp2Ftr">bti{{$r1tr}}{{$r2tr}}i{{$r3tr}}i</x-slot>
    <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="imp2Ptr">bti{{$r1tr}}{{$r2tr}}i{{$r3tr}}u</x-slot>
    <x-slot name="imp3M">بي{{$r1}}{{$r2}}{{$r3}}</x-slot>
    <x-slot name="imp3Mtr">byi{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}</x-slot>
    <x-slot name="imp3Ftr">bti{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp3P">بي{{$r1}}{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="imp3Ptr">byi{{$r1tr}}{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif ($form == 'XB')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Mtr">byi{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">byi{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'XC')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}i{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'XD')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}ī{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}ī{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}ī{{$r3tr}}u</x-slot>

    @elseif ($form == '9A')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}ّ</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}ّ</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ّي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}ّو</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}{{$r3}}ّ</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}ّ</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}{{$r3}}ّو</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}u</x-slot>

@endif

@if ($form == 'XA')
    <x-slot name="past1S">أ{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
    <x-slot name="past1Str">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}t</x-slot>
    <x-slot name="past1P">أ{{$r1}}{{$r2}}{{$r3}}نا</x-slot>
    <x-slot name="past1Ptr">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}na</x-slot>
    <x-slot name="past2F">أ{{$r1}}{{$r2}}{{$r3}}تي</x-slot>
    <x-slot name="past2Ftr">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}ti</x-slot>
    <x-slot name="past2P">أ{{$r1}}{{$r2}}{{$r3}}تو</x-slot>
    <x-slot name="past2Ptr">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}tu</x-slot>
    <x-slot name="past3M">أ{{$r1}}{{$r2}}{{$r3}}</x-slot>
    <x-slot name="past3Mtr">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}</x-slot>
    <x-slot name="past3F">أ{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
    <x-slot name="past3Ftr">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}at</x-slot>
    <x-slot name="past3P">أ{{$r1}}{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="past3Ptr">ʔa{{$r1tr}}{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == 'XB')
        <x-slot name="past1S">أ{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past1Str">ʔa{{$r1tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">أ{{$r1}}{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">ʔa{{$r1tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2F">أ{{$r1}}{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">ʔa{{$r1tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">أ{{$r1}}{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">ʔa{{$r1tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">أ{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="past3Mtr">ʔa{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="past3F">أ{{$r1}}{{$r2}}ت</x-slot>
        <x-slot name="past3Ftr">ʔa{{$r1tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">أ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">ʔa{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'XC')
        <x-slot name="past1S">أ{{$r1}}{{$r2}}ّيت</x-slot>
        <x-slot name="past1Str">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">أ{{$r1}}{{$r2}}ّينا</x-slot>
        <x-slot name="past1Ptr">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2F">أ{{$r1}}{{$r2}}ّيتي</x-slot>
        <x-slot name="past2Ftr">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">أ{{$r1}}{{$r2}}ّيتو</x-slot>
        <x-slot name="past2Ptr">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">أ{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="past3Mtr">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="past3F">أ{{$r1}}{{$r2}}ّت</x-slot>
        <x-slot name="past3Ftr">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">أ{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="past3Ptr">ʔa{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'XD')
        <x-slot name="past1S">أ{{$r1}}{{$r3}}ت</x-slot>
        <x-slot name="past1Str">ʔa{{$r1tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past1P">أ{{$r1}}{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">ʔa{{$r1tr}}a{{$r3tr}}na</x-slot>
        <x-slot name="past2F">أ{{$r1}}{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">ʔa{{$r1tr}}a{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">أ{{$r1}}{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">ʔa{{$r1tr}}a{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">أ{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="past3Mtr">ʔa{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="past3F">أ{{$r1}}ا{{$r3}}ت</x-slot>
        <x-slot name="past3Ftr">ʔa{{$r1tr}}ā{{$r3tr}}at</x-slot>
        <x-slot name="past3P">أ{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">ʔa{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == '9A')
        <x-slot name="past1S">ا{{$r1}}{{$r2}}{{$r3}}ّيت</x-slot>
        <x-slot name="past1Str">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}ēt</x-slot>
        <x-slot name="past1P">ا{{$r1}}{{$r2}}{{$r3}}ّينا</x-slot>
        <x-slot name="past1Ptr">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}ēna</x-slot>
        <x-slot name="past2F">ا{{$r1}}{{$r2}}{{$r3}}ّيتي</x-slot>
        <x-slot name="past2Ftr">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}ēti</x-slot>
        <x-slot name="past2P">ا{{$r1}}{{$r2}}{{$r3}}ّيتو</x-slot>
        <x-slot name="past2Ptr">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}ētu</x-slot>
        <x-slot name="past3M">ا{{$r1}}{{$r2}}{{$r3}}ّ</x-slot>
        <x-slot name="past3Mtr">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}</x-slot>
        <x-slot name="past3F">ا{{$r1}}{{$r2}}{{$r3}}ّت</x-slot>
        <x-slot name="past3Ftr">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}at</x-slot>
        <x-slot name="past3P">ا{{$r1}}{{$r2}}{{$r3}}ّو</x-slot>
        <x-slot name="past3Ptr">i{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}u</x-slot>

@endif

@if ($form == 'XA')
    <x-slot name="amr2M">إ{{$r1}}{{$r2}}{{$r3}}</x-slot>
    <x-slot name="amr2Mtr">ʔi{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="amr2F">إ{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
    <x-slot name="amr2Ftr">ʔi{{$r1tr}}{{$r2tr}}i{{$r3tr}}i</x-slot>
    <x-slot name="amr2P">إ{{$r1}}{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="amr2Ptr">ʔi{{$r1tr}}{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif ($form == 'XB')
        <x-slot name="amr2M">إ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2F">إ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'XC')
        <x-slot name="amr2M">إ{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}i{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'XD')
        <x-slot name="amr2M">إ{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}ī{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}ī{{$r3tr}}u</x-slot>

    @elseif ($form == '9A')
        <x-slot name="amr2M">إ{{$r1}}{{$r2}}{{$r3}}ّ</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}{{$r2}}{{$r3}}ّي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}{{$r2}}{{$r3}}ّو</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}{{$r2tr}}a{{$r3tr}}{{$r3tr}}u</x-slot>

@endif

</x-conj.chart>
