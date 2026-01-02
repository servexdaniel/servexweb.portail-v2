@extends('layouts.v1.guest')

@section('content')
    <div class="card">
        <div class="header">
            <h3>
                <span class="clearfix title">
                    <span class="number text-danger">500</span><br>
                    <span class="text-danger">Internal Server Error</span>
                </span>
            </h3>
        </div>
        <div class="body">
            <p>Apparently we're experiencing an error. But don't worry, we will solve it shortly.
                <br>Please try after some time.
            </p>
            <p><a href="{{ route('home', ['language' => app()->getLocale()]) }}" class="btn btn-primary btn-block"><i
                        class="fa fa-home"></i>
                    <span>Accueil</span></a></p>
        </div>
    </div>
@endsection
