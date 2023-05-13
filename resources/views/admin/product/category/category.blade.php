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
                <form class="filter-wrap row" action="" method="POST" id="category_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">
                        <fieldset class="col-lg-6">
                            <label for="cat_title">Category Name <strong class="text-danger">*</strong> </label>
                            <input type="text" name="cat_title" id="cat_title" class="form-control" value="{{ empty($category) ? '' : $category->cat_title }}" required>
                        </fieldset>

                        <fieldset class="col-lg-6">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ empty($category) ? '' : $category->slug }}" readonly>
                        </fieldset>

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label>Brand Logo <small>(only jpg,jpeg,png,webp or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="logo" class="custom-file-input form-control" id="logo" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="logo">Choose file</label>
                                    </div>
                                </div>
                                <div id="brand_logoDiv">
                                    @if (!empty($category->logo))
                                        <a href="javascript:void(0)" data-img="{{ asset($category->logo) }}" onclick="viewImage(this)">View file</a>
                                    @endif
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label>Thumbnail <small>(only jpg,jpeg,png,webp or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="thumbnail" class="custom-file-input form-control" id="thumbnail" accept=".png,.jpeg,.jpg,webp">
                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                    </div>
                                </div>
                                <div id="thumbnailDiv">
                                    @if (!empty($category->thumbnail))
                                        <a href="javascript:void(0)" data-img="{{ asset($category->thumbnail) }}" onclick="viewImage(this)">View file</a>
                                    @endif
                                </div>
                            </fieldset>
                        </div>

                        @php
                            $parent_id = (empty($category)) ? '' : $category->parent_id ;
                        @endphp
                        <fieldset class="select-box col-lg-6">
                            <label>Select Parent Category</label>
                            <select class="form-control" name="parent_id" id="parent_id">
                                <option value="">None</option>
                                @foreach ($parent_categories as $var)
                                <option value="{{ $var->id }}" {{ ($var->id == $parent_id) ? 'selected' : '' }} >
                                    {{ $var->cat_title }}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>

                        
                        @php
                            $product_filter_id = [];
                            if (!empty($category)) {
                                if (!empty($category->product_filter_id)) {
                                    $product_filter_id = json_decode($category->product_filter_id);
                                }
                            }
                            
                        @endphp
                        <fieldset class="select-box col-lg-12">
                            <label>Select Filter</label>
                            <select class="form-control" name="product_filter_id[]" id="product_filter_id" multiple>
                                @foreach ($parentFilters as $var)
                                <option value="{{ $var->id }}"  {{ in_array($var->id, $product_filter_id) ? 'selected' : '' }}>
                                    {{ $var->filter_title }}
                                </option>
                                @endforeach
                            </select>
                        </fieldset>

                    </div>

                    <fieldset class="col-lg-4 offset-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($category) ? 'Submit' : 'Update' }}</button>
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
                        All Categories
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category Title</th>
                                        <th>Parent Category</th>
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
    <div class="modal-dialog" role="document" id="defaultmodal-size">
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
        $('#slug').slugify('#cat_title');
        $('#category_form').parsley();
        $('#product_filter_id').select2();

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
                url : '{{ route('admin.product.category.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',serachable:false},
                {data: 'cat_title', name: 'cat_title'},
                {data: 'parent_cat', name: 'parent_cat',serachable:false},
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
                        url: "{{ route('admin.ajax.changestatus.category') }}",
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

        @if (empty($category))
        $('#category_form').submit(function (e) { 
            e.preventDefault();
            if ($('#category_form').parsley().isValid()) {
                const formID = 'category_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.category.create') }}",
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
                            updateCategory(response.parent_categories);
                            $('#product_filter_id').trigger('change');
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
        $('#category_form').submit(function (e) { 
            e.preventDefault();
            if ($('#category_form').parsley().isValid()) {
                const formID = 'category_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.category.edit', ['id'=>$category->id]) }}",
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
                            updateCategory(response.parent_categories);
                            $('#dataTable').DataTable().ajax.reload();

                            const files = response.files;
                            const logo = files.logo;
                            const thumbnail = files.thumbnail;
                            if (logo != null) {
                                let htmlString = `<a href="javascript:void(0)" data-img="${logo}" onclick="viewImage(this)">View file</a>`;
                                $('#brand_logoDiv').html(htmlString);
                            }
                            if (thumbnail != null) {
                                let htmlString = `<a href="javascript:void(0)" data-img="${thumbnail}" onclick="viewImage(this)">View file</a>`;
                                $('#thumbnailDiv').html(htmlString);
                            }

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

    function updateCategory(params) {
        if (params.length > 0) {
            let htmlString = '<option value="">None</option>';
            for (const key in params) {
                htmlString += `<option value="${params[key].id}">${params[key].cat_title}</option>`;
            }
            $('#parent_id').html(htmlString);
        }
    }

    function viewImage(evt) {
        const path = evt.getAttribute('data-img');

        let htmlString = `<img class="img-thumbnail" src="${path}" alt="" id="thumbnail" style="width: 500px; height:300px;">`;
        $('#defaultmodal-body').html(htmlString);
        $('#defaultmodal-title').text('Thumbnail');
        $('#defaultmodal').modal('show');
    }
</script>
@endpush
@endsection
