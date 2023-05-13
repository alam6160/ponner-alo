@extends('user.common')
@section('content')

<div class="user-form-card">
    <div class="user-form-title">
        <h2>worried?</h2>
        <p>No Problem! Just Follow The Simple Way</p>
    </div>
    <form class="user-form" action="" method="post" id="resetpassword_form">
        @csrf
        <div class="form-group"><input type="email" name="email" class="form-control" placeholder="Enter your register email"  required /></div>
        <div class="form-button">
            <button type="submit" name="submit">get New password</button>
        </div>
    </form>
</div>
<div class="user-form-remind">
    <p>Go Back To<a href="{{ route('home') }}">Home</a></p>
</div>

@push('script')
        <script>
            $(function(){
                $('#resetpassword_form').submit(function (e) { 
                    e.preventDefault();
                    const formID = 'resetpassword_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('resetpassword') }}",
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
                                $("#"+formID+" [name='submit']").text('get New password');

                                $("#"+formID)[0].reset();
                                toastr.success(response.message);
                            } else {
                                $("#"+formID+" [name='submit']").prop('disabled', false);
                                $("#"+formID+" [name='submit']").text('get New password');
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