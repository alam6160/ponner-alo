@extends('agent.layout.layout')
@section('title', 'Bank')
@section('content')
<div class="row">
    <div class="col-lg-11 col-md-11">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>Update Bank</h3>
                </div>
                <div class="tab-wrap">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="bank-tab" data-toggle="tab" href="#bankTab" role="tab" aria-controls="bank" aria-selected="false">Bank</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#otherTab" role="tab" aria-controls="profile" aria-selected="false">Other</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="bankTab" role="tabpanel" aria-labelledby="bank-tab">
                            <form action="" method="POST" id="bank_form">
                            <div class="row">
                                <div class="col-lg-6">
                                    <fieldset>
                                        <label for="bank_name">Bank Name <strong class="text-danger">*</strong></label>
                                        <input type="text" name="bank_name" id="bank_name" class="form-control cfcrl" required value="{{ empty($agentBank) ? '' : $agentBank->bank_name }}">
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <fieldset>
                                        <label for="account_no">Account Number <strong class="text-danger">*</strong></label>
                                        <input type="text" name="account_no" id="account_no" class="form-control cfcrl" required value="{{ empty($agentBank) ? '' : $agentBank->account_no }}" onkeypress="return onlyNumberKey(event)">
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <fieldset>
                                        <label for="account_holder">Account Holder <strong class="text-danger">*</strong></label>
                                        <input type="text" name="account_holder" id="account_holder" class="form-control cfcrl" required value="{{ empty($agentBank) ? '' : $agentBank->account_holder }}">
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <fieldset>
                                        <label for="ifsc_code">IFSC Code <strong class="text-danger">*</strong></label>
                                        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control cfcrl" required value="{{ empty($agentBank) ? '' : $agentBank->ifsc_code }}">
                                    </fieldset>
                                </div>
                                <div class="col-lg-4">
                                    <fieldset>
                                        <label for="swift_code">Swift Code </label>
                                        <input type="text" name="swift_code" id="swift_code" class="form-control cfcrl" value="{{ empty($agentBank) ? '' : $agentBank->swift_code }}">
                                    </fieldset>
                                </div>
                                <div class="col-lg-8">
                                    <fieldset>
                                        <label for="bank_remark">Remark </label>
                                        <input type="text" name="bank_remark" id="bank_remark" class="form-control cfcrl" value="{{ empty($agentBank) ? '' : $agentBank->bank_remark }}">
                                    </fieldset>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-md-4">
                                    <button type="submit" name="submit" class="btn-primary btn-block">Update</button>
                                </div>
                            </div>
                            
                            </form>
                        </div>
                        <div class="tab-pane fade" id="otherTab" role="tabpanel" aria-labelledby="profile-tab">
                            <form action="" method="post" id="other_form">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <label for="other_info">Other Info <strong class="text-danger">*</strong></label>
                                            <input type="text" name="other_info" id="other_info" class="form-control cfcrl" required value="{{ empty($agentBank) ? '' : $agentBank->other_info }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-12">
                                        <fieldset>
                                            <label for="other_remark">Remark </label>
                                            <input type="text" name="other_remark" id="other_remark" class="form-control cfcrl" value="{{ empty($agentBank) ? '' : $agentBank->other_remark }}">
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-md-4">
                                        <button type="submit" name="submit" class="btn-primary btn-block">Update</button>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#bank_form').parsley();
            $('#other_form').parsley();

            $('#bank_form').submit(function (e) { 
                e.preventDefault();
                if ($('#bank_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    formData.append('form_type', '1');
                    const formID = 'bank_form';

                    $.ajax({
                        type: "POST",
                        url: "{{ route('agent.profile.bank') }}",
                        data: formData,
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

            $('#other_form').submit(function (e) { 
                e.preventDefault();
                if ($('#other_form').parsley().isValid()) {
                    const formData = new FormData(this);
                    formData.append('form_type', '2');
                    const formID = 'other_form';

                    $.ajax({
                        type: "POST",
                        url: "{{ route('agent.profile.bank') }}",
                        data: formData,
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

        });
    </script>
@endpush
@endsection
