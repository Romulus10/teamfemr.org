@extends('layouts.app', [ 'content_wrapper_class' => 'login-wrapper' ])

@section('content')

    <div class="login-box">

        <div class="columns">
            <div class="column is-half is-offset-one-quarter">
                <div class="card ">
                    <header class="card-header">
                        <p class="card-header-title">
                            Login
                        </p>
                    </header>
                    <div class="card-content">
                        <form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
                            {{ csrf_field() }}

                            <div class="content">

                                <div class="field">
                                    <label class="label">Email</label>
                                    <p class="control">
                                        <input name="email" class="input{{ $errors->has('email') ? ' is-danger' : '' }}" type="email" value="{{ old('email') }}" required autofocus>
                                    </p>
                                </div>
                                @include('auth.components.form-errors', ['field' => 'email', 'type' => 'horizontal'])

                                <div class="field">
                                    <label class="label">Password</label>
                                    <p class="control">
                                        <input name="password" class="input{{ $errors->has('password') ? ' is-danger' : '' }}" type="password" required>
                                    </p>
                                </div>
                                @include('auth.components.form-errors', ['field' => 'password', 'type' => 'horizontal'])

                                <div class="field">
                                    <p class="control">
                                        <label class="checkbox">
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Remember me
                                        </label>
                                    </p>
                                </div>

                                <div class="field is-grouped button-container">

                                    <p class="control">
                                        <button class="button is-primary">Submit</button>
                                    </p>

                                    <p class="control is-expanded forgot-password">
                                        <a href="{{ route( 'password.request' ) }}">
                                            Forgot password
                                        </a>
                                        <span class="sep">|</span>
                                        <a href="{{ route('register') }}">
                                            Register
                                        </a>
                                    </p>

                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection