@extends('admin.layout.layout')
@section('title', $title)
@section('content')
<div class="row">
    <div class="col-lg-12 main-box" style="flex: none; max-width: initial;">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>{{ $title }}</h3>
                </div>
                <form action="" method="POST" id="customer_form">
                    <div class="tab-wrap">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="basic_info-tab" data-toggle="tab" href="#basic_info" role="tab" aria-controls="basic_info" aria-selected="true">Basic Info</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="shipping_address-tab" data-toggle="tab" href="#shipping_address" role="tab" aria-controls="shipping_address" aria-selected="false">Shipping Address</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab" aria-controls="billing" aria-selected="false">Billing Address</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="basic_info" role="tabpanel" aria-labelledby="basic_info-ta">
                                <div class="row">

                                    <div class="col-lg-4">
                                        @php
                                            $title = (empty($user)) ? '' : $user->title ;
                                        @endphp
                                        <fieldset class="select-box">
                                            <label for="title">Select Title <strong class="text-danger">*</strong> </label>
                                            <select class="form-control cfcrl" name="title" id="title">
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
                                            <input type="text" name="fname" id="fname" class="form-control cfcrl" required value="{{ empty($user) ? '' : $user->fname }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="lname">Last Name <strong class="text-danger">*</strong></label>
                                            <input class="form-control cfcrl" type="text" name="lname" id="lname" required value="{{ empty($user) ? '' : $user->lname }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="email">Email <strong class="text-danger">*</strong></label>
                                            <input class="form-control cfcrl" type="text" name="email" id="email" required value="{{ empty($user) ? '' : $user->email }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="contact">Phone No. <strong class="text-danger">*</strong></label>
                                            <input class="form-control cfcrl" type="tel" name="contact" id="contact" required pattern="/^[0-9]{10}$/" maxlength="10" min="10" onkeypress="return onlyNumberKey(event)" value="{{ empty($user) ? '' : $user->contact }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="password">Password 
                                                @empty($user)
                                                <strong class="text-danger">*</strong>
                                                @endempty
                                            </label>
                                            <input class="form-control cfcrl" type="password" name="password" id="password"  minlength="6" @empty($user) required @endempty >
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-12">
                                        <fieldset>
                                            <label for="address">Address</label>
                                            <textarea class="form-control cfcrl" cols="" rows="2" placeholder="Enter Address" name="address" id="address">{{ empty($user) ? '' : $user->customer_profile->address }}</textarea>
                                        </fieldset>
                                    </div>
                                    @php
                                        $state_id = (empty($user)) ? '' : $user->customer_profile->state_id;
                                    @endphp
                                    <div class="col-lg-6">
                                        <fieldset class="select-box">
                                            <label for="state_id">Select State</label>
                                            <select class="form-control cfcrl" name="state_id" id="state_id">
                                                <option value="">Select State</option>
                                                @foreach ($allstates as $var)
                                                <option value="{{ $var->id }}" {{ $state_id == $var->id ? 'selected' : '' }}>
                                                    {{ $var->state_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6">
                                        <fieldset>
                                            <label for="pin_code">Pin Code</label>
                                            <input class="form-control cfcrl" type="tel" name="pin_code" id="pin_code" value="{{ empty($user) ? '' : $user->customer_profile->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                                        </fieldset>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="shipping_address" role="tabpanel" aria-labelledby="shipping_address-tab">
                                <div class="row">
                                    <div class="col-lg-4">
                                        @php
                                            $title = (empty($user)) ? '' : $user->customer_shipping->title ;
                                        @endphp
                                        <fieldset class="select-box">
                                            <label for="shipping_title">Select Title</label>
                                            <select class="form-control cfcrl" name="shipping_title" id="shipping_title">
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
                                            <label for="shipping_fname">First Name </label>
                                            <input type="text" name="shipping_fname" id="shipping_fname" class="form-control cfcrl" value="{{ empty($user) ? '' : $user->customer_shipping->fname }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="shipping_lname">Last Name </label>
                                            <input class="form-control cfcrl" type="text" name="shipping_lname" id="shipping_lname" value="{{ empty($user) ? '' : $user->customer_shipping->lname }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="shipping_email">Email </label>
                                            <input class="form-control cfcrl" type="text" name="shipping_email" id="shipping_email" value="{{ empty($user) ? '' : $user->customer_shipping->email }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="shipping_contact">Phone No. </label>
                                            <input class="form-control cfcrl" type="tel" name="shipping_contact" id="shipping_contact" pattern="/^[0-9]{10}$/" maxlength="10" min="10" onkeypress="return onlyNumberKey(event)" value="{{ empty($user) ? '' : $user->customer_shipping->contact }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="shipping_alt_contact">Alternative Phone No.</label>
                                            <input type="text" class="form-control cfcrl" name="shipping_alt_contact" id="shipping_alt_contact" pattern="/^[0-9]{10}$/" maxlength="10" min="10" onkeypress="return onlyNumberKey(event)" value="{{ empty($user) ? '' : $user->customer_shipping->alt_contact }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-12">
                                        <fieldset>
                                            <label for="shipping_address">Address</label>
                                            <textarea class="form-control cfcrl" cols="" rows="2" placeholder="Enter Address" name="shipping_address" id="shipping_address">{{ empty($user) ? '' : $user->customer_shipping->address }}</textarea>
                                        </fieldset>
                                    </div>
                                    @php
                                        $state_id = (empty($user)) ? '' : $user->customer_shipping->state_id;
                                    @endphp
                                    <div class="col-lg-6">
                                        <fieldset class="select-box">
                                            <label for="shipping_state_id">Select State</label>
                                            <select class="form-control cfcrl" name="shipping_state_id" id="shipping_state_id">
                                                <option value="">Select State</option>
                                                @foreach ($allstates as $var)
                                                <option value="{{ $var->id }}" {{ $state_id == $var->id ? 'selected' : '' }}>
                                                    {{ $var->state_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6">
                                        <fieldset>
                                            <label for="shipping_pin_code">Pin Code</label>
                                            <input class="form-control cfcrl" type="tel" name="shipping_pin_code" id="shipping_pin_code" value="{{ empty($user) ? '' : $user->customer_shipping->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                                        </fieldset>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="billing" role="tabpanel" aria-labelledby="billing-tab">
                                <div class="row">
                                    <div class="col-lg-4">
                                        @php
                                            $title = (empty($user)) ? '' : $user->customer_billing->title ;
                                        @endphp
                                        <fieldset class="select-box">
                                            <label for="billing_title">Select Title</label>
                                            <select class="form-control cfcrl" name="billing_title" id="billing_title">
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
                                            <label for="billing_fname">First Name </label>
                                            <input type="text" name="billing_fname" id="billing_fname" class="form-control cfcrl" value="{{ empty($user) ? '' : $user->customer_billing->fname }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="billing_lname">Last Name </label>
                                            <input class="form-control cfcrl" type="text" name="billing_lname" id="billing_lname" value="{{ empty($user) ? '' : $user->customer_billing->lname }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="billing_email">Email </label>
                                            <input class="form-control cfcrl" type="text" name="billing_email" id="billing_email" value="{{ empty($user) ? '' : $user->customer_billing->email }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="billing_contact">Phone No. </label>
                                            <input class="form-control cfcrl" type="tel" name="billing_contact" id="billing_contact" pattern="/^[0-9]{10}$/" maxlength="10" min="10" onkeypress="return onlyNumberKey(event)" value="{{ empty($user) ? '' : $user->customer_billing->contact }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-4">
                                        <fieldset>
                                            <label for="billing_alt_contact">Alternative Phone No.</label>
                                            <input type="text" class="form-control cfcrl" name="billing_alt_contact" id="billing_alt_contact" pattern="/^[0-9]{10}$/" maxlength="10" min="10" onkeypress="return onlyNumberKey(event)" value="{{ empty($user) ? '' : $user->customer_billing->alt_contact }}">
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-12">
                                        <fieldset>
                                            <label for="billing_address">Address</label>
                                            <textarea class="form-control cfcrl" cols="" rows="2" placeholder="Enter Address" name="billing_address" id="billing_address">{{ empty($user) ? '' : $user->customer_billing->address }}</textarea>
                                        </fieldset>
                                    </div>
                                    @php
                                        $state_id = (empty($user)) ? '' : $user->customer_billing->state_id;
                                    @endphp
                                    <div class="col-lg-6">
                                        <fieldset class="select-box">
                                            <label for="billing_state_id">Select State</label>
                                            <select class="form-control cfcrl" name="billing_state_id" id="billing_state_id">
                                                <option value="">Select State</option>
                                                @foreach ($allstates as $var)
                                                <option value="{{ $var->id }}" {{ $state_id == $var->id ? 'selected' : '' }}>
                                                    {{ $var->state_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-6">
                                        <fieldset>
                                            <label for="billing_pin_code">Pin Code</label>
                                            <input class="form-control cfcrl" type="tel" name="billing_pin_code" id="billing_pin_code" value="{{ empty($user) ? '' : $user->customer_billing->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                                        </fieldset>
                                    </div>

                                </div>
                            </div>
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
            $('#customer_form').parsley();

            @if (empty($user))
            $('#customer_form').submit(function (e) { 
                e.preventDefault();
                if ($('#customer_form').parsley().isValid()) {
                    const formID = 'customer_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manage-user.customer.create') }}",
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
            $('#customer_form').submit(function (e) { 
                e.preventDefault();
                if ($('#customer_form').parsley().isValid()) {
                    const formID = 'customer_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manage-user.customer.edit', ['id'=>$user->id]) }}",
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
