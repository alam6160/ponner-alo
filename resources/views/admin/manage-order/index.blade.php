@extends('admin.layout.layout')
@section('title', $title)
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">{{ $title }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Order Status</button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($orderstatus as $var)
                                        <a class="dropdown-item" href="{{ $var['url'] }}">{{ $var['label'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        @foreach ($table as $var)
                                            <th>{{ $var }}</th>
                                        @endforeach
                                        <th style="width: 5%"></th>
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

@php $tableKeys = $table->keys(); @endphp

@push('script')


<script>
    $(function(){

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
            ajax: '{{ $indexURL }}',
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false},

                @foreach ($tableKeys as $var)
                {data: '{{ $var }}', name: '{{ $var }}'},
                @endforeach
                
                {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });

        $('#dataTable').on('click', '.btn-status',function () {
            event.preventDefault();

            var status = $(this).attr('data-status');
            var id = $(this).attr('data-row');

            if (status !=='' && id !=='') {

                if (status == '1') {
                    confirmButtonText = 'Yes, Do it!';
                }else if(status == '2'){
                    confirmButtonText = 'Yes, Processed it!';
                }else if(status == '3'){
                    confirmButtonText = 'Yes, Shipped it!';
                }else if(status == '4'){
                    confirmButtonText = 'Yes, Delivered it!';
                }
                
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: confirmButtonText,
                    closeOnConfirm: false
                },
                function(){
                    swal.close();
                    form_data = {status:status, id:id };
                    $.ajax({
                        type: "POST",
                        url: "{{ $statusURL }}",
                        data: form_data,
                        dataType: "JSON",
                        beforeSend: function(){
                            $('#orderstatus_'+id).prop('disabled', true);
                            $('#orderstatus_'+id).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
                            $('#orderstatus_'+id).html('Processing');
                        },
                        success: function (response) {
                            if (response.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                $('#dataTable').DataTable().ajax.reload();
                                setError(response.message);
                            }
                        }
                    });
                });

            }else{
                toastr.error('ID or Status is required');
            }
        });
    });

    function cancelOrder(evt) {
        id = evt.getAttribute('data-row');
        const htmlString = `
        <form action="" method="post" id="cancel_form" onsubmit="submitCancelOrder()">
            <input type="hidden" name="order_id" value="${id}">
            <div class="form-group">
                <label for="cancel_remark">Enter Message</label>
                <textarea id="cancel_remark" class="form-control cfcrl" name="cancel_remark" rows="2" required></textarea>
            </div>
            <button type="submit" name="submit" id="submitCancel" class="btn btn-danger">Cancel</button>
        </form>
        `;
        $('#defaultmodal-body').html(htmlString);
        $('#defaultmodal').modal('show');
    }

    function submitCancelOrder() {
        event.preventDefault();
        const formData = new FormData( $('#cancel_form')[0] );
        //console.log(formData);
        const formId = 'submitCancel';
        $.ajax({
            type: "POST",
            url: "{{ route('admin.manageorder.cancelorder') }}",
            data: formData,
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $('#'+formId).prop('disabled', true);
                $('#'+formId).html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
            },
            success: function (response) {
                $('#'+formId).prop('disabled', false);
                $('#'+formId).text('Cancel');
                if (response.type == 'success') {
                    $('#defaultmodal').modal('hide');
                    $('#dataTable').DataTable().ajax.reload();
                    toastr.success(response.message);
                } else {
                    setError(response.message);
                }
            }
        });
    }

    function chageReturnStatus(evt) {
        val = JSON.parse(evt.getAttribute('data-info'));
        console.log(val);
        //console.log();
        if (jQuery.isEmptyObject(val) == false) {

            if (val.type == '1') {
                confirmButtonText = 'Yes, Approved it!';
                buttonid = '#appreturnstatus_'+val.id;
                label = 'Approved';
            }else if(val.type == '2'){
                confirmButtonText = 'Yes, Reject it!';
                buttonid = '#rejreturnstatus_'+val.id;
                label = 'Reject';
            }

            swal({
                title: "Are you sure?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: confirmButtonText,
                closeOnConfirm: false
            },
            function(){
                swal.close();

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.manageorder.chagereturnstatus') }}",
                    data: val,
                    dataType: "JSON",
                    beforeSend: function(){
                        $(buttonid).prop('disabled', true);
                        //$(buttonid).html('<i class="fa fa-circle-o-notch fa-spin"></i>');
                        $(buttonid).text('Processing');
                    },
                    success: function (response) {
                        if (response.type == 'success') {
                            $('#dataTable').DataTable().ajax.reload();
                            toastr.success(response.message);
                        } else {
                            $(buttonid).prop('disabled', false);
                            $(buttonid).text(label);
                            setError(response.message);
                        }
                    }
                });
            });
        }
    }
</script>

@endpush
@endsection
