@extends('layouts.base')

@section('base_content')
    <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0px !important;">
        <div class="container container-fluid">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand nav-logo" href="{{ url('/') }}">
                    <img src="{{ asset('images/logo_empresa.png') }}">
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{route('user.profile')}}"><i class="glyphicon glyphicon-cog"></i> Minha Conta</a></li>
                                <li class="divider"></li>
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
    @php
        $action = explode('.', Route::current()->action['as']);
    @endphp

    @if($action[1] == 'index')
        <div class="container-fluid">
            <ul class="nav nav-pills">
                @permission('listar-alias')
                <li role="presentation" {{ (Route::current()->uri == 'alias') ? 'class=active' : ''}} ><a href="{{ route('alias.index') }}">Aliases</a></li>
                @endpermission
                @permission('listar-domain')
                <li role="presentation" {{ (Route::current()->uri == 'domain') ? 'class=active' : ''}} ><a href="{{ route('domain.index') }}">Domínios</a></li>
                @endpermission
                @permission('listar-mailbox')
                <li role="presentation" {{ (Route::current()->uri == 'mailbox') ? 'class=active' : ''}} ><a href="{{ route('mailbox.index') }}">Contas</a></li>
                @endpermission
                @permission('listar-user')
                <li role="presentation" {{ (Route::current()->uri == 'user') ? 'class=active' : ''}} ><a href="{{ route('user.index') }}">Usuários</a></li>
                @endpermission
                @permission('listar-role')
                <li role="presentation" {{ (Route::current()->uri == 'role') ? 'class=active' : ''}} ><a href="{{ route('role.index') }}">Perfis</a></li>
                @endpermission
            </ul>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" id="success-alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ Session::get('success') }}
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" id="success-alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            {{ Session::get('error') }}
        </div>
    @endif
    @yield('content')
    @push('bottom-scripts')
    <script>
        $('document').ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    @endpush
@endsection
