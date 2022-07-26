@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>An OTP has been sent to your register email, enter it to change password.</p>

                    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Enter OTP to change your password</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('validate.otp') }}">
                        @csrf

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Enter OTP</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('otp') is-invalid @enderror" name="otp" required autocomplete="current-password">

                                @error('otp')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                       

                        

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>

                               
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
