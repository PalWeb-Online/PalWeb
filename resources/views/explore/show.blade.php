@extends ('layouts.main')

@section('content')

    @include("explore.pages." . $page, ["terms" => $terms])

@endsection
