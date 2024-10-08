<x-chart.conjugation>

@if ($form == '2QA')
    <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="imp1Str">ba{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}</x-slot>
    <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="imp1Ptr">min{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}</x-slot>
    <x-slot name="imp2M">بت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="imp2Mtr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}</x-slot>
    <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}{{$r4}}ي</x-slot>
    <x-slot name="imp2Ftr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}i</x-slot>
    <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
    <x-slot name="imp2Ptr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}u</x-slot>
    <x-slot name="imp3M">ب{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="imp3Mtr">bi{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}</x-slot>
    <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="imp3Ftr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}</x-slot>
    <x-slot name="imp3P">ب{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
    <x-slot name="imp3Ptr">bi{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}u</x-slot>

    @elseif ($form == '2QB')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2M">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Mtr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}a{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '5QA')
        <x-slot name="imp1S">بت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="imp1Str">bat{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="imp1P">منت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="imp1Ptr">mnit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="imp2M">بتت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="imp2Mtr">btit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="imp2F">بتت{{$r1}}{{$r2}}{{$r3}}{{$r4}}ي</x-slot>
        <x-slot name="imp2Ftr">btit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}i</x-slot>
        <x-slot name="imp2P">بتت{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
        <x-slot name="imp2Ptr">btit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}u</x-slot>
        <x-slot name="imp3M">بت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="imp3Mtr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="imp3F">بتت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="imp3Ftr">btit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="imp3P">بتت{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
        <x-slot name="imp3Ptr">bit{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}u</x-slot>

@endif

@if ($form == '2QA')
    <x-slot name="past1S">{{$r1}}{{$r2}}{{$r3}}{{$r4}}ت</x-slot>
    <x-slot name="past1Str">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}t</x-slot>
    <x-slot name="past1P">{{$r1}}{{$r2}}{{$r3}}{{$r4}}نا</x-slot>
    <x-slot name="past1Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}na</x-slot>
    <x-slot name="past2M">{{$r1}}{{$r2}}{{$r3}}{{$r4}}ت</x-slot>
    <x-slot name="past2Mtr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}t</x-slot>
    <x-slot name="past2F">{{$r1}}{{$r2}}{{$r3}}{{$r4}}تي</x-slot>
    <x-slot name="past2Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}ti</x-slot>
    <x-slot name="past2P">{{$r1}}{{$r2}}{{$r3}}{{$r4}}تو</x-slot>
    <x-slot name="past2Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}tu</x-slot>
    <x-slot name="past3M">{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="past3Mtr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
    <x-slot name="past3F">{{$r1}}{{$r2}}{{$r3}}{{$r4}}ت</x-slot>
    <x-slot name="past3Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}at</x-slot>
    <x-slot name="past3P">{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
    <x-slot name="past3Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}u</x-slot>

    @elseif ($form == '2QB')
        <x-slot name="past1S">{{$r1}}{{$r2}}{{$r3}}يت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}a{{$r2tr}}{{$r3tr}}ēt</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}{{$r3}}ينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}ēna</x-slot>
        <x-slot name="past2M">{{$r1}}{{$r2}}{{$r3}}يت</x-slot>
        <x-slot name="past2Mtr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}ēt</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}{{$r3}}يتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}ēti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}{{$r3}}يتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}ētu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}{{$r3}}ى</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}a</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '5QA')
        <x-slot name="past1S">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}ت</x-slot>
        <x-slot name="past1Str">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}t</x-slot>
        <x-slot name="past1P">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}نا</x-slot>
        <x-slot name="past1Ptr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}na</x-slot>
        <x-slot name="past2M">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}ت</x-slot>
        <x-slot name="past2Mtr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}t</x-slot>
        <x-slot name="past2F">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}تي</x-slot>
        <x-slot name="past2Ftr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}ti</x-slot>
        <x-slot name="past2P">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}تو</x-slot>
        <x-slot name="past2Ptr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}tu</x-slot>
        <x-slot name="past3M">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="past3Mtr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="past3F">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}ت</x-slot>
        <x-slot name="past3Ftr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}at</x-slot>
        <x-slot name="past3P">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
        <x-slot name="past3Ptr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}u</x-slot>

@endif

@if ($form == '2QA')
    <x-slot name="amr2M">{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
    <x-slot name="amr2Mtr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}</x-slot>
    <x-slot name="amr2F">{{$r1}}{{$r2}}{{$r3}}{{$r4}}ي</x-slot>
    <x-slot name="amr2Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}i</x-slot>
    <x-slot name="amr2P">{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
    <x-slot name="amr2Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}i{{$r4tr}}u</x-slot>

    @elseif ($form == '2QB')
        <x-slot name="amr2M">{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="amr2F">{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '5QA')
        <x-slot name="amr2M">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}</x-slot>
        <x-slot name="amr2Mtr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}</x-slot>
        <x-slot name="amr2F">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}ي</x-slot>
        <x-slot name="amr2Ftr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}i</x-slot>
        <x-slot name="amr2P">ت{{$r1}}{{$r2}}{{$r3}}{{$r4}}و</x-slot>
        <x-slot name="amr2Ptr">t{{$r1tr}}a{{$r2tr}}{{$r3tr}}a{{$r4tr}}u</x-slot>

@endif

</x-chart.conjugation>
