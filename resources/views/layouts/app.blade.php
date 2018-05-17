@extends('layouts.base')

@section('base_content')
    <nav class="margin-bottom-12 navbar navbar-default navbar-static-top">
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
        <div class="margin-bottom-12 container-fluid">
            <ul class="nav nav-pills">
                <li role="presentation" {{ (Route::current()->uri == 'alias') ? 'class=active' : ''}} ><a href="{{ route('alias.index') }}">Aliases</a></li>
                <li role="presentation" {{ (Route::current()->uri == 'domain') ? 'class=active' : ''}} ><a href="{{ route('domain.index') }}">Domínios</a></li>
                <li role="presentation" {{ (Route::current()->uri == 'mailbox') ? 'class=active' : ''}} ><a href="{{ route('mailbox.index') }}">Contas</a></li>
            </ul>
        </div>
    @endif
    @yield('content')
    <script>
        $('document').ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
