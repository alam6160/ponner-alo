@extends('agent.layout.layout')
@section('title', 'Withdrawl')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>Withdrawl Request</h3>
                </div>
                <form class="filter-wrap row" action="" method="POST" id="payout_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">
                        <fieldset class="col-lg-4">
                            <label for="addon_price">Amount 
                                <strong class="text-danger">*</strong>
                                <small>(Wallet : <span id="maxamount">{{ Auth::user()->wallet }}</span> )</small>
                            </label>
                            <input type="text" name="amount" id="amount" class="form-control" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off" min="0" max="{{ Auth::user()->wallet }}">
                        </fieldset>
                        <fieldset class="select-box col-lg-4">
                            <label>Received by</label>
                            <select class="form-control" name="receive_by" id="receive_by">
                                <option value="1">Bank </option>
                                <option value="2">Other</option>
                            </select>
                        </fieldset>
                        <fieldset class="col-lg-3">
                            <label style="margin-bottom: 5px">&nbsp;</label>
                            <button type="submit" name="submit" class="btn-block btn-dark">Submit</button>
                        </fieldset>
                    </div>

                    
                        
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">
                        All Request
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Req. No</th>
                                        <th>Request At</th>
                                        <th>Amount</th>
                                        <th>Received By</th>
                                        <th>Status</th>
                                        <th>Verification At</th>
                                        
                                        <th style="width: 15%"></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="defaultmodal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" id="defaultmodal-size">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="defaultmodal-title"></h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="defaultmodal-body">
                
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#payout_form').parsley();

            var dataTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                searching: false,
                lengthChange: false,
                responsive: true,
                pageLength: 10,
                // scrollX: true,
                "order": [[ 0, "desc" ]],
                ajax: {
                    url : '{{ route('agent.payout.index') }}',
                    type: "GET",
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'req_no', name: 'req_no'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'amount', name: 'amount'},
                    {data: 'receive_by', name: 'receive_by'},
                    {data: 'status', name: 'status'},
                    {data: 'verification_at', name: 'verification_at'},
                    
                    {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
                ]
            });

            $('#payout_form').submit(function (e) { 
                e.preventDefault();
                if ($('#payout_form').parsley().isValid()) {
                    const formID = 'payout_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('agent.payout.request') }}",
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
                                $('#maxamount').text(response.maxamount);
                                $('#dataTable').DataTable().ajax.reload();
                            } else {
                                setRestButton(formID, 'submit');
                                setError(response.message);
                            }
                        }
                    });
                }
            });
        });

        function viewAccountDesc(evt) {
            const accountdesc = JSON.parse( evt.getAttribute('data-accountdesc') );
            //const accountdesc = evt.getAttribute('data-accountdesc');
            let htmlString = `
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>`;
                        if (jQuery.isEmptyObject(accountdesc) == false) {
                            for (let key in accountdesc) {
                                let element = accountdesc[key];
                                label = key.replace('_', ' ');
                                const newStr = `${label[0].toUpperCase()}${label.slice(1)}`;
                                htmlString += `<tr><th>${newStr}</th><td>${element == null ? '' : element}</td></tr>`;
                            }
                        }else{
                            htmlString += `<tr><td>No Data</td></tr>`;
                        }
                    htmlString +=`
                    </tbody>
                </table>
            </div>`;

            $('#defaultmodal-title').text('Account Details');
            $('#defaultmodal-body').html(htmlString);
            $('#defaultmodal').modal('show');
        }
        function cancelView(evt) {
            const accountdesc = evt.getAttribute('data-cancelremark');
            const htmlString = `<textarea class="form-control" cols="30" rows="10" disabled>${accountdesc}</textarea>`;
            $('#defaultmodal-title').text('Admin Review');
            $('#defaultmodal-body').html(htmlString);
            $('#defaultmodal').modal('show');
        }
    </script>
@endpush
@endsection
