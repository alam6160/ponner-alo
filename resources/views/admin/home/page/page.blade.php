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
                <form class="filter-wrap row" action="" method="POST" id="page_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">
                        <fieldset class="col-lg-12">
                            <label for="page_title">Page Title <strong class="text-danger">*</strong> </label>
                            <input type="text" name="page_title" id="page_title" class="form-control" value="{{ empty($page) ? '' : $page->page_title }}" required>
                        </fieldset>

                        <fieldset class="col-lg-12">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ empty($page) ? '' : $page->slug }}" readonly>
                        </fieldset>

                        <div class="col-lg-12">
                            <fieldset>
                                <label for="page_desc">Page Descriptions</label>
                                <textarea name="page_desc" id="page_desc" >{{ empty($page) ? '' : htmlspecialchars_decode($page->page_desc) }}</textarea>
                            </fieldset>
                        </div>

                        <div class="col-lg-12">
                            <fieldset>
                                <label for="metakeywords">Meta Keywords</label>
                                <input id="metakeywords" class="form-control cfcrl" type="text" name="metakeywords" value="{{ empty($page) ? '' : $page->metakeywords }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <label for="metadescriptions">Meta Descriptions</label>
                                <textarea name="metadescriptions" id="metadescriptions" class="form-control cfcrl" rows="4">{{ empty($page) ? '' : $page->metadescriptions }}</textarea>
                            </fieldset>
                        </div>
                        

                    </div>

                    <fieldset class="col-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($page) ? 'Submit' : 'Update' }}</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('assests/ckeditor/ckeditor.js') }}"></script>
@endpush

@push('script')
<script>
    $(function(){
        $('#slug').slugify('#page_title');
        $('#page_form').parsley();

        CKEDITOR.replace( 'page_desc' );

        @if (empty($page))
        $('#page_form').submit(function (e) { 
            e.preventDefault();
            if ($('#page_form').parsley().isValid()) {
                const formID = 'page_form';
                formData = new FormData(this);
                const page_desc = CKEDITOR.instances['page_desc'].getData();
                formData.append('page_desc', page_desc);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.page.create') }}",
                    data: formData,
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
                            CKEDITOR.instances['page_desc'].setData("");
                        } else {
                            setRestButton(formID, 'submit');
                            setError(response.message);
                        }
                    }
                });
            }
        });
        @else
        $('#page_form').submit(function (e) { 
            e.preventDefault();
            if ($('#page_form').parsley().isValid()) {
                const formID = 'page_form';
                formData = new FormData(this);
                const page_desc = CKEDITOR.instances['page_desc'].getData();
                formData.append('page_desc', page_desc);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.page.edit', ['id'=>$page->id]) }}",
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
        @endif
    });
</script>
@endpush
@endsection
