@extends('admin.layout.layout')
@section('title', 'Withdrawl Request')
@section('content')
<div class="row">
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
                                        <th>Req No</th>
                                        <th>Request At</th>
                                        <th>Amount</th>
                                        <th>Received By</th>
                                        <th>Status</th>
                                        <th>Vendor Name</th>
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
            <div class="modal-body" id="defaultmodal-body"></div>
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
                url : '{{ route('admin.payout.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false},
                {data: 'req_no', name: 'req_no'},
                {data: 'created_at', name: 'created_at'},
                {data: 'amount', name: 'amount'},
                {data: 'receive_by', name: 'receive_by'},
                {data: 'status', name: 'status'},
                {data: 'agent_name', name: 'agent_name'},
                
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
</script>
@endpush
@endsection
