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
                <form class="filter-wrap row" action="" method="POST" id="addon_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">
                        <fieldset class="col-lg-6">
                            <label for="addon_title">Addon Name <strong class="text-danger">*</strong></label>
                            <input type="text" name="addon_title" id="addon_title" class="form-control" value="{{ empty($productAddon) ? '' : $productAddon->addon_title }}" required>
                        </fieldset>

                        <fieldset class="col-lg-6">
                            <label for="addon_price">Price <strong class="text-danger">*</strong></label>
                            <input type="text" name="addon_price" id="addon_price" class="form-control" value="{{ empty($productAddon) ? '' : $productAddon->addon_price }}" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                        </fieldset>
                        <div class="col-lg-12">
                            <fieldset>
                                <div class="form-group">
                                    <label for="addon_desc">Description</label>
                                    <textarea name="addon_desc" id="addon_desc" class="form-control" rows="1">{{ empty($productAddon) ? '' : $productAddon->addon_desc }}</textarea>
                                </div>
                            </fieldset>
                        </div>

                        

                    </div>

                    <fieldset class="col-lg-4 offset-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($productAddon) ? 'Submit' : 'Update' }}</button>
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
                        All Addons
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Addon Title</th>
                                        <th>Addon Price</th>
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
        $('#addon_form').parsley();

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
                url : '{{ route('admin.product.addon.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'addon_title', name: 'addon_title'},
                {data: 'addon_price', name: 'addon_price'},
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
                        url: "{{ route('admin.ajax.changestatus.productaddon') }}",
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

        @if (empty($productAddon))
        $('#addon_form').submit(function (e) { 
            e.preventDefault();
            if ($('#addon_form').parsley().isValid()) {
                const formID = 'addon_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.addon.create') }}",
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
                            $('#dataTable').DataTable().ajax.reload();
                        } else {
                            setRestButton(formID, 'submit');
                            setError(response.message);
                        }
                    }
                });
            }
        });
        @else
        $('#addon_form').submit(function (e) { 
            e.preventDefault();
            if ($('#addon_form').parsley().isValid()) {
                const formID = 'addon_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.addon.edit', ['id'=>$productAddon->id]) }}",
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
                            $('#dataTable').DataTable().ajax.reload();
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
</script>
@endpush
@endsection
