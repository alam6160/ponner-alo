@extends('admin.layout.layout')
@section('title', 'Edit Profile')
@section('content')
<div class="row">
    <div class="col-lg-12 main-box">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>Edit Profile</h3>
                </div>
                <form class="filter-wrap" action="" method="POST" id="profile_form">
                    <fieldset>
                        <label>First Name <strong class="text-danger">*</strong> </label>
                        <input type="text" name="fname" id="fname" class="form-control" value="{{ Auth::user()->fname }}" required>
                    </fieldset>
                    <fieldset>
                        <label>Last Name <strong class="text-danger">*</strong></label>
                        <input type="text" name="lname" id="lname" class="form-control" value="{{ Auth::user()->lname }}" required>
                    </fieldset>
                    <fieldset>
                        <label>Email <strong class="text-danger">*</strong></label>
                        <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="form-control" minlength="6" required>
                    </fieldset>
                    <button type="submit" name="submit" class="btn-block btn-dark">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#profile_form').parsley();

            $('#profile_form').submit(function (e) { 
                e.preventDefault();
                const formID = 'profile_form';
                if ($('#profile_form').parsley().isValid()) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.profile.edit') }}",
                        data: new FormData(this),
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        beforeSend: function(){
                            setSubmitButton(formID);
                        },
                        success: function (response) {
                            
                            if (response.type == 'success') {
                                $('#'+formID).parsley().reset();
                                alert(response.message)
                                window.location.reload();
                                //toastr.success(response.message);
                            } else {
                                $('#'+formID+' [name=submit]').prop('disabled', false);
                                $('#'+formID+' [name=submit]').text('Submit');
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
