@extends('layouts.v1.guest')

@section('content')
    <div class="card">
        <div class="header">
            <h3>
                <span class="clearfix title">
                    <h2><span class="text text-danger">Error 403 <br />Accès refusé!</span></h2>
                </span>
            </h3>
        </div>
        <div class="body">
            <p class="lead">Vous n'avez pas les autorisations nécessaires pour accéder à cette page.</p>
            <div class="margin-top-30">
                <a href="javascript:history.go(-1)" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i>
                    <span>Retour</span></a>
                <a href="{{ route('home') }}" class="btn btn-primary btn-block"><i class="fa fa-home"></i>
                    <span>Accueil</span></a>
            </div>
        </div>
    </div>
@endsection
