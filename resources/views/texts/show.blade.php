@extends ('layouts.main')

@section('content')

    @include("texts.pages." . $text, ["terms" => $terms])

@endsection
