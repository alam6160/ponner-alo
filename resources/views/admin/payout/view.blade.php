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
                            <th style="width: 25%" >Amount</th>
                            <th style="width: 25%" >Reqest Date</th>
                        </tr>
                        <tr>
                            <td>{{ $agentPayout->req_no }}</td>
                            <td>{{ $agentPayout->amount }}</td>
                            <td>{{ date('d-m-Y', strtotime($agentPayout->created_at)) }}</td>
                        </tr>
                        <tr class="thead-light">
                            <th>Full Name</th>
                            <th>Contact</th>
                            <th>Email</th>
                        </tr>
                        <tr>
                            <td>{{ $agentPayout->agent->title.' '.$agentPayout->agent->fname.' '.$agentPayout->agent->lname }}</td>
                            <td>{{ $agentPayout->agent->contact }}</td>
                            <td>{{ $agentPayout->agent->email }}</td>
                        </tr>


                        @if ($agentPayout->receive_by == '1')
                            @php
                                $account_desc = json_decode($agentPayout->account_desc);
                            @endphp
                            <tr class="thead-light">
                                <th>Bank Name</th>
                                <th>Account No</th>
                                <th>IFSC Code</th>
                            </tr>
                            <tr>
                                <td>{{ $account_desc->bank_name }}</td>
                                <td>{{ $account_desc->account_no }}</td>
                                <td>{{ $account_desc->ifsc_code }}</td>
                            </tr>

                            <tr class="thead-light">
                                <th colspan="2">Account Holder</th>
                                <th>Swift Code</th>
                            </tr>
                            <tr>
                                <td colspan="2">{{ $account_desc->account_holder }}</td>
                                <td>{{ $account_desc->swift_code }}</td>
                            </tr>
                            <tr class="thead-light">
                                <th colspan="3">Instruction</th>
                            </tr>
                            <tr>
                                <td colspan="3">{{ $account_desc->bank_remark }}</td>
                            </tr>
                        @elseif ($agentPayout->receive_by == '2')
                            @php
                                $account_desc = json_decode($agentPayout->account_desc);
                            @endphp
                            <tr class="thead-light">
                                <th colspan="3">Other Info</th>
                            </tr>
                            <tr>
                                <td colspan="3">{{ $account_desc->other_info }}</td>
                            </tr>
                            <tr class="thead-light">
                                <th colspan="3">Instruction</th>
                            </tr>
                            <tr>
                                <td colspan="3">{{ $account_desc->other_remark }}</td>
                            </tr>
                        @endif
                        

                        @if ($agentPayout->status == '3')
                            <tr class="thead-light"><th colspan="4">Message</th></tr>
                            <tr><td colspan="4" class="text-danger">{{ $agentPayout->cancel_remark }}</td></tr>
                        @endif
                    </tbody>
                </table>

                @if ($agentPayout->status == '1')
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
                    <input type="hidden" name="agent_payout_id" value="{{ $agentPayout->id }}">
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
            

            $('#cancelmodal').on('submit', '#cancel_appl', function (e) {
                e.preventDefault();
                const formId = 'cancel_appl';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.payout.cancel') }}",
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
                    url: "{{ route('admin.payout.approve') }}",
                    data: {agent_payout_id: '{{ $agentPayout->id }}'},
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
    </script>
@endpush

@endsection
