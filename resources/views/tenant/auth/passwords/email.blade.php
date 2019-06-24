@extends('layouts.auth')

@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form class="form-material" id="loginform" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
            <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

            <div class="">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group text-center">
            <div class="col-xs-12 p-b-20">
                <button class="btn btn-block btn-info btn-rounded" type="submit">{{ __('Send Password Reset Link') }}</button>
            </div>
        </div>
    </form>
@endsection
