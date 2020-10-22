@extends('layouts.app')

@section('title') 404 - Nicht gefunden @endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col text-center p-4">
                <ion-icon name="thunderstorm-outline" class="big text-primary"></ion-icon>
                <h1>Upps</h1>
                <h2>Leider konnte das Formular nicht gefunden werden...</h2>
                <hr>
                Powerd by <a href="{{ config('app.info_url') }}">{{ config('app.name') }}</a>
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
@endsection
