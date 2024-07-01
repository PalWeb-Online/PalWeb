@extends ('layouts.main')

@section('content')

    @include("wiki.pages." . $page, ["terms" => $terms])

@endsection
