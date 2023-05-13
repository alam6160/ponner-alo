@extends('admin.layout.layout')
@section('title', $title)
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>{{ $title }}</h3>
                </div>
                <form action="" method="POST" id="staff_form">
                    <div class="row">
                        <div class="col-lg-4">
                            @php
                                $title = (empty($user)) ? '' : $user->title ;
                            @endphp
                            <fieldset class="select-box">
                                <label for="title">Select Title <strong class="text-danger">*</strong> </label>
                                <select class="form-control" name="title" id="title">
                                    <option value="Mr." {{ $title == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                                    <option value="Mrs." {{ $title == 'Mrs.' ? 'selected' : '' }}>Mrs.</option>
                                    <option value="Sri" {{ $title == 'Sri' ? 'selected' : '' }}>Sri</option>
                                    <option value="Smt." {{ $title == 'Smt.' ? 'selected' : '' }}>Smt.</option>
                                    <option value="Kumari" {{ $title == 'Kumari' ? 'selected' : '' }}>Kumari</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset>
                                <label for="fname">First Name <strong class="text-danger">*</strong></label>
                                <input type="text" name="fname" id="fname" class="form-control" required value="{{ empty($user) ? '' : $user->fname }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset>
                                <label for="lname">Last Name <strong class="text-danger">*</strong></label>
                                <input class="form-control" type="text" name="lname" id="lname" required value="{{ empty($user) ? '' : $user->lname }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset>
                                <label for="email">Email <strong class="text-danger">*</strong></label>
                                <input class="form-control" type="text" name="email" id="email" required value="{{ empty($user) ? '' : $user->email }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset>
                                <label for="contact">Phone No. <strong class="text-danger">*</strong></label>
                                <input class="form-control" type="tel" name="contact" id="contact" required pattern="/^[0-9]{10}$/" maxlength="10" min="10" onkeypress="return onlyNumberKey(event)" value="{{ empty($user) ? '' : $user->contact }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-4">
                            <fieldset>
                                <label for="password">Password 
                                    @empty($user)
                                    <strong class="text-danger">*</strong>
                                    @endempty
                                </label>
                                <input class="form-control" type="password" name="password" id="password"  minlength="6" @empty($user) required @endempty >
                            </fieldset>
                        </div>

                        @php
                            $user_type = (empty($user)) ? '' : $user->user_type;
                        @endphp
                        <div class="col-lg-4">
                            <fieldset class="select-box">
                                <label>Select Dept. <strong class="text-danger">*</strong></label>
                                <select class="form-control" name="user_type" id="user_type" required>
                                    <option value="2" {{ $user_type == '2' ? 'selected' : '' }}>Admin</option>
                                    <option value="6" {{ $user_type == '6' ? 'selected' : '' }}>Phermasist</option>
                                    <option value="8" {{ $user_type == '8' ? 'selected' : '' }}>Marketing</option>
                                    <option value="7" {{ $user_type == '7' ? 'selected' : '' }}>Support</option>
                                </select>
                            </fieldset>
                        </div>

                        @php
                            $state_id = (empty($user)) ? '' : $user->staff_profile->state_id;
                        @endphp
                        <div class="col-lg-4">
                            <fieldset class="select-box">
                                <label for="state_id">Select State</label>
                                <select class="form-control" name="state_id" id="state_id">
                                    <option value="">Select State</option>
                                    @foreach ($allstates as $var)
                                    <option value="{{ $var->id }}" {{ $state_id == $var->id ? 'selected' : '' }}>
                                        {{ $var->state_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-lg-4">
                            <fieldset>
                                <label for="pin_code">Pin Code</label>
                                <input class="form-control" type="tel" name="pin_code" id="pin_code" value="{{ empty($user) ? '' : $user->staff_profile->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                            </fieldset>
                        </div>

                        

                        <div class="col-lg-12">
                            <fieldset>
                                <label for="address">Address</label>
                                <textarea class="form-control" cols="" rows="2" placeholder="Enter Address" name="address" id="address">{{ empty($user) ? '' : $user->staff_profile->address }}</textarea>
                            </fieldset>
                        </div>

                        

                    </div>
                    <button type="submit" name="submit" class="btn-primary">{{ empty($user) ? 'Submit' : 'Update' }}  </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#staff_form').parsley();

            @if (empty($user))
            $('#staff_form').submit(function (e) { 
                e.preventDefault();
                if ($('#staff_form').parsley().isValid()) {
                    const formID = 'staff_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manage-user.staff.create') }}",
                        data: new FormData(this),
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        beforeSend: function(){
                            setSubmitButton(formID);
                        },
                        success: function (response) {
                            if (response.type == 'success') {
                                setSubmitButton(formID, 'reset', response.message);
                            } else {
                                setRestButton(formID, 'submit');
                                setError(response.message);
                            }
                        }
                    });
                }
            });
            @else
            $('#staff_form').submit(function (e) { 
                e.preventDefault();
                if ($('#staff_form').parsley().isValid()) {
                    const formID = 'staff_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manage-user.staff.edit', ['id'=>$user->id]) }}",
                        data: new FormData(this),
                        cache: false,
                        processData: false,
                        contentType: false,
                        dataType: "JSON",
                        beforeSend: function(){
                            setUpdateButton(formID);
                        },
                        success: function (response) {
                            if (response.type == 'success') {
                                setUpdateButton(formID, 'reset', response.message);
                                $('#password').val('');
                            } else if(response.type == 'error'){
                                setRestButton(formID, 'update');
                                setError(response.message);
                            }else if(response.type == 'redirect'){
                                setRedirect(response.url, response.message);
                            }
                        }
                    });
                }
            });
            @endif
        });
    </script>
@endpush
@endsection
