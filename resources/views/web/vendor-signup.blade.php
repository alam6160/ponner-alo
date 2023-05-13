@extends('user.common')
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
            <form class="user-form" action="" method="post" id="agent_form">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="title">
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Sri">Sri</option>
                                <option value="Smt.">Smt.</option>
                                <option value="Kumari">Kumari</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <select class="form-control" name="vendor_type" required>
                                <option value="" selected disabled>Vendor Type</option>
                                <option value="1">Regular</option>
                                <option value="2">Subscription</option>
                            </select>
                        </div>
                    </div>
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
                    <input type="text" name="contact" class="form-control" placeholder="Phone No." value="{{old('contact')}}" required maxlength="12" minlength="10">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" minlength="6" required>
                </div>
                <div class="form-group">
                    <input type="text" name="organization_name" class="form-control" placeholder="Organization Name" value="{{old('organization_name')}}">
                </div>
                <div class="form-button">
                    <button type="submit" name="submit" style="font-weight:bold;">REGISTER</button>
                </div>
            </form>
        </div>
    </div>
    <div class="user-form-remind">
        <p>Already Have An Account?<a href="{{ route('agent') }}">Login Now!</a></p>
    </div>

    @push('script')
        <script>
            $(function(){
                $('#agent_form').submit(function (e) { 
                    e.preventDefault();
                    const formID = 'agent_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('vendorsignup') }}",
                        data: new FormData(this),
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        beforeSend: function(){
                            $("#"+formID+" [name='submit']").prop('disabled', true);
                            $("#"+formID+" [name='submit']").html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                        },
                        success: function (response) {
                            if (response.type == 'success') {

                                $("#"+formID+" [name='submit']").prop('disabled', false);
                                $("#"+formID+" [name='submit']").text('REGISTER');

                                $("#"+formID)[0].reset();
                                toastr.success(response.message);
                            } else {
                                $("#"+formID+" [name='submit']").prop('disabled', false);
                                $("#"+formID+" [name='submit']").text('REGISTER');
                                if (Array.isArray(response.message)) {
                                    response.message.forEach(function (val) { toastr.error(val); });
                                } else { toastr.error(response.message); }
                            }
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection