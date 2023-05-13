@extends('user/common')
@section('content')

    <div class="user-form-card">
        <div class="user-form-title">
            <h2>Register Now!</h2>
            <p>Create Your Account & Let's Get Started.</p>
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
                    <select class="form-control" name="title">
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs.">Mrs.</option>
                        <option value="Sri">Sri</option>
                        <option value="Smt.">Smt.</option>
                        <option value="Kumari">Kumari</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="text" name="fname" class="form-control" placeholder="First Name" value="{{old('fname')}}" required>
                </div>
                <div class="form-group">
                    <input type="text" name="lname" class="form-control" placeholder="Last Name" value="{{old('lname')}}" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="E-Mail ID" value="{{old('email')}}" required>
                </div>
                <div class="form-group">
                    <input type="text" name="contact" class="form-control" placeholder="Phone No." value="{{old('contact')}}" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" minlength="6" required>
                </div>
                <div class="form-check mb-3">
                    <label class="form-check-label">By continuing, you agree to Greeny's <a href="{{ route('termsconditions') }}">Terms & Conditions</a> and <a href="{{ route('privacypolicy') }}">Privacy Policy</a>.</label>
                </div>
                <div class="form-button">
                    <button type="submit" style="font-weight:bold;">REGISTER</button>
                </div>
            </form>
        </div>
    </div>
    <div class="user-form-remind">
        <p>Already Have An Account?<a href="{{url('sign-in')}}">Login Now!</a></p>
    </div>

@endsection