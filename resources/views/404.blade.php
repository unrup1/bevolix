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
                Powerd by <a href="{{ env('APP_INFO_URL') }}">{{ env('APP_NAME') }}</a>
            </div>
        </div>
    </div>
@endsection
