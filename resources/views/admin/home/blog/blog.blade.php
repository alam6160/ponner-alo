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
                <form class="filter-wrap row" action="" method="POST" id="blog_form" enctype="multipart/form-data" autocomplete="off">
                    <div class="col-lg-12 row" id="filterRules">
                        <fieldset class="col-lg-12">
                            <label for="blog_title">Blog Title <strong class="text-danger">*</strong> </label>
                            <input type="text" name="blog_title" id="blog_title" class="form-control" value="{{ empty($blog) ? '' : $blog->blog_title }}" required>
                        </fieldset>

                        <fieldset class="col-lg-12">
                            <label for="slug">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ empty($blog) ? '' : $blog->slug }}" readonly>
                        </fieldset>

                        <div class="col-lg-6">
                            <fieldset>
                                <div class="form-group">
                                    <label for="thumbnail">Thumbnail <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                    <div class="custom-file">
                                        <input type="file" name="thumbnail" class="custom-file-input form-control" id="thumbnail" accept=".png,.jpeg,.jpg">
                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        @php
                            $publish_status = (empty($blog)) ? '1' : $blog->publish_status;
                        @endphp
                        <div class="col-lg-3">
                            <fieldset class="select-box">
                                <label for="publish_status">Publising Status <strong class="text-danger">*</strong> </label>
                                <select class="form-control" name="publish_status" id="publish_status" onchange="setScheduleDate()">
                                    <option value="1" {{ $publish_status == '1' ? 'selected' : '' }} >Schedule</option>
                                    <option value="2" {{ $publish_status == '2' ? 'selected' : '' }}>Daft</option>
                                </select>
                            </fieldset>
                        </div>

                        <fieldset class="col-lg-3">
                            <label for="schedule_date">Schedule Date <strong class="text-danger" id="schedule_txt"></strong></label>
                            <input type="date" name="schedule_date" id="schedule_date" class="form-control" value="{{ empty($blog) ? '' : $blog->schedule_date }}">
                        </fieldset>

                        

                        @php

                            $category_ids = [];
                            if (!empty($blog)) {
                                $category_ids = (empty($blog->categories)) ? [] : explode(',', $blog->categories);
                            }
                        @endphp
                        <div class="col-lg-12">
                            <fieldset class="select-box">
                                <label for="category_id">Select Category </label>
                                <select class="form-control select2" id="category_id" name="category_ids[]" multiple data-parsley-errors-container="#category_id_error">
                                    @foreach ($parent_categories as $level1)
                                        <option value="{{ $level1->id }}" {{ in_array($level1->id, $category_ids) ? 'selected' : '' }} >
                                            {{ $level1->cat_title }}</option>
                                        @foreach ($level1->children as $level2)
                                            <option value="{{ $level2->id }}" {{ in_array($level2->id, $category_ids) ? 'selected' : '' }}>
                                                &nbsp;&nbsp; {{ $level2->cat_title }}
                                            </option>
                                        @endforeach
                                    @endforeach
                                </select>
                                <div id="category_id_error"></div>
                            </fieldset>
                        </div>

                        <div class="col-lg-12">
                            <fieldset>
                                <label for="blog_desc">Blog Descriptions</label>
                                <textarea name="blog_desc" id="blog_desc" >{{ empty($blog) ? '' : htmlspecialchars_decode($blog->blog_desc) }}</textarea>
                            </fieldset>
                        </div>

                        <div class="col-lg-12">
                            <fieldset>
                                <label for="metakeywords">Meta Keywords</label>
                                <input id="metakeywords" class="form-control cfcrl" type="text" name="metakeywords" value="{{ empty($blog) ? '' : $blog->metakeywords }}">
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <label for="metadescriptions">Meta Descriptions</label>
                                <textarea name="metadescriptions" id="metadescriptions" class="form-control cfcrl" rows="4">{{ empty($blog) ? '' : $blog->metadescriptions }}</textarea>
                            </fieldset>
                        </div>
                        

                    </div>

                    <fieldset class="col-lg-4">
                        <button type="submit" name="submit" class="btn-block btn-dark">{{ empty($blog) ? 'Submit' : 'Update' }}</button>
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
        $('#slug').slugify('#blog_title');
        $('#blog_form').parsley();
        setScheduleDate();

        CKEDITOR.replace( 'blog_desc' );

        @if (empty($blog))
        $('#blog_form').submit(function (e) { 
            e.preventDefault();
            if ($('#blog_form').parsley().isValid()) {
                const formID = 'blog_form';
                formData = new FormData(this);
                const blog_desc = CKEDITOR.instances['blog_desc'].getData();
                formData.append('blog_desc', blog_desc);
                
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.blog.create') }}",
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
                            CKEDITOR.instances['blog_desc'].setData("");
                            $('#category_id').trigger('change');
                        } else {
                            setRestButton(formID, 'submit');
                            setError(response.message);
                        }
                    }
                });
            }
        });
        @else
        $('#blog_form').submit(function (e) { 
            e.preventDefault();
            if ($('#blog_form').parsley().isValid()) {
                const formID = 'blog_form';
                formData = new FormData(this);
                const blog_desc = CKEDITOR.instances['blog_desc'].getData();
                formData.append('blog_desc', blog_desc);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.home.blog.edit', ['id'=>$blog->id]) }}",
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
    function setScheduleDate() {
        const publish_status = $('#publish_status option:selected').val();
        if (publish_status == '1') {
            $('#schedule_date').val('{{ (empty($blog)) ? date('Y-m-d') : date('Y-m-d', strtotime($blog->schedule_date) ) }}');
            $('#schedule_date').prop('required', true);
            $('#schedule_txt').text('*');
        } else {
            $('#schedule_date').val('');
            $('#schedule_date').prop('required', false);
            $('#schedule_txt').text('');
        }
    }
</script>
@endpush
@endsection
