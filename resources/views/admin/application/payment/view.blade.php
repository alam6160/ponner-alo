@extends('admin.layout.layout')
@section('title', 'View Request')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>View Request</h3>
                </div>
                <table class="table table-bordered">
                    <tbody>
                        <tr class="thead-light">
                            <th style="width: 25%" >Reqest No</th>
                            <th style="width: 25%" >Department</th>
                            <th style="width: 25%" >Reqest Date</th>
                        </tr>
                        <tr>
                            <td>{{ $applicationPayment->req_no }}</td>
                            <td>{{ strtoupper($applicationPayment->department) }}</td>
                            <td>{{ date('d-m-Y', strtotime($applicationPayment->created_at)) }}</td>
                        </tr>
                        <tr class="thead-light">
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                        </tr>
                        <tr>
                            <td>{{ $applicationPayment->user->title.' '.$applicationPayment->user->fname.' '.$applicationPayment->user->lname }}</td>
                            <td>{{ $applicationPayment->user->contact }}</td>
                            <td>{{ $applicationPayment->user->email }}</td>
                        </tr>
                        <tr class="thead-light">
                            <th>Payable Amount</th>
                            <th>Payment Mode</th>
                            <th>Payment Proof </th>
                        </tr>
                        <tr>
                            <td>&#8377;{{ $applicationPayment->payable_amount }}</td>
                            <td>{{  Helper::getPaymentMode($applicationPayment->payment_mode) }}</td>
                            <td>
                                @if ($applicationPayment->payment_proof)
                                    <img class="img-thumbnail" src="{{ asset($applicationPayment->payment_proof) }}" alt="" style="width: 40px; height: 40px;" onclick="viewImage()">
                                @endif
                            </td>
                        </tr>
                        <tr class="thead-light">
                            <th colspan="3">Payment Message</th>
                        </tr>
                        <tr>
                            <td colspan="3">{{ $applicationPayment->remark }}</td>
                        </tr>
                        @if ($applicationPayment->status == '3')
                            <tr class="thead-light"><th colspan="4">Message</th></tr>
                            <tr><td colspan="4" class="text-danger">{{ $applicationPayment->cancel_remark }}</td></tr>
                        @endif
                    </tbody>
                </table>

                @if ($applicationPayment->status == '1')
                <div class="row" id="formDiv">
                    <fieldset class="col-lg-3">
                        <button type="button" id="approve" class="btn-block btn-primary">Approve</button>
                    </fieldset>
                    <fieldset class="col-lg-3">
                        <button type="button" id="cancel" data-toggle="modal" data-target="#cancelmodal" class="btn-block btn-primary">Cancel</button>
                    </fieldset>
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
                <h4 class="modal-title">Request Cancel</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="cancel_appl">
                <div class="modal-body">
                    <input type="hidden" name="application_payment_id" value="{{ $applicationPayment->id }}">
                    <div class="form-group">
                        <label for="cancel_remark">Enter Your Message</label>
                        <textarea id="cancel_remark" class="form-control cfcrl" name="cancel_remark" rows="3"></textarea>
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
                    url: "{{ route('admin.application.payment.cancel') }}",
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

                            const cancel_remark = (response.cancel_remark == null) ? '' : response.cancel_remark;
                            htmlString = `<tr class="thead-light"><th colspan="4">Message</th></tr>`;
                            htmlString += `<tr><td colspan="4" class="text-danger">${cancel_remark}</td></tr>`;
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

            $('#approve').click(function (e) { 
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.application.payment.approve') }}",
                    data: {application_payment_id: '{{ $applicationPayment->id }}'},
                    dataType: "JSON",
                    beforeSend: function(){
                        $('#approve').prop('disabled', true);
                        $('#approve').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                        $('#cancel').prop('disabled', true);
                    },
                    success: function (response) {
                        $('#approve').prop('disabled', false);
                        $('#cancel').prop('disabled', false);
                        $('#approve').text('Approve');
                        if (response.type == 'success') {
                            $('#formDiv').hide();
                            toastr.success(response.message);
                        } else {
                            setError(response.message);
                        }
                    }
                });
            });

        });
        function viewImage() {
            const path = $(event.target).attr('src');
            $('#thumbnail').attr('src', path);
            $('#modal-default').modal('show');
        }
    </script>
@endpush

@endsection
