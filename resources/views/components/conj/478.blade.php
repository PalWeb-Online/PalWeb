<x-conj.chart>

@if ($form == '4A')
    <x-slot name="imp1S">بَن{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
    <x-slot name="imp1Str">ban{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp1P">منِن{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
    <x-slot name="imp1Ptr">mnin{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp2M">بتِن{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
    <x-slot name="imp2Mtr">btin{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp2F">بتِن{{$r1}}ِ{{$r2}}{{$r3}}ي</x-slot>
    <x-slot name="imp2Ftr">btin{{$r1tr}}i{{$r2tr}}{{$r3tr}}i</x-slot>
    <x-slot name="imp2P">بتِن{{$r1}}ِ{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="imp2Ptr">btin{{$r1tr}}i{{$r2tr}}{{$r3tr}}u</x-slot>
    <x-slot name="imp3M">بِن{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
    <x-slot name="imp3Mtr">bin{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp3F">بتِن{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
    <x-slot name="imp3Ftr">btin{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="imp3P">بِن{{$r1}}ِ{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="imp3Ptr">bin{{$r1tr}}i{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '4B')
        <x-slot name="imp1S">بَن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Str">ban{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp1P">منِن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Ptr">mnin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2M">بتِن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Mtr">btin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2F">بتِن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">btin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بتِن{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">btin{{$r1tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بِن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Mtr">bin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp3F">بتِن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Ftr">btin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp3P">بِن{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">bin{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '4C')
        <x-slot name="imp1S">بَن{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="imp1Str">ban{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp1P">منِن{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="imp1Ptr">mnin{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2M">بتِن{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="imp2Mtr">btin{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2F">بتِن{{$r1}}َ{{$r2}}ّي</x-slot>
        <x-slot name="imp2Ftr">btin{{$r1tr}}a{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بتِن{{$r1}}َ{{$r2}}ّو</x-slot>
        <x-slot name="imp2Ptr">btin{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بِن{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="imp3Mtr">bin{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3F">بتِن{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="imp3Ftr">btin{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3P">بِن{{$r1}}َ{{$r2}}ّو</x-slot>
        <x-slot name="imp3Ptr">bin{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '4D')
        <x-slot name="imp1S">بَن{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp1Str">ban{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منِن{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mnin{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp2M">بتِن{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp2Mtr">btin{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتِن{{$r1}}ا{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">btin{{$r1tr}}ā{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتِن{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">btin{{$r1tr}}ā{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بِن{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bin{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتِن{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">btin{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بِن{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bin{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == '8A')
        <x-slot name="imp1S">بَ{{$r1}}ت{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}t{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منِ{{$r1}}ت{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}t{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2M">بتِ{{$r1}}ت{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp2Mtr">bti{{$r1tr}}t{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتِ{{$r1}}تِ{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}ti{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتِ{{$r1}}تِ{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}ti{{$r2tr}}{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بِ{{$r1}}ت{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}t{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتِ{{$r1}}ت{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}t{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بِ{{$r1}}تِ{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}ti{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '8B')
        <x-slot name="imp1S">بَ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="imp1P">منِ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="imp2M">بتِ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="imp2Mtr">bti{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="imp2F">بتِ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بتِ{{$r1}}ت{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}t{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بِ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="imp3F">بتِ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="imp3P">بِ{{$r1}}ت{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}t{{$r2tr}}u</x-slot>

    @elseif ($form == '8C')
        <x-slot name="imp1S">بَ{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp1P">منِ{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2M">بتِ{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="imp2Mtr">bti{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2F">بتِ{{$r1}}تَ{{$r2}}ّي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}ta{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بتِ{{$r1}}تَ{{$r2}}ّو</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}ta{{$r2tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بِ{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3F">بتِ{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3P">بِ{{$r1}}تَ{{$r2}}ّو</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}ta{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '8D')
        <x-slot name="imp1S">بَ{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منِ{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="imp2M">بتِ{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="imp2Mtr">bti{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتِ{{$r1}}تا{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}tā{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتِ{{$r1}}تا{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}tā{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بِ{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتِ{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بِ{{$r1}}تا{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}tā{{$r3tr}}u</x-slot>

    @elseif ($form == '8U')
        <x-slot name="imp1S">بَتّ{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp1Str">batt{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منِتّ{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mnitt{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2M">بتِتّ{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp2Mtr">btitt{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتِتِّ{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">btitti{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتِتِّ{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">btitti{{$r2tr}}{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بِتّ{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bitt{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتِتّ{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">btitt{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بِتِّ{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bitti{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '7A')
        <x-slot name="imp1S">بَستَ{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp1Str">basta{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منِستَ{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mnista{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2M">بتِستَ{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp2Mtr">btista{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتِستَ{{$r1}}{{$r2}}ِ{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">btista{{$r1tr}}{{$r2tr}}i{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتِستَ{{$r1}}{{$r2}}ِ{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">btista{{$r1tr}}{{$r2tr}}i{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بِستَ{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bista{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتِستَ{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">btista{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بِستَ{{$r1}}{{$r2}}ِ{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bista{{$r1tr}}{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif ($form == '7B')
        <x-slot name="imp1S">بَستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Str">basta{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp1P">منِستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Ptr">mnista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2M">بتِستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Mtr">btista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2F">بتِستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">btista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بتِستَ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">btista{{$r1tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بِستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Mtr">bista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp3F">بتِستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Ftr">btista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp3P">بِستَ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">bista{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '7C')
        <x-slot name="imp1S">بَستَ{{$r1}}ِ{{$r2}}ّ</x-slot>
        <x-slot name="imp1Str">basta{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp1P">منِستَ{{$r1}}ِ{{$r2}}ّ</x-slot>
        <x-slot name="imp1Ptr">mnista{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2M">بتِستَ{{$r1}}ِ{{$r2}}ّ</x-slot>
        <x-slot name="imp2Mtr">btista{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2F">بتِستَ{{$r1}}ِ{{$r2}}ّي</x-slot>
        <x-slot name="imp2Ftr">btista{{$r1tr}}i{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بتِستَ{{$r1}}ِ{{$r2}}ّو</x-slot>
        <x-slot name="imp2Ptr">btista{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بِستَ{{$r1}}ِ{{$r2}}ّ</x-slot>
        <x-slot name="imp3Mtr">bista{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3F">بتِستَ{{$r1}}ِ{{$r2}}ّ</x-slot>
        <x-slot name="imp3Ftr">btista{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3P">بِستَ{{$r1}}ِ{{$r2}}ّو</x-slot>
        <x-slot name="imp3Ptr">bista{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '7D')
        <x-slot name="imp1S">بَستَ{{$r1}}ي{{$r3}}</x-slot>
        <x-slot name="imp1Str">basta{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منِستَ{{$r1}}ي{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mnista{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp2M">بتِستَ{{$r1}}ي{{$r3}}</x-slot>
        <x-slot name="imp2Mtr">btista{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتِستَ{{$r1}}ي{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">btista{{$r1tr}}ī{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتِستَ{{$r1}}ي{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">btista{{$r1tr}}ī{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بِستَ{{$r1}}ي{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bista{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتِستَ{{$r1}}ي{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">btista{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بِستَ{{$r1}}ي{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bista{{$r1tr}}ī{{$r3tr}}u</x-slot>

@endif

@if ($form == '4A')
    <x-slot name="past1S">ان{{$r1}}َ{{$r2}}َ{{$r3}}ت</x-slot>
    <x-slot name="past1Str">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}t</x-slot>
    <x-slot name="past1P">ان{{$r1}}َ{{$r2}}َ{{$r3}}نا</x-slot>
    <x-slot name="past1Ptr">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}na</x-slot>
    <x-slot name="past2M">ان{{$r1}}َ{{$r2}}َ{{$r3}}ت</x-slot>
    <x-slot name="past2Mtr">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}t</x-slot>
    <x-slot name="past2F">ان{{$r1}}َ{{$r2}}َ{{$r3}}تي</x-slot>
    <x-slot name="past2Ftr">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}ti</x-slot>
    <x-slot name="past2P">ان{{$r1}}َ{{$r2}}َ{{$r3}}تو</x-slot>
    <x-slot name="past2Ptr">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}tu</x-slot>
    <x-slot name="past3M">ان{{$r1}}َ{{$r2}}َ{{$r3}}</x-slot>
    <x-slot name="past3Mtr">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}</x-slot>
    <x-slot name="past3F">ان{{$r1}}َ{{$r2}}{{$r3}}َت</x-slot>
    <x-slot name="past3Ftr">n{{$r1tr}}a{{$r2tr}}{{$r3tr}}at</x-slot>
    <x-slot name="past3P">ان{{$r1}}َ{{$r2}}َ{{$r3}}و</x-slot>
    <x-slot name="past3Ptr">n{{$r1tr}}a{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == '4B')
        <x-slot name="past1S">ان{{$r1}}َ{{$r2}}يت</x-slot>
        <x-slot name="past1Str">n{{$r1tr}}a{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">ان{{$r1}}َ{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">n{{$r1tr}}a{{$r2tr}}ēna</x-slot>
        <x-slot name="past2M">ان{{$r1}}َ{{$r2}}يت</x-slot>
        <x-slot name="past2Mtr">n{{$r1tr}}a{{$r2tr}}ēt</x-slot>
        <x-slot name="past2F">ان{{$r1}}َ{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">n{{$r1tr}}a{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">ان{{$r1}}َ{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">n{{$r1tr}}a{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">ان{{$r1}}َ{{$r2}}ى</x-slot>
        <x-slot name="past3Mtr">n{{$r1tr}}a{{$r2tr}}a</x-slot>
        <x-slot name="past3F">ان{{$r1}}َ{{$r2}}َت</x-slot>
        <x-slot name="past3Ftr">n{{$r1tr}}a{{$r2tr}}at</x-slot>
        <x-slot name="past3P">ان{{$r1}}َ{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">n{{$r1tr}}a{{$r2tr}}u</x-slot>

    @elseif ($form == '4C')
        <x-slot name="past1S">ان{{$r1}}َ{{$r2}}ّيت</x-slot>
        <x-slot name="past1Str">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">ان{{$r1}}َ{{$r2}}ّينا</x-slot>
        <x-slot name="past1Ptr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2M">ان{{$r1}}َ{{$r2}}ّيت</x-slot>
        <x-slot name="past2Mtr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past2F">ان{{$r1}}َ{{$r2}}ّيتي</x-slot>
        <x-slot name="past2Ftr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">ان{{$r1}}َ{{$r2}}ّيتو</x-slot>
        <x-slot name="past2Ptr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">ان{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="past3Mtr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="past3F">ان{{$r1}}َ{{$r2}}َّت</x-slot>
        <x-slot name="past3Ftr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">ان{{$r1}}َ{{$r2}}ّو</x-slot>
        <x-slot name="past3Ptr">n{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '4D')
        <x-slot name="past1S">ان{{$r1}}ا{{$r3}}ت</x-slot>
        <x-slot name="past1Str">n{{$r1tr}}ā{{$r3tr}}t</x-slot>
        <x-slot name="past1P">ان{{$r1}}ا{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">n{{$r1tr}}ā{{$r3tr}}na</x-slot>
        <x-slot name="past2M">ان{{$r1}}ا{{$r3}}ت</x-slot>
        <x-slot name="past2Mtr">n{{$r1tr}}ā{{$r3tr}}t</x-slot>
        <x-slot name="past2F">ان{{$r1}}ا{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">n{{$r1tr}}ā{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">ان{{$r1}}ا{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">n{{$r1tr}}ā{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">ان{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="past3Mtr">n{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="past3F">ان{{$r1}}ا{{$r3}}َت</x-slot>
        <x-slot name="past3Ftr">n{{$r1tr}}ā{{$r3tr}}at</x-slot>
        <x-slot name="past3P">ان{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">n{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == '8A')
        <x-slot name="past1S">ا{{$r1}}تَ{{$r2}}َ{{$r3}}ت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past1P">ا{{$r1}}تَ{{$r2}}َ{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}na</x-slot>
        <x-slot name="past2M">ا{{$r1}}تَ{{$r2}}َ{{$r3}}ت</x-slot>
        <x-slot name="past2Mtr">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past2F">ا{{$r1}}تَ{{$r2}}َ{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">ا{{$r1}}تَ{{$r2}}َ{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">ا{{$r1}}تَ{{$r2}}َ{{$r3}}</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="past3F">ا{{$r1}}تَ{{$r2}}{{$r3}}َت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}ta{{$r2tr}}{{$r3tr}}at</x-slot>
        <x-slot name="past3P">ا{{$r1}}تَ{{$r2}}َ{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}ta{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == '8B')
        <x-slot name="past1S">ا{{$r1}}تَ{{$r2}}يت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}ta{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">ا{{$r1}}تَ{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}ta{{$r2tr}}ēna</x-slot>
        <x-slot name="past2M">ا{{$r1}}تَ{{$r2}}يت</x-slot>
        <x-slot name="past2Mtr">{{$r1tr}}ta{{$r2tr}}ēt</x-slot>
        <x-slot name="past2F">ا{{$r1}}تَ{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}ta{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">ا{{$r1}}تَ{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}ta{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">ا{{$r1}}تَ{{$r2}}ى</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}ta{{$r2tr}}a</x-slot>
        <x-slot name="past3F">ا{{$r1}}تَ{{$r2}}َت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}ta{{$r2tr}}at</x-slot>
        <x-slot name="past3P">ا{{$r1}}تَ{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}ta{{$r2tr}}u</x-slot>

    @elseif ($form == '8C')
        <x-slot name="past1S">ا{{$r1}}تَ{{$r2}}ّيت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">ا{{$r1}}تَ{{$r2}}ّينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2M">ا{{$r1}}تَ{{$r2}}ّيت</x-slot>
        <x-slot name="past2Mtr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past2F">ا{{$r1}}تَ{{$r2}}ّيتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">ا{{$r1}}تَ{{$r2}}ّيتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">ا{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="past3F">ا{{$r1}}تَ{{$r2}}َّت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">ا{{$r1}}تَ{{$r2}}ّو</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}ta{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '8D')
        <x-slot name="past1S">ا{{$r1}}تَ{{$r3}}ت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}ta{{$r3tr}}t</x-slot>
        <x-slot name="past1P">ا{{$r1}}تَ{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}ta{{$r3tr}}na</x-slot>
        <x-slot name="past2M">ا{{$r1}}تَ{{$r3}}ت</x-slot>
        <x-slot name="past2Mtr">{{$r1tr}}ta{{$r3tr}}t</x-slot>
        <x-slot name="past2F">ا{{$r1}}تَ{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}ta{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">ا{{$r1}}تَ{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}ta{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">ا{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="past3F">ا{{$r1}}تا{{$r3}}َت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}tā{{$r3tr}}at</x-slot>
        <x-slot name="past3P">ا{{$r1}}تا{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}tā{{$r3tr}}u</x-slot>

    @elseif ($form == '8U')
        <x-slot name="past1S">اتَّ{{$r2}}َ{{$r3}}ت</x-slot>
        <x-slot name="past1Str">tta{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past1P">اتَّ{{$r2}}َ{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">tta{{$r2tr}}a{{$r3tr}}na</x-slot>
        <x-slot name="past2M">اتَّ{{$r2}}َ{{$r3}}ت</x-slot>
        <x-slot name="past2Mtr">tta{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past2F">اتَّ{{$r2}}َ{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">tta{{$r2tr}}a{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">اتَّ{{$r2}}َ{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">tta{{$r2tr}}a{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">اتَّ{{$r2}}َ{{$r3}}</x-slot>
        <x-slot name="past3Mtr">tta{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="past3F">اتَّ{{$r2}}{{$r3}}َت</x-slot>
        <x-slot name="past3Ftr">tta{{$r2tr}}{{$r3tr}}at</x-slot>
        <x-slot name="past3P">اتَّ{{$r2}}َ{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">tta{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == '7A')
        <x-slot name="past1S">استَ{{$r1}}{{$r2}}َ{{$r3}}ت</x-slot>
        <x-slot name="past1Str">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past1P">استَ{{$r1}}{{$r2}}َ{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}na</x-slot>
        <x-slot name="past2M">استَ{{$r1}}{{$r2}}َ{{$r3}}ت</x-slot>
        <x-slot name="past2Mtr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past2F">استَ{{$r1}}{{$r2}}َ{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">استَ{{$r1}}{{$r2}}َ{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">استَ{{$r1}}{{$r2}}َ{{$r3}}</x-slot>
        <x-slot name="past3Mtr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="past3F">استَ{{$r1}}{{$r2}}َ{{$r3}}َت</x-slot>
        <x-slot name="past3Ftr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}at</x-slot>
        <x-slot name="past3P">استَ{{$r1}}{{$r2}}َ{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">sta{{$r1tr}}{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == '7B')
        <x-slot name="past1S">استَ{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past1Str">sta{{$r1tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">استَ{{$r1}}{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">sta{{$r1tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2M">استَ{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past2Mtr">sta{{$r1tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past2F">استَ{{$r1}}{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">sta{{$r1tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">استَ{{$r1}}{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">sta{{$r1tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">استَ{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="past3Mtr">sta{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="past3F">استَ{{$r1}}{{$r2}}َت</x-slot>
        <x-slot name="past3Ftr">sta{{$r1tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">استَ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">sta{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '7C')
        <x-slot name="past1S">استَ{{$r1}}َ{{$r2}}ّيت</x-slot>
        <x-slot name="past1Str">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">استَ{{$r1}}َ{{$r2}}ّينا</x-slot>
        <x-slot name="past1Ptr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2M">استَ{{$r1}}َ{{$r2}}ّيت</x-slot>
        <x-slot name="past2Mtr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past2F">استَ{{$r1}}َ{{$r2}}ّيتي</x-slot>
        <x-slot name="past2Ftr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">استَ{{$r1}}َ{{$r2}}ّيتو</x-slot>
        <x-slot name="past2Ptr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">استَ{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="past3Mtr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="past3F">استَ{{$r1}}َ{{$r2}}َّت</x-slot>
        <x-slot name="past3Ftr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">استَ{{$r1}}َ{{$r2}}ّو</x-slot>
        <x-slot name="past3Ptr">sta{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '7D')
        <x-slot name="past1S">استَ{{$r1}}َ{{$r3}}ت</x-slot>
        <x-slot name="past1Str">sta{{$r1tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past1P">استَ{{$r1}}َ{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">sta{{$r1tr}}a{{$r3tr}}na</x-slot>
        <x-slot name="past2M">استَ{{$r1}}َ{{$r3}}ت</x-slot>
        <x-slot name="past2Mtr">sta{{$r1tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past2F">استَ{{$r1}}َ{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">sta{{$r1tr}}a{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">استَ{{$r1}}َ{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">sta{{$r1tr}}a{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">استَ{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="past3Mtr">sta{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="past3F">استَ{{$r1}}ا{{$r3}}َت</x-slot>
        <x-slot name="past3Ftr">sta{{$r1tr}}ā{{$r3tr}}at</x-slot>
        <x-slot name="past3P">استَ{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">sta{{$r1tr}}ā{{$r3tr}}u</x-slot>

@endif

@if ($form == '4A')
    <x-slot name="amr2M">إن{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
    <x-slot name="amr2Mtr">ʔin{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
    <x-slot name="amr2F">إن{{$r1}}ِ{{$r2}}{{$r3}}ي</x-slot>
    <x-slot name="amr2Ftr">ʔin{{$r1tr}}i{{$r2tr}}{{$r3tr}}i</x-slot>
    <x-slot name="amr2P">إن{{$r1}}ِ{{$r2}}{{$r3}}و</x-slot>
    <x-slot name="amr2Ptr">ʔin{{$r1tr}}i{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '4B')
        <x-slot name="amr2M">إن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Mtr">ʔin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2F">إن{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔin{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إن{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔin{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '4C')
        <x-slot name="amr2M">إن{{$r1}}َ{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">ʔin{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">إن{{$r1}}َ{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">ʔin{{$r1tr}}a{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إن{{$r1}}َ{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">ʔin{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '4D')
        <x-slot name="amr2M">إن{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔin{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إن{{$r1}}ا{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔin{{$r1tr}}ā{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إن{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔin{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == '8A')
        <x-slot name="amr2M">إ{{$r1}}ت{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}t{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}تِ{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}ti{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}تِ{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}ti{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '8B')
        <x-slot name="amr2M">إ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="amr2F">إ{{$r1}}ت{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}t{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}ت{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}t{{$r2tr}}u</x-slot>

    @elseif ($form == '8C')
        <x-slot name="amr2M">إ{{$r1}}تَ{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}ta{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}تَ{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}ta{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}تَ{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}ta{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '8D')
        <x-slot name="amr2M">إ{{$r1}}تا{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}tā{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}تا{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}tā{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}تا{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}tā{{$r3tr}}u</x-slot>

    @elseif ($form == '8U')
        <x-slot name="amr2M">إتّ{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔitt{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إتِّ{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔitti{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إتِّ{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔitti{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif ($form == '7A')
        <x-slot name="amr2M">إستَ{{$r1}}{{$r2}}ِ{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔista{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إستَ{{$r1}}{{$r2}}ِ{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔista{{$r1tr}}{{$r2tr}}i{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إستَ{{$r1}}{{$r2}}ِ{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔista{{$r1tr}}{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif ($form == '7B')
        <x-slot name="amr2M">إستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Mtr">ʔista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2F">إستَ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔista{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إستَ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔista{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '7C')
        <x-slot name="amr2M">إستَ{{$r1}}ِ{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">ʔista{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">إستَ{{$r1}}ِ{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">ʔista{{$r1tr}}i{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إستَ{{$r1}}ِ{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">ʔista{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == '7D')
        <x-slot name="amr2M">إستَ{{$r1}}ي{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔista{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إستَ{{$r1}}ي{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔista{{$r1tr}}ī{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إستَ{{$r1}}ي{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔista{{$r1tr}}ī{{$r3tr}}u</x-slot>

@endif

</x-conj.chart>
