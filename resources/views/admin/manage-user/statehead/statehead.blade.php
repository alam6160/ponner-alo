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
                <form action="" method="POST" id="statehead_form">
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
                                <input class="form-control" type="text" name="organization_name" id="organization_name" value="{{ empty($user) ? '' : $user->statehead_prfile->organization_name }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <label for="licence">Licence</label>
                                <input class="form-control" type="text" name="licence" id="licence" value="{{ empty($user) ? '' : $user->statehead_prfile->licence }}">
                            </fieldset>
                        </div>

                        <div class="col-lg-12">
                            <fieldset>
                                <label for="address">Address</label>
                                <textarea class="form-control" cols="" rows="2" placeholder="Enter Address" name="address" id="address">{{ empty($user) ? '' : $user->statehead_prfile->address }}</textarea>
                            </fieldset>
                        </div>

                        @php
                            $state_id = (empty($user)) ? '' : $user->statehead_prfile->state_id;
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
                        <div class="col-lg-3">
                            <fieldset>
                                <label for="pin_code">Pin Code</label>
                                <input class="form-control" type="tel" name="pin_code" id="pin_code" value="{{ empty($user) ? '' : $user->statehead_prfile->pin_code }}" onkeypress="return onlyNumberKey(event)" data-parsley-type="integer">
                            </fieldset>
                        </div>

                        @php
                            $servicable_state_id = (empty($user)) ? '' : $user->statehead_prfile->servicable_state_id;
                        @endphp
                        <div class="col-lg-5">
                            <fieldset class="select-box">
                                <label for="servicable_state_id">Servicable State <strong class="text-danger">*</strong></label>
                                <select class="form-control" name="servicable_state_id" id="servicable_state_id" required>
                                    <option value="">Select State</option>
                                    @foreach ($unReserveStates as $var)
                                    <option value="{{ $var->id }}" {{ $servicable_state_id == $var->id ? 'selected' : '' }}>
                                        {{ $var->state_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </fieldset>
                        </div>

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="brand_logo_file">Upload Brand Logo <small>(only jpg,jpeg,png or maximum file size 2MB)</small>  </label>
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
            $('#statehead_form').parsley();

            @if (empty($user))
            $('#statehead_form').submit(function (e) { 
                e.preventDefault();
                if ($('#statehead_form').parsley().isValid()) {
                    const formID = 'statehead_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manage-user.statehead.create') }}",
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
                                setServicableState(response.unReserveStates);
                            } else {
                                setRestButton(formID, 'submit');
                                setError(response.message);
                            }
                        }
                    });
                }
            });
            @else
            $('#statehead_form').submit(function (e) { 
                e.preventDefault();
                if ($('#statehead_form').parsley().isValid()) {
                    const formID = 'statehead_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.manage-user.statehead.edit', ['id'=>$user->id]) }}",
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
        function setServicableState(unReserveStates){
            let htmlString = '<option value="">Select State</option>';
            if (unReserveStates.length > 0) {
                for (const key in unReserveStates) {
                    htmlString += `<option value="${unReserveStates[key].id}">${unReserveStates[key].state_name}</option>`;
                }
            }
            $('#servicable_state_id').html(htmlString);
        }
    </script>
@endpush
@endsection
