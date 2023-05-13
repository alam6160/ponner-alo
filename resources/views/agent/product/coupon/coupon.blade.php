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
                <form class="filter-wrap row" action="" method="POST" id="coupon_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">
                        <fieldset class="col-lg-6">
                            <label for="code">Coupon code <strong class="text-danger">*</strong> </label>
                            <input type="text" name="code" id="code" class="form-control" value="{{ empty($coupon) ? '' : $coupon->code }}" required>
                        </fieldset>
                        @php
                            $discount_type = (empty($coupon)) ? '' : $coupon->discount_type ;
                        @endphp
                        <fieldset class="select-box col-lg-6">
                            <label for="discount_type">Discount Type</label>
                            <select class="form-control" name="discount_type" id="discount_type">
                                <option value="1" {{ $discount_type == '1' ? 'selected' : '' }}>Flat Discount</option>
                                <option value="2" {{ $discount_type == '2' ? 'selected' : '' }}>Percentage Discount</option>
                            </select>
                        </fieldset>
                        <fieldset class="col-lg-6">
                            <label for="discount">Discount <strong class="text-danger">*</strong> </label>
                            <input type="text" name="discount" id="discount" class="form-control" required autocomplete="off" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" data-parsley-type="digits" value="{{ empty($coupon) ? '' : $coupon->discount }}">
                        </fieldset>
                        <fieldset class="col-lg-6">
                            <label for="expire_date">Expire Date  </label>
                            <input type="date" name="expire_date" id="expire_date" class="form-control" value="{{ empty($coupon) ? '' : $coupon->expire_date }}">
                        </fieldset>

                        @php
                            $product_ids = (empty($coupon)) ? [] : explode(',', $coupon->product_ids) ;
                        @endphp
                        <div class="col-lg-12">
                            <fieldset class="select-box">
                                <label for="product_ids">Products</label>
                                <select class="form-control" id="product_ids" name="product_ids[]" multiple data-parsley-errors-container="#product_ids_error">
                                    
                                    @foreach ($allproducts as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, $product_ids) ? 'selected' : '' }} >
                                            {{ $product->name.' ('. $product->sku .')'; }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="product_ids_error"></div>
                            </fieldset>
                        </div>
                    </div>

                    <fieldset class="col-lg-4 offset-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($coupon) ? 'Submit' : 'Update' }}</button>
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
                        All Coupons
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Discount Type</th>
                                        <th>Discount</th>
                                        <th>Expire Date</th>
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
        
        $('#coupon_form').parsley();
        
        $('#product_ids').select2({
            ajax: {
                url: "{{ route('admin.ajax.searchproducts') }}",
                type: "post",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    return {
                        query: params.term, // search term
                        "product_ids": "{{ empty($coupon) ? '' : $coupon->product_ids }}",
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },
                cache: true
            }
        });

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
                url : '{{ route('admin.product.coupon.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'code', name: 'code'},
                {data: 'discount_type', name: 'discount_type'},
                {data: 'discount', name: 'discount'},
                {data: 'expire_date', name: 'expire_date'},
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
                        url: "{{ route('admin.ajax.changestatus.coupon') }}",
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

        @if (empty($coupon))
        $('#coupon_form').submit(function (e) { 
            e.preventDefault();
            if ($('#coupon_form').parsley().isValid()) {
                const formID = 'coupon_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.coupon.create') }}",
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
                            $("#product_ids").val('').trigger('change');
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
        $('#coupon_form').submit(function (e) { 
            e.preventDefault();
            if ($('#coupon_form').parsley().isValid()) {
                const formID = 'coupon_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.coupon.edit', ['id'=>$coupon->id]) }}",
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
