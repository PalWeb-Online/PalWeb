@extends('layouts.app')

@section('inertia-head')
    @inertiaHead
@endsection

@section('inertia-body')
    <div id="page-body" class="app">
        <div id="page-content">
            @inertia
        </div>
    </div>
@endsection
