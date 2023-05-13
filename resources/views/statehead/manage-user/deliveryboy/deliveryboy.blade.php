@extends('statehead.layout.layout')
@section('title', $title)
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>{{ $title }}</h3>
                </div>
                <form action="" method="POST" id="deliveryboy_form">
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

                        <div class="col-lg-6">
                            <fieldset>
                                <label>Driving Licence</label>
                                <input type="text" class="form-control" name="driving_licence" id="driving_licence" value="{{ empty($user) ? '' : $user->deliveryboy_profile->driving_licence }}">
                            </fieldset>
                        </div>

                        @php
                            $state_id = (empty($user)) ? '' : $user->deliveryboy_profile->state_id;
                        @endphp
                        <div class="col-lg-6">
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
                        <div class="col-lg-6">
                            <fieldset>
                                <label for="pin_code">Pin Code</label>
                                <input class="form-control" type="tel" name="pin_code" id="pin_code" value="{{ empty($user) ? '' : $user->deliveryboy_profile->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                            </fieldset>
                        </div>

                        @php
                            $agent_id = (empty($user)) ? '' : $user->deliveryboy_profile->agent_id;
                        @endphp
                        <div class="col-lg-6">
                            <fieldset class="select-box">
                                <label>Select a Agent  <strong class="text-danger">*</strong></label>
                                <select class="form-control" name="agent_id" id="agent_id" required>
                                    <option value="">Select Agent</option>
                                    @foreach ($allagents as $var)
                                    <option value="{{ $var->id }}" {{ $agent_id == $var->id ? 'selected' : ''  }}>
                                        {{ $var->fname.' '.$var->lname .' ('.$var->email.')' }}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>
                        
                        {{-- <div class="col-lg-6">
                            <fieldset>
                                <label for="servicable_pincodes">Servicable Pincodes <strong class="text-danger">*</strong></label>
                                <textarea class="form-control" cols="" rows="4" placeholder="Enter Servicable Pincodes" name="servicable_pincodes" id="servicable_pincodes" required>{{ empty($user) ? '' : $user->deliveryboy_profile->servicable_pincodes }}</textarea>
                            </fieldset>
                        </div> --}}

                        @php
                            $servicable_pincodes = (empty($user)) ? [] : array_filter(explode(',',$user->deliveryboy_profile->servicable_pincodes));
                        @endphp
                        <div class="col-lg-6">
                            <fieldset class="select-box">
                                <label>Servicable Pincodes <strong class="text-danger">*</strong></label>
                                <select class="form-control" id="servicable_pincodes" name="servicable_pincodes[]" multiple required data-parsley-errors-container="#servicable_pincodes_error" >
                                    @if (!empty($servicable_pincodes))
                                        @foreach ($servicable_pincodes as $pincode)
                                            <option value="{{ $pincode }}" selected>{{ $pincode }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="servicable_pincodes_error"></div>
                            </fieldset>
                        </div>

                        <div class="col-lg-6">
                            <fieldset>
                                <label for="address">Address</label>
                                <textarea class="form-control" cols="" rows="4" placeholder="Enter Address" name="address" id="address">{{ empty($user) ? '' : $user->deliveryboy_profile->address }}</textarea>
                            </fieldset>
                        </div>

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label>Upload Driving Licence <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="driving_licence_file" class="custom-file-input form-control" id="driving_licence_file" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="driving_licence_file">Choose file</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="aadhaar_file">Upload Aadhaar <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="aadhaar_file" class="custom-file-input form-control" id="aadhaar_file" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="aadhaar_file">Choose file</label>
                                    </div>
                                </div>
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
            $('#deliveryboy_form').parsley();

            $("#servicable_pincodes").select2({
                placeholder: "Enter Pincodes",
                tags: true,
            });

            @if (empty($user))
            $('#deliveryboy_form').submit(function (e) { 
                e.preventDefault();
                if ($('#deliveryboy_form').parsley().isValid()) {
                    const formID = 'deliveryboy_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('statehead.manage-user.deliveryboy.create') }}",
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
                                $("#servicable_pincodes").val('').trigger('change');
                            } else {
                                setRestButton(formID, 'submit');
                                setError(response.message);
                            }
                        }
                    });
                }
            });
            @else
            $('#deliveryboy_form').submit(function (e) { 
                e.preventDefault();
                if ($('#deliveryboy_form').parsley().isValid()) {
                    const formID = 'deliveryboy_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('statehead.manage-user.deliveryboy.edit', ['id'=>$user->id]) }}",
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
