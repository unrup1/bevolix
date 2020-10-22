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
                Erstellt mit <a target="_blank" href="{{ config('app.info_url') }}">{{ config('app.name') }}</a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="col text-center">
            <a href="https://github.com/unrup1/bevolix" target="_blank"><ion-icon name="logo-github"></ion-icon> Github</a>
            <a class="ml-3" href="/imprint">Impressum</a>
            <a class="ml-3" href="/privacy-policy">Datenschutzerklärung</a>
        </div>
    </div>

    <!-- App -->
    <script src="{{ mix('js/corona-form.js') }}"></script>
@endsection
