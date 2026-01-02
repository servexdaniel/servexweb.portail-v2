@extends('layouts.v1.guest')

@section('content')
    <div class="card">
        <div class="header">
            <h3>
                <span class="clearfix title">
                    <span class="number left text-danger">404 </span><span class="text text-danger">Oops! <br />Page Not
                        Found</span>
                </span>
            </h3>
        </div>
        <div class="body">
            <p>The page you were looking for could not be found, please <a href="javascript:void(0);">contact us</a> to
                report this issue.</p>
            <div class="margin-top-30">
                <a href="javascript:history.go(-1)" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i>
                    <span>Retour</span></a>
                <a href="{{ route('home', ['language' => app()->getLocale()]) }}" class="btn btn-primary btn-block"><i
                        class="fa fa-home"></i>
                    <span>Accueil</span></a>
            </div>
        </div>
    </div>
@endsection
