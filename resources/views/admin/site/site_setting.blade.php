@extends('admin.layout.layout')
@section('title', 'System Setting')
@section('content')
<div class="row">
    <div class="col-lg-12">

        <div class="content-wrap">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>System Setting</h3>
                </div>
                <form class="filter-wrap row" action="" method="POST" id="setting_form" autocomplete="off">
                    <div class="col-lg-12 row">

                        @foreach ($sitesettings as $item)
                            <fieldset class="col-lg-4">
                                <label for="{{ $item->key_name }}" >{{ ucwords(str_replace('_', ' ', $item->key_name)) }} <strong class="text-danger">*</strong> </label>
                                <input type="text" name="{{ $item->key_name }}" id="{{ $item->key_name }}" class="form-control" value="{{ $item->key_value }}" required>
                            </fieldset>
                        @endforeach
                    </div>

                    <fieldset class="col-lg-4 offset-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">Update</button>
                    </fieldset>
                </form>
            </div>

            <div class="form-wrap">
                <form action="" method="POST" class="filter-wrap" enctype="multipart/form-data" id="upload_logo_form">
                    <div class="row">
                        <div class="col-md-8">
                            <fieldset>
                                <div class="form-group">
                                    <label for="logo_file">Upload Logo <small>(only png or maximum file size 1MB & Width: 386pixels & Height: 99 pixels)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="logo_file" class="custom-file-input form-control" id="logo_file" accept=".png" required>
                                        <label class="custom-file-label" for="logo_file">Choose file</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-4">
                            <label>&nbsp;</label>
                            <button type="submit" name="submit" class="btn-block btn-dark">Upload</button>
                        </div>
                        <div class="col-md-4">
                            <img class="img-fluid" src="{{ Helper::frontendLogo() }}" alt="">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){

            $('#setting_form').parsley();
            $('#upload_logo_form').parsley();


            $('#setting_form').submit(function (e) { 
                e.preventDefault();
                if ($('#setting_form').parsley().isValid()) {
                    const formID = 'setting_form';
                    formData = new FormData(this);
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.home.sitesetting') }}",
                        data: formData,
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

            $('#upload_logo_form').submit(function (e) { 
                e.preventDefault();
                const formID = 'upload_logo_form';
                formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.uploadlogo') }}",
                    data: formData,
                    cache: false,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    beforeSend: function(){
                        setUpdateButton(formID);
                    },
                    success: function (response) {
                        if (response.type == 'success') {
                            alert(response.message);
                            window.location.reload();
                        } else if(response.type == 'error'){
                            setRestButton(formID, 'update');
                            setError(response.message);
                        }else if(response.type == 'redirect'){
                            setRedirect(response.url, response.message);
                        }
                    }
                });

            });
        });
    </script>
@endpush
@endsection
