<div class="card flashcard">

  @isset($frnum)

    @if($frnum === 'close')
    <div class="num redbgr block material-icons-round">

    @elseif($frnum === 'check')
    <div class="num grnbgr block material-icons-round">

    @elseif($frnum === 'question_mark')
    <div class="num yelbgr block material-icons-round">

    @else
    <div {{ $attributes->merge(['class' => 'num']) }}>

    @endif

    <div class="front">{{ $frnum }}</div>
    <div class="back">{{ $bknum }}</div>
  </div>

  @endisset

  <div class="content">
    <div class="front">

      @isset($frttl)
        <div class="title">{!! $frttl !!}</div>
      @endisset

      @isset($frar)
      <div class="arb">{!! $frar !!}</div>
      @endisset

      @isset($fren)
        <div class="eng">{!! $fren !!}</div>
      @endisset

      @isset($frgl)
        <div class="gloss">{!! $frgl !!}</div>
      @endisset

    </div>

    <div class="back">

      @isset($bkttl)
        <div class="title">{!! $bkttl !!}</div>
      @endisset

      @isset($bkar)
      <div class="arb">{!! $bkar !!}</div>
      @endisset

      @isset($bken)
        <div class="eng">{!! $bken !!}</div>
      @endisset

      @isset($bkgl)
        <div class="gloss">{!! $bkgl !!}</div>
      @endisset

    </div>
  </div>
</div>
