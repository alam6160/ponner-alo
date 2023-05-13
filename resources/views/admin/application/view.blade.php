@extends('admin.layout.layout')
@section('title', 'View Application')
@section('content')

@php
    $contact = (object) json_decode($userApplication->content, true);
@endphp
<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>View Application</h3>
                </div>
                <table class="table table-bordered">
                    <tbody>
                        <tr class="thead-light">
                            <th style="width: 25%" >Application No</th>
                            <th style="width: 25%" >Department</th>
                            <th style="width: 25%" >Application Date</th>
                            <th style="width: 25%" >Email</th>
                        </tr>
                        <tr>
                            <td>{{ $userApplication->appl_no }}</td>
                            <td>{{ strtoupper($userApplication->department) }}</td>
                            <td>{{ date('d-m-Y', strtotime($userApplication->created_at)) }}</td>
                            <td>{{ $userApplication->email }}</td>
                        </tr>
                        <tr class="thead-light">
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>State</th>
                            <th>Pincode</th>
                        </tr>
                        <tr>
                            <td>{{ $userApplication->title.' '.$userApplication->fname.' '.$userApplication->lname }}</td>
                            <td>{{ $userApplication->contact }}</td>
                            <td>{{ $userApplication->state->state_name }}</td>
                            <td>{{ $userApplication->pin_code }}</td>
                        </tr>
                        <tr class="thead-light">
                            <th colspan="2">Address</th>
                            <th>Organization Name</th>
                            <th>Licence</th>
                        </tr>
                        <tr>
                            <td colspan="2">{{ $userApplication->address }}</td>
                            <td>{{ $contact->organization_name }}</td>
                            <td>{{ $contact->licence }}</td>
                        </tr>
                        <tr class="thead-light">
                            <th colspan="2">Upload Files</th>
                            <th colspan="2">
                                @if ($userApplication->department == 'statehead')
                                Servicable State
                                @elseif ($userApplication->department == 'hsp')
                                Servicable Pincodes
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                @if (!empty($contact->brand_logo_file))
                                <a href="javascript:void(0)" data-path="{{ $contact->brand_logo_file }}" class="btn-link" onclick="viewImage(this)"><ins>Brand Logo</ins></a>&nbsp;&nbsp;&nbsp;
                                @endif
                                
                                @if (!empty($contact->gst_file))
                                <a href="javascript:void(0)" data-path="{{ $contact->gst_file }}" class="btn-link" onclick="viewImage(this)"><ins>GST</ins></a>&nbsp;&nbsp;&nbsp;
                                @endif

                                @if (!empty($contact->drug_licence_file))
                                <a href="javascript:void(0)" data-path="{{ $contact->drug_licence_file }}" class="btn-link" onclick="viewImage(this)"><ins>Drug Licence</ins></a>&nbsp;&nbsp;&nbsp;
                                @endif

                                @if (!empty($contact->aadhaar_file))
                                <a href="javascript:void(0)" data-path="{{ $contact->aadhaar_file }}" class="btn-link" onclick="viewImage(this)"><ins>Aadhaar</ins></a>&nbsp;&nbsp;&nbsp;
                                @endif

                            </td>
                            <td colspan="2">
                                @if ($userApplication->department == 'statehead')
                                {{ $userApplication->servicable_state->state_name }}
                                @elseif ($userApplication->department == 'hsp')
                                {{ $userApplication->servicable_pincodes }}
                                @endif
                            </td>
                        </tr>

                        
                        @if ($userApplication->status == 3)
                            <tr class="thead-light"><th colspan="4">Message</th></tr>
                            <tr><td colspan="4" class="text-danger">{{ $userApplication->cancel_msg }}</td></tr>
                        @endif
                    </tbody>
                </table>
                
                @if ($userApplication->status == 1)
                    <div id="formDiv">
                        @if ($userApplication->department == 'statehead')
                            @include('admin.application.stateheadform')
                        @elseif ($userApplication->department == 'hsp')
                            @include('admin.application.agentform')
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thumbnail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img class="img-thumbnail" src="" alt="" id="thumbnail" style="width: 500px; height:300px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cancelmodal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Application Cancel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="cancel_appl">
                <div class="modal-body">
                    <input type="hidden" name="user_application_id" value="{{ $userApplication->id }}">
                    <div class="form-group">
                        <label for="cancel_msg">Enter Your Message</label>
                        <textarea id="cancel_msg" class="form-control cfcrl" name="cancel_msg" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-primary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $("#servicable_pincodes").select2({
                placeholder: "Enter Pincodes",
                tags: true,
            });

            $('#cancelmodal').on('submit', '#cancel_appl', function (e) {
                e.preventDefault();
                const formId = 'cancel_appl';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.application.cancel') }}",
                    data: new FormData(this),
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('#'+formId+' [name=submit]').prop('disabled', true);
                        $('#'+formId+' [name=submit]').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                    },
                    success: function (response) {
                        $('#'+formId+' [name=submit]').prop('disabled', false);
                        $('#'+formId+' [name=submit]').text('Cancel');
                        if (response.type == 'success') {

                            const cancel_msg = (response.cancel_msg == null) ? '' : response.cancel_msg;
                            htmlString = `<tr class="thead-light"><th colspan="4">Message</th></tr>`;
                            htmlString += `<tr><td colspan="4" class="text-danger">${cancel_msg}</td></tr>`;
                            $('.table').find('tbody').append(htmlString);

                            $('#formDiv').hide();
                            $('#cancelmodal').modal('hide');
                            toastr.success(response.message);
                        } else {
                            setError(response.message);
                        }
                    }
                });
            });


            
            
            
        });
        function viewImage(evt) {
            path = evt.getAttribute("data-path");
            path = '{{ asset('') }}'+''+path;
            $('#thumbnail').attr('src', path);
            $('#modal-default').modal('show');
        }
    </script>
@endpush

@push('script')
    @if ($userApplication->department == 'statehead')
        @include('admin.application.stateheadjs')
    @elseif ($userApplication->department == 'hsp')
        @include('admin.application.agentjs')
    @endif
@endpush

@endsection
