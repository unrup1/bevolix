@extends('layouts.app')

@section('title')Anmeldung - {{ $facility->name }}@endsection

@section('head')
    <!-- Data for the Form -->
    <script>
        var facility = @json($facility);
    </script>
@endsection

@section('content')
    <div id="app">
        <corona-app></corona-app>
    </div>

    <div class="container">
        <div class="row">
            <div class="col text-center mb-3">
                Erstellt mit <a target="_blank" href="{{ env('APP_INFO_URL') }}">{{ env('APP_NAME') }}</a>
            </div>
        </div>
    </div>

    <!-- App -->
    <script src="{{ mix('js/corona-form.js') }}"></script>
@endsection
