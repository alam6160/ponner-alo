@extends('user/common')
@section('content')

    <div class="user-form-card">
        <div class="user-form-title">
            <h2>Login Now!</h2>
            <p>Get Access To Your Orders, Wishlist & Etc.</p>
        </div>

        @if($errors->any())
            @foreach($errors->all() as $error)
                <li class="fp-message">{{$error}}</li>
            @endforeach
        @endif

        @if(session('message'))
            <li class="fp-message">{{session('message')}}</li>
        @endif

        <div class="user-form-group">
            {{-- <ul class="user-form-social">
                <li><a href="javascript:void(0);" class="facebook"><i class="fab fa-facebook-f"></i>Join with facebook</a></li>
                <li><a href="javascript:void(0);" class="twitter"><i class="fab fa-twitter"></i>Join with twitter</a></li>
                <li><a href="javascript:void(0);" class="google"><i class="fab fa-google"></i>Join with google</a></li>
                <li><a href="javascript:void(0);" class="instagram"><i class="fab fa-instagram"></i>Join with instagram</a></li>
            </ul>
            <div class="user-form-divider"><p>OR</p></div> --}}
            <form class="user-form" action="" method="post">
                @csrf
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="E-Mail ID" value="{{old('email')}}" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" minlength="6" required>
                </div>
                <div class="form-button">
                    <button type="submit" style="font-weight:bold;">LOGIN</button>

                    <p>Forgot Your Password?<a href="{{ route('resetpassword') }}">Reset Now!</a></p>
                </div>
            </form>
        </div>
    </div>
    <div class="user-form-remind">
        <p>Don't Have An Account?<a href="{{url('sign-up')}}">Register Now!</a></p>
    </div>

@endsection