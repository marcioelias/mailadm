@extends('layouts.app')

@section('content')
@if (Session::has('success'))
	<div class="alert alert-success alert-dismissible" id="success-alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('success') }}
    </div>
@endif
    <div class="panel panel-default">
        <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6"><h3>Minha Conta</h3></div>
                    <div class="col-md-6">
                        <a class="btn btn-success pull-right" href="{{ route('site.index')}}">Voltar</a>
                    </div>
                </div>
        </div>
        <div class="panel panel-body">
            <div class="col-md-12 col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Dados da Conta
                    </div>
                    <div class="panel-body">
                        <div class="well-sm">
                            <h5><strong>Nome:</strong></h5> {{$user->name}}
                        </div> 
                        <div class="well-sm">
                            <h5><strong>E-mail:</strong></h5> {{$user->email}}
                        </div>
                        <div class="well-sm">
                            <h5><strong>Criado em:</strong></h5> {{$user->created_at}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Senha
                    </div>
                    <div class="panel-body">
                        <a href="{{route('user.form.change.password')}}" class="btn btn-primary">Alterar minha Senha</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Perfil
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach($user->roles as $role)
                                <li class="list-group-item">{{$role->display_name}}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection