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
                <form class="filter-wrap row" action="" method="POST" id="slider_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label>Upload Thumbanil 
                                        @empty($slider)
                                            <strong class="text-danger">*</strong>
                                        @endempty
                                        <small>(only jpg,jpeg,png or maximum file size 2MB)</small>
                                    </label>
                                    <div class="custom-file">
                                        <input type="file" name="thumnanail" class="custom-file-input form-control" id="thumnanail" accept=".png,.jpeg,.jpg" {{ empty($slider) ? 'required' : '' }} data-parsley-errors-container="#thumnanail_error" >
                                        <label class="custom-file-label" for="thumnanail">Choose file</label>
                                    </div>
                                </div>
                                <div id="thumnanail_error"></div>

                                <div id="thumnanailDiv">
                                    @if (!empty($slider->thumnanail))
                                        <a href="javascript:void(0)" data-img="{{ asset($slider->thumnanail) }}" onclick="viewImage(this)">View file</a>
                                    @endif
                                </div>
                            </fieldset>
                        </div>

                        <fieldset class="col-lg-6">
                            <label for="title">Title </label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ empty($slider) ? '' : $slider->title }}">
                        </fieldset>

                        <fieldset class="col-lg-5">
                            <label for="sub_title">Sub Title</label>
                            <input type="text" name="sub_title" id="sub_title" class="form-control" value="{{ empty($slider) ? '' : $slider->sub_title }}">
                        </fieldset>

                        <fieldset class="col-lg-3">
                            <label for="caption">Button Caption </label>
                            <input type="text" name="caption" id="caption" class="form-control" value="{{ empty($slider) ? '' : $slider->caption }}">
                        </fieldset>

                        <fieldset class="col-lg-4">
                            <label for="link">Link </label>
                            <input type="url" name="link" id="link" class="form-control" value="{{ empty($slider) ? '' : $slider->link }}">
                        </fieldset>

                    </div>

                    <fieldset class="col-lg-4 offset-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($slider) ? 'Submit' : 'Update' }}</button>
                    </fieldset>

                    <div class="col-md-6">
                        @if (!empty($slider))
                            <div id="thumnanailDiv1" class="mb-4">
                                @if (!empty($slider->thumnanail))
                                    <img src="{{ asset($slider->thumnanail) }}" alt="" style="height: 200px;  width: 400px; ">
                                @endif
                            </div>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">
                        All Sliders
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Thumbnail</th>
                                        <th>Title</th>
                                        <th>Sub Title</th>
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
        
        $('#slider_form').parsley();

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
                url : '{{ route('admin.home.slider.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'thumnanail', name: 'thumnanail'},
                {data: 'title', name: 'title'},
                {data: 'sub_title', name: 'sub_title'},
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
                        url: "{{ route('admin.ajax.changestatus.slider') }}",
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
                toastr.error('ID or Status is required');
            }
        });

        @if (empty($slider))
        $('#slider_form').submit(function (e) { 
            e.preventDefault();
            if ($('#slider_form').parsley().isValid()) {
                const formID = 'slider_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.slider.create') }}",
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
        $('#slider_form').submit(function (e) { 
            e.preventDefault();
            if ($('#slider_form').parsley().isValid()) {
                const formID = 'slider_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.slider.edit', ['id'=>$slider->id]) }}",
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

                            const files = response.files;
                            const thumnanail = files.thumnanail;
                            if (thumnanail != null) {
                                let htmlString = `<a href="javascript:void(0)" data-img="${thumnanail}" onclick="viewImage(this)">View file</a>`;
                                $('#thumnanailDiv').html(htmlString);

                                let htmlString1 = `<img src="${thumnanail}" alt="" style="height: 200px;  width: 400px; ">`;
                                $('#thumnanailDiv1').html(htmlString1);
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
