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
                <form class="filter-wrap row" action="" method="POST" id="filter_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">

                        @php
                            $parent_id = (empty($productFilter)) ? '' : $productFilter->parent_id;
                        @endphp
                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="parent_id">Select Filter</label>
                                    <select class="form-control" id="parent_id" name="parent_id" onchange="changeFitlterTitle()">
                                        <option value="">None</option>
                                        @foreach ($parentFilters as $var)
                                            <option value="{{ $var->id }}" {{ $parent_id == $var->id ? 'selected' : '' }}>
                                                {{ $var->filter_title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <fieldset class="col-lg-6">
                            <label for="filter_title"><span id="filter_txt">Filter Name</span> <strong class="text-danger">*</strong></label>
                            <input type="text" name="filter_title" id="filter_title" class="form-control" value="{{ empty($productFilter) ? '' : $productFilter->filter_title }}" required>
                        </fieldset>

                    </div>

                    <fieldset class="col-lg-4 offset-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($productFilter) ? 'Submit' : 'Update' }}</button>
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
                                        <th>Filter/Value</th>
                                        <th>Parent Filter</th>
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
        $('#filter_form').parsley();
        $('#slug').slugify('#cat_title');
        $("#filter_values").select2({
            placeholder: "Enter Values",
            tags: true,
        });

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
                url : '{{ route('admin.product.filter.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',serachable:false},
                {data: 'filter_title', name: 'filter_title'},
                {data: 'filter_values', name: 'filter_values',serachable:false},
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
                        url: "{{ route('admin.ajax.changestatus.productfilter') }}",
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

        @if (empty($productFilter))
        $('#filter_form').submit(function (e) { 
            e.preventDefault();
            if ($('#filter_form').parsley().isValid()) {
                const formID = 'filter_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.filter.create') }}",
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
                            updateParentFilter(response.parent_filters);
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
        $('#filter_form').submit(function (e) { 
            e.preventDefault();
            if ($('#filter_form').parsley().isValid()) {
                const formID = 'filter_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.filter.edit', ['id'=>$productFilter->id]) }}",
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
                            updateParentFilter(response.parent_filters);
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

    function changeFitlterTitle() {
        const parent_id = $('#parent_id option:selected').val();
        if (parent_id == '') {
            $('#filter_txt').text('Filter Name');
        } else {
            $('#filter_txt').text('Filter Value');
        }
    }
    function updateParentFilter(params) {
        if (params.length > 0) {
            let htmlString = '<option value="">None</option>';
            for (const key in params) {
                htmlString += `<option value="${params[key].id}">${params[key].filter_title}</option>`;
            }
            $('#parent_id').html(htmlString);
        }
    }
</script>
@endpush
@endsection
