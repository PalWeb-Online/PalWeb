@php
    $vowel = 'i' . $r1tr;
    $r1 === 'و' && $vowel = 'ū';
    $r1 === 'ي' && $vowel = 'ī';
@endphp

<x-chart.conjugation>
    @if (in_array($form, ['A1', 'A2i']))
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mn{{ $vowel }}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bt{{ $vowel }}{{$r2tr}}i{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bt{{ $vowel }}{{$r2tr}}i{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">by{{ $vowel }}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bt{{ $vowel }}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">by{{ $vowel }}{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['A1a', 'A2']))
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mn{{ $vowel }}{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bt{{ $vowel }}{{$r2tr}}a{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bt{{ $vowel }}{{$r2tr}}a{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">by{{ $vowel }}{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bt{{ $vowel }}{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">by{{ $vowel }}{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == 'A1u')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mnu{{$r1tr}}{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">btu{{$r1tr}}{{$r2tr}}u{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">btu{{$r1tr}}{{$r2tr}}u{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">byu{{$r1tr}}{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">btu{{$r1tr}}{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">byu{{$r1tr}}{{$r2tr}}u{{$r3tr}}u</x-slot>

    @elseif ($form == 'A1h')
        <x-slot name="imp1S">با{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Str">bā{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp1P">منا{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">mnā{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بتا{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">btā{{$r2tr}}{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بتا{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">btā{{$r2tr}}{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">بيا{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">byā{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بتا{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">btā{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="imp3P">بيا{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">byā{{$r2tr}}{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['B1', 'B2i']))
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp1Ptr">mn{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">bt{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">bt{{ $vowel }}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Mtr">by{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp3Ftr">bt{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">by{{ $vowel }}{{$r2tr}}u</x-slot>

    @elseif ($form == 'B1a')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ا</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ا</x-slot>
        <x-slot name="imp1Ptr">mni{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">bti{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">bti{{$r1tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}ا</x-slot>
        <x-slot name="imp3Mtr">byi{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ا</x-slot>
        <x-slot name="imp3Ftr">bti{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">byi{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'B2')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="imp1Ptr">mn{{ $vowel }}{{$r2tr}}a</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="imp2Ftr">bt{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp2Ptr">bt{{ $vowel }}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">بي{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="imp3Mtr">by{{ $vowel }}{{$r2tr}}a</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="imp3Ftr">bt{{ $vowel }}{{$r2tr}}a</x-slot>
        <x-slot name="imp3P">بي{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="imp3Ptr">by{{ $vowel }}{{$r2tr}}u</x-slot>

    @elseif ($form == 'Ci')
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

    @elseif ($form == 'Ca')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}a{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'Cu')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}u{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}u{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}u{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}u{{$r2tr}}{{$r2tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}u{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}u{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}u{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'DY')
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

    @elseif (in_array($form, ['DAi', 'DAu']))
        <x-slot name="imp1S">ب{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}ا{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}ā{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}ā{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == 'DW')
        <x-slot name="imp1S">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Str">ba{{$r1tr}}ū{{$r3tr}}</x-slot>
        <x-slot name="imp1P">من{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp1Ptr">min{{$r1tr}}ū{{$r3tr}}</x-slot>
        <x-slot name="imp2F">بت{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="imp2Ftr">bit{{$r1tr}}ū{{$r3tr}}i</x-slot>
        <x-slot name="imp2P">بت{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp2Ptr">bit{{$r1tr}}ū{{$r3tr}}u</x-slot>
        <x-slot name="imp3M">ب{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Mtr">bi{{$r1tr}}ū{{$r3tr}}</x-slot>
        <x-slot name="imp3F">بت{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="imp3Ftr">bit{{$r1tr}}ū{{$r3tr}}</x-slot>
        <x-slot name="imp3P">ب{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="imp3Ptr">bi{{$r1tr}}ū{{$r3tr}}u</x-slot>

    @elseif ($form == 'ʔaža')
        <x-slot name="imp1S">باجي</x-slot>
        <x-slot name="imp1Str">bāži</x-slot>
        <x-slot name="imp1P">منيجي</x-slot>
        <x-slot name="imp1Ptr">mnīži</x-slot>
        <x-slot name="imp2F">بتيجي</x-slot>
        <x-slot name="imp2Ftr">btīži</x-slot>
        <x-slot name="imp2P">بتيجو</x-slot>
        <x-slot name="imp2Ptr">btīžu</x-slot>
        <x-slot name="imp3M">بيجي</x-slot>
        <x-slot name="imp3Mtr">bīži</x-slot>
        <x-slot name="imp3F">بتيجي</x-slot>
        <x-slot name="imp3Ftr">btīži</x-slot>
        <x-slot name="imp3P">بيجو</x-slot>
        <x-slot name="imp3Ptr">bīžu</x-slot>

    @endif

    @if (in_array($form, ['A1', 'A1a', 'A1u', 'A1h']))
        <x-slot name="past1S">{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}a{{$r2tr}}a{{$r3tr}}t</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}a{{$r2tr}}a{{$r3tr}}na</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}a{{$r2tr}}a{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}a{{$r2tr}}a{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}a{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}a{{$r2tr}}{{$r3tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}a{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['A2', 'A2i']))
        <x-slot name="past1S">{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}{{$r2tr}}i{{$r3tr}}t</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}{{$r2tr}}i{{$r3tr}}na</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}{{$r2tr}}i{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}{{$r2tr}}i{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}i{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}{{$r3}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}i{{$r2tr}}{{$r3tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}i{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif ($form == 'B1')
        <x-slot name="past1S">{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}a{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}a{{$r2tr}}ēna</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}a{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}a{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}a{{$r2tr}}a</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}a{{$r2tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}a{{$r2tr}}u</x-slot>

    @elseif ($form == 'B1a')
        <x-slot name="past1S">{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}a{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}a{{$r2tr}}ēna</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}a{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}a{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}ا</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}a{{$r2tr}}a</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}a{{$r2tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}a{{$r2tr}}u</x-slot>

    @elseif (in_array($form, ['B2', 'B2i']))
        <x-slot name="past1S">{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}{{$r2tr}}īt</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}ينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}{{$r2tr}}īna</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}يتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}{{$r2tr}}īti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}يتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}{{$r2tr}}ītu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}i{{$r2tr}}i</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}يت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}i{{$r2tr}}yat</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}i{{$r2tr}}u</x-slot>

    @elseif (in_array($form, ['Cu', 'Ci', 'Ca']))
        <x-slot name="past1S">{{$r1}}{{$r2}}ّيت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēt</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r2}}ّينا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēna</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r2}}ّيتي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}ēti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r2}}ّيتو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}ētu</x-slot>
        <x-slot name="past3M">{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="past3F">{{$r1}}{{$r2}}ّت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif (in_array($form, ['DY', 'DAi']))
        <x-slot name="past1S">{{$r1}}{{$r3}}ت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}i{{$r3tr}}t</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}i{{$r3tr}}na</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}i{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}i{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="past3F">{{$r1}}ا{{$r3}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}ā{{$r3tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['DW', 'DAu']))
        <x-slot name="past1S">{{$r1}}{{$r3}}ت</x-slot>
        <x-slot name="past1Str">{{$r1tr}}u{{$r3tr}}t</x-slot>
        <x-slot name="past1P">{{$r1}}{{$r3}}نا</x-slot>
        <x-slot name="past1Ptr">{{$r1tr}}u{{$r3tr}}na</x-slot>
        <x-slot name="past2F">{{$r1}}{{$r3}}تي</x-slot>
        <x-slot name="past2Ftr">{{$r1tr}}u{{$r3tr}}ti</x-slot>
        <x-slot name="past2P">{{$r1}}{{$r3}}تو</x-slot>
        <x-slot name="past2Ptr">{{$r1tr}}u{{$r3tr}}tu</x-slot>
        <x-slot name="past3M">{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="past3Mtr">{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="past3F">{{$r1}}ا{{$r3}}ت</x-slot>
        <x-slot name="past3Ftr">{{$r1tr}}ā{{$r3tr}}at</x-slot>
        <x-slot name="past3P">{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="past3Ptr">{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == 'ʔaža')
        <x-slot name="past1S">أجيت</x-slot>
        <x-slot name="past1Str">ʔažīt</x-slot>
        <x-slot name="past1P">أجينا</x-slot>
        <x-slot name="past1Ptr">ʔažīna</x-slot>
        <x-slot name="past2F">أجيتي</x-slot>
        <x-slot name="past2Ftr">ʔažīti</x-slot>
        <x-slot name="past2P">أجيتو</x-slot>
        <x-slot name="past2Ptr">ʔažītu</x-slot>
        <x-slot name="past3M">أجى</x-slot>
        <x-slot name="past3Mtr">ʔaža</x-slot>
        <x-slot name="past3F">أجت</x-slot>
        <x-slot name="past3Ftr">ʔažat</x-slot>
        <x-slot name="past3P">أجو</x-slot>
        <x-slot name="past3Ptr">ʔažu</x-slot>

    @endif

    @if (in_array($form, ['A1', 'A2i']))
        <x-slot name="amr2M">إ{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔ{{ $vowel }}{{$r2tr}}i{{$r3tr}}</x-slot>
        <x-slot name="amr2F">إ{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔ{{ $vowel }}{{$r2tr}}i{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔ{{ $vowel }}{{$r2tr}}i{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['A1a', 'A2']))
        <x-slot name="amr2M">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔ{{ $vowel }}{{$r2tr}}a{{$r3tr}}</x-slot>
        <x-slot name="amr2F">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔ{{ $vowel }}{{$r2tr}}a{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔ{{ $vowel }}{{$r2tr}}a{{$r3tr}}u</x-slot>

    @elseif ($form == 'A1u')
        <x-slot name="amr2M">أ{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">ʔu{{$r1tr}}{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="amr2F">أ{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔu{{$r1tr}}{{$r2tr}}u{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">أ{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">ʔu{{$r1tr}}{{$r2tr}}u{{$r3tr}}u</x-slot>

    @elseif ($form == 'A1h')
        <x-slot name="amr2M">{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">{{$r2tr}}u{{$r3tr}}</x-slot>
        <x-slot name="amr2F">{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">{{$r2tr}}u{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">{{$r2tr}}u{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['B1', 'B2i']))
        <x-slot name="amr2M">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Mtr">ʔ{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="amr2F">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔ{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔ{{ $vowel }}{{$r2tr}}u</x-slot>

    @elseif ($form == 'B1a')
        <x-slot name="amr2M">إ{{$r1}}{{$r2}}ا</x-slot>
        <x-slot name="amr2Mtr">ʔi{{$r1tr}}{{$r2tr}}a</x-slot>
        <x-slot name="amr2F">إ{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔi{{$r1tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">إ{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔi{{$r1tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'B2')
        <x-slot name="amr2M">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}ى</x-slot>
        <x-slot name="amr2Mtr">ʔ{{ $vowel }}{{$r2tr}}a</x-slot>
        <x-slot name="amr2F">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}ي</x-slot>
        <x-slot name="amr2Ftr">ʔ{{ $vowel }}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">{{ $vowel === 'ū' ? 'أ' : 'إ' }}{{$r1}}{{$r2}}و</x-slot>
        <x-slot name="amr2Ptr">ʔ{{ $vowel }}{{$r2tr}}u</x-slot>

    @elseif ($form == 'Ci')
        <x-slot name="amr2M">{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}i{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}i{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}i{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'Ca')
        <x-slot name="amr2M">{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}a{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'Cu')
        <x-slot name="amr2M">{{$r1}}{{$r2}}ّ</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}u{{$r2tr}}{{$r2tr}}</x-slot>
        <x-slot name="amr2F">{{$r1}}{{$r2}}ّي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}u{{$r2tr}}{{$r2tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}{{$r2}}ّو</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}u{{$r2tr}}{{$r2tr}}u</x-slot>

    @elseif ($form == 'DY')
        <x-slot name="amr2M">{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}ī{{$r3tr}}</x-slot>
        <x-slot name="amr2F">{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}ī{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}ī{{$r3tr}}u</x-slot>

    @elseif (in_array($form, ['DAi', 'DAu']))
        <x-slot name="amr2M">{{$r1}}ا{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}ā{{$r3tr}}</x-slot>
        <x-slot name="amr2F">{{$r1}}ا{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}ā{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}ا{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}ā{{$r3tr}}u</x-slot>

    @elseif ($form == 'DW')
        <x-slot name="amr2M">{{$r1}}{{$r2}}{{$r3}}</x-slot>
        <x-slot name="amr2Mtr">{{$r1tr}}ū{{$r3tr}}</x-slot>
        <x-slot name="amr2F">{{$r1}}{{$r2}}{{$r3}}ي</x-slot>
        <x-slot name="amr2Ftr">{{$r1tr}}ū{{$r3tr}}i</x-slot>
        <x-slot name="amr2P">{{$r1}}{{$r2}}{{$r3}}و</x-slot>
        <x-slot name="amr2Ptr">{{$r1tr}}ū{{$r3tr}}u</x-slot>

    @elseif ($form == 'ʔaža')
        <x-slot name="amr2M">تعال</x-slot>
        <x-slot name="amr2Mtr">taʕāl</x-slot>
        <x-slot name="amr2F">تعالي</x-slot>
        <x-slot name="amr2Ftr">taʕāli</x-slot>
        <x-slot name="amr2P">تعالو</x-slot>
        <x-slot name="amr2Ptr">taʕālu</x-slot>

    @endif
</x-chart.conjugation>

@if (in_array($vowel, ['ū', 'ī']))
    <x-tip>
        <p>Because the first root letter is a consonant-vowel (i.e. <b>و</b> or <b>ي</b>), the coda of the first syllable in
            the Present Tense results in a <b>"iw"</b> or <b>"iy"</b> that is elided to <b>"ū"</b> or <b>"ī"</b>, respectively.</p>
    </x-tip>
@endif
