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
                <form action="" method="POST" id="agent_form">
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
                                <label for="organization_name">Organization Name</label>
                                <input class="form-control" type="text" name="organization_name" id="organization_name" value="{{ empty($user) ? '' : $user->agent_prfile->organization_name }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <label for="licence">Licence</label>
                                <input class="form-control" type="text" name="licence" id="licence" value="{{ empty($user) ? '' : $user->agent_prfile->licence }}">
                            </fieldset>
                        </div>

                        @php
                            $servicable_pincodes = (empty($user)) ? [] : array_filter(explode(',',$user->agent_prfile->servicable_pincodes));
                        @endphp
                        <div class="col-lg-6">
                            <fieldset class="select-box">
                                <label>Servicable Pincodes <strong class="text-danger">*</strong></label>
                                <select class="form-control" id="servicable_pincodes" name="servicable_pincodes[]" multiple required onchange="availablePincode()" data-parsley-errors-container="#servicable_pincodes_error" >
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
                                <textarea class="form-control" cols="" rows="4" placeholder="Enter Address" name="address" id="address">{{ empty($user) ? '' : $user->agent_prfile->address }}</textarea>
                            </fieldset>
                        </div>
                        @php
                            $state_id = (empty($user)) ? '' : $user->agent_prfile->state_id;
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
                                <input class="form-control" type="tel" name="pin_code" id="pin_code" value="{{ empty($user) ? '' : $user->agent_prfile->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                            </fieldset>
                        </div>

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="brand_logo_file">Upload Brand Logo <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="brand_logo_file" class="custom-file-input form-control" id="brand_logo_file" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="brand_logo_file">Choose file</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="gst_file">Upload GST <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="gst_file" class="custom-file-input form-control" id="gst_file" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="gst_file">Choose file</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="drug_licence_file">Upload Drug Licence <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="drug_licence_file" class="custom-file-input form-control" id="drug_licence_file" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="drug_licence_file">Choose file</label>
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
            $('#agent_form').parsley();

            $("#servicable_pincodes").select2({
                placeholder: "Enter Pincodes",
                tags: true,
            });

            @if (empty($user))
            $('#agent_form').submit(function (e) { 
                e.preventDefault();
                if ($('#agent_form').parsley().isValid()) {
                    const formID = 'agent_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('statehead.manage-user.agent.create') }}",
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
            $('#agent_form').submit(function (e) { 
                e.preventDefault();
                if ($('#agent_form').parsley().isValid()) {
                    const formID = 'agent_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('statehead.manage-user.agent.edit', ['id'=>$user->id]) }}",
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

        function availablePincode() {
            const pinCode = '{{ $agentReservePincodes->reservepincodes }}';
            //console.log(pinCode);
            const pincodeArr = pinCode.split(",");
            const pincodes = $('#servicable_pincodes').val();
            //console.log(pincodes);

            if (pincodes !== null) {
                if (pincodes.length > 0) {
                    for (let index = 0; index < pincodes.length; index++) {
                        const element = pincodes[index].toString();
                        if(pincodeArr.indexOf(element) >= 0){
                            toastr.error(element+' is Already Reserved');
                        }
                    }
                }
            }
        }
    </script>
@endpush
@endsection
