@extends('agent.layout.layout')
@section('title', 'Profile')
@section('content')
<div class="row">
    <div class="col-lg-12 main-box">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>Change System Password</h3>
                </div>
                <form class="filter-wrap" action="" method="POST" id="chagepassword_form">
                    <fieldset>
                        <label>New Password</label>
                        <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                    </fieldset>
                    <fieldset>
                        <label>Confirm Password</label>
                        <input type="password" name="cpassword" id="cpassword" class="form-control" minlength="6" required data-parsley-equalto="#password">
                    </fieldset>
                    <button type="submit" name="submit" class="btn-block btn-dark">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#chagepassword_form').parsley();

            $('#chagepassword_form').submit(function (e) { 
                e.preventDefault();
                const formID = 'chagepassword_form';
                if ($('#chagepassword_form').parsley().isValid()) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('agent.profile.chagepassword') }}",
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        beforeSend: function(){
                            setSubmitButton(formID);
                        },
                        success: function (response) {
                            $('#'+formID+' [name=submit]').prop('disabled', false);
                            $('#'+formID+' [name=submit]').text('Change Password');
                            if (response.type == 'success') {
                                $('#'+formID).parsley().reset();
                                $('#'+formID)[0].reset();
                                toastr.success(response.message);
                            } else {
                                setError(response.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
@endsection
