@extends('agent.layout.layout')
@section('title', 'All Products')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">All Products</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Product Type</th>
                                        <th>SKU Code</th>
                                        <th>Short Desc</th>
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

@push('script')
<script>
    $(function(){

    
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
			searching: true,
			lengthChange: false,
			responsive: true,
            pageLength: 10,
            // scrollX: true,
            "order": [[ 0, "desc" ]],
            ajax: {
                url : '{{ route('agent.product.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',serachable:false},
                {data: 'name', name: 'name'},
                {data: 'product_type', name: 'product_type',serachable:false},
                {data: 'sku', name: 'sku',serachable:false},
                {data: 'short_desc', name: 'short_desc',serachable:false},
                {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });

        $('#dataTable').on('click', '.btn-delete', function () {
            event.preventDefault();

            var status = $(this).attr('data-status');
            var id = $(this).attr('data-row');

            if (status !=='' && id !=='') {

                let confirmButtonText = '';
                if (status == '2') {
                    confirmButtonText = 'Yes, delete it!';
                } else {
                    confirmButtonText = 'Yes, restore it!';
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
                        url: "{{ route('agent.ajax.changestatus.product') }}",
                        data: form_data,
                        dataType: "JSON",
                        beforeSend: function(){
                            
                        },
                        success: function (response) {
                            if (response.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                setError(response.message);
                            }
                        }
                    });
                });
                
            }else{
                toastr.error('Agent ID or Status is required');
            }
        });
    });
</script>

@endpush
@endsection
