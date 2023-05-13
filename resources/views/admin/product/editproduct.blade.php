@extends('admin.layout.layout')
@section('title', $title)
@section('content')

@push('style')
    <style>
        .loading {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid;
            border-color: #333 rgba(0,0,0,0.1) rgba(0,0,0,0.1);
            animation: spin .6s ease infinite;
        }

        @keyframes spin {
        0% { transform: rotate(0deg) }
        
        100% { transform: rotate(359deg) }
        }

        .loading-preview-images-zone {
            align-items: center;
            /* background-color: black; */
            background-color: #fdfbff;
            display: flex;
            justify-content: center;
            border: none;
            /*min-height: 300px; */
        }
    </style>
@endpush

<div class="col-lg-12 main-box" style="flex: none; max-width: initial;">
    <div class="content-wrap">
        <form action="" method="post" id="product_form" enctype="multipart/form-data">
            <div class="form-wrap">
                <div class="title-div">
                    <h3>{{ $title }}</h3>
                </div>
                <div class="tab-wrap">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pd-tab" data-toggle="tab" href="#pdTab" role="tab" aria-controls="pdTab" aria-selected="true">Product Details</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link " id="pricing-tab" data-toggle="tab" href="#pricingTab" role="tab" aria-controls="pricingTab" aria-selected="false">Pricing</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pimages-tab" data-toggle="tab" href="#pimagesTab" role="tab" aria-controls="pimagesTab" aria-selected="false">Images</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="seo-tab" data-toggle="tab" href="#seoTab" role="tab" aria-controls="seoTab" aria-selected="false">SEO</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane show active" id="pdTab" role="tabpanel" aria-labelledby="pd-tab">
                            <div class="row">
                                <div class="col-lg-6">
                                    <fieldset>
                                        <label for="name">Product Name <strong class="text-danger">*</strong></label>
                                        <input id="name" class="form-control cfcrl" type="text" name="name" required value="{{ $product->name }}">
                                    </fieldset>
                                </div>
                                <div class="col-lg-6">
                                    <fieldset>
                                        <label for="slug">Slug</label>
                                        <input id="slug" class="form-control cfcrl" type="text" name="slug" readonly value="{{ $product->slug }}">
                                    </fieldset>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <fieldset>
                                        <label for="components">Product Components (Salt)</label>
                                        <input id="components" class="form-control cfcrl" type="text" name="components" value="{{ $product->components }}">
                                    </fieldset>
                                </div> --}}
                                @php
                                    $category_ids = (empty($product->categories)) ? [] : explode(',', $product->categories) ;
                                @endphp
                                <div class="col-lg-12">
                                    <fieldset class="select-box">
                                        <label for="category_id">Select Category <strong class="text-danger">*</strong></label>
                                        <select class="form-control select2" id="category_id" name="category_ids[]" multiple required data-parsley-errors-container="#category_id_error">
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
                                <div class="col-lg-6">
                                    <fieldset class="select-box">
                                        <label for="product_filter_id">Select Type </label>
                                        <select class="form-control" id="product_filter_id" name="saletype">
                                            <option value="1" {{ !empty($product->saletype) ? 'selected' : '' }}>None</option>
                                            <option value="2" {{ !empty($product->saletype) ? 'selected' : '' }}>Featured</option>
                                            <option value="3" {{ !empty($product->saletype) ? 'selected' : '' }}>Deals of The Day</option>
                                            <option value="4" {{ !empty($product->saletype) ? 'selected' : '' }}>Deals of The Week</option>
                                            <option value="5" {{ !empty($product->saletype) ? 'selected' : '' }}>Deals of the Month</option>
                                        </select>
                                        <div id="product_filter_id_error"></div>
                                    </fieldset>
                                </div>
                                @php
                                    //$product_filter_id = (empty($product->product_filter_id)) ? [] : explode(',',$product->product_filter_id);
                                @endphp
                                {{-- <div class="col-lg-6">
                                    <fieldset class="select-box">
                                        <label for="product_filter_id">Select Filter </label>
                                        <select class="form-control select2" id="product_filter_id" name="product_filter_ids[]" multiple data-parsley-errors-container="#product_filter_id_error">
                                            @foreach ($parentFilters as $level1)
                                                <option disabled>{{ $level1->filter_title }}</option>
                                                @foreach ($level1->children as $level2)
                                                    <option value="{{ $level2->id }}" {{ in_array($level2->id, $product_filter_id) ? 'selected' : '' }}>
                                                        &nbsp;&nbsp; {{ $level2->filter_title }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div id="product_filter_id_error"></div>
                                    </fieldset>
                                </div> --}}
                                @php
                                    //$product_addon_id = (empty($product->product_addon_id)) ? [] : explode(',',$product->product_addon_id);
                                @endphp
                                {{-- <div class="col-lg-6">
                                    <fieldset class="select-box">
                                        <label for="product_addon_id">Addon</label>
                                        <select class="form-control select2" id="product_addon_id" name="product_addon_ids[]" multiple data-parsley-errors-container="#product_addon_id_error">
                                            @foreach ($productAddon as $var)
                                                <option value="{{ $var->id }}" {{ in_array($var->id, $product_addon_id) ? 'selected' : '' }}>
                                                    {{ $var->addon_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="product_addon_id_error"></div>
                                    </fieldset>
                                </div> --}}
                                <div class="col-lg-12">
                                    <fieldset>
                                        <label for="short_desc">Product Short Descriptions</label>
                                        <textarea name="short_desc" id="short_desc" class="form-control cfcrl" rows="2">{{ $product->short_desc }}</textarea>
                                    </fieldset>
                                </div>
                                <div class="col-lg-12">
                                    <fieldset>
                                        <label for="">Product Descriptions</label>
                                        <textarea name="description" id="description" class="form-control cfcrl" rows="2">{{ htmlspecialchars_decode($product->description) }}</textarea>
                                    </fieldset>
                                </div>
                                

                                
                                <div class="col-lg-3">
                                    <fieldset>
                                        <label for="number_of_items">Number of items</label>
                                        <input id="number_of_items" class="form-control cfcrl" type="number" name="number_of_items" value="{{ $product->number_of_items }}" onkeypress="return onlyNumberKey(event)">
                                    </fieldset>
                                </div>
                                
                                <div class="col-lg-3">
                                    <fieldset class="select-box">
                                        <label for="product_type">Select Product Type <strong class="text-danger">*</strong></label>
                                        <select class="form-control cfcrl" id="product_type" name="product_type" required onchange="setVariableDiv()">
                                            <option value="1" {{ $product->product_type == '1' ? 'selected': '' }}>Simple</option>
                                            <option value="2" {{ $product->product_type == '2' ? 'selected': '' }}>Variable</option>
                                        </select>
                                    </fieldset>
                                </div> 
                                
                            </div>

                            @if ($product->product_type == '2')
                                {{-- style="display: none;" --}}
                                <div class="row" id="variableDiv">
                                    <div class="col-lg-5">
                                        <fieldset>
                                            <label for="attribute_1">Attribute 1 <strong id="attribute_1_label" class="text-danger"></strong></label>
                                            <input id="attribute_1" class="form-control cfcrl" type="text" name="attribute_1" onkeyup="setVarientTitle(this)" value="{{ $product->attribute_1 }}">
                                        </fieldset>
                                    </div>
                                    @php
                                        $specifications_1 = (empty($product->specifications_1)) ? [] : explode(',', $product->specifications_1);
                                    @endphp
                                    <div class="col-lg-7">
                                        <fieldset class="select-box">
                                            <label id="attribute_1_title">{{ empty($product->attribute_1) ? 'Attribute Title' : $product->attribute_1 }}</label>
                                            <select class="form-control" id="specifications_1" name="specifications_1[]" multiple onchange="setPriceHtml()">
                                                @if (!empty($specifications_1))
                                                    @foreach ($specifications_1 as $specification)
                                                        <option value="{{ $specification }}" selected>{{ $specification }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-5">
                                        <fieldset>
                                            <label for="attribute_2">Attribute 2 <strong id="attribute_2_label" class="text-danger"></strong></label>
                                            <input id="attribute_2" class="form-control cfcrl" type="text" name="attribute_2" onkeyup="setVarientTitle(this)" value="{{ $product->attribute_2 }}">
                                        </fieldset>
                                    </div>
                                    @php
                                        $specifications_2 = (empty($product->specifications_2)) ? [] : explode(',', $product->specifications_2);
                                    @endphp
                                    <div class="col-lg-7">
                                        <fieldset class="select-box">
                                            <label id="attribute_2_title">{{ empty($product->attribute_2) ? 'Attribute Title' : $product->attribute_2 }}</label>
                                            <select class="form-control" id="specifications_2" name="specifications_2[]" multiple onchange="setPriceHtml()">
                                                @if (!empty($specifications_2))
                                                    @foreach ($specifications_2 as $specification)
                                                        <option value="{{ $specification }}" selected>{{ $specification }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-5">
                                        <fieldset>
                                            <label for="attribute_3">Attribute 3 <strong id="attribute_3_label" class="text-danger"></strong></label>
                                            <input id="attribute_3" class="form-control cfcrl" type="text" name="attribute_3" onkeyup="setVarientTitle(this)" value="{{ $product->attribute_3 }}">
                                        </fieldset>
                                    </div>
                                    @php
                                        $specifications_3 = (empty($product->specifications_3)) ? [] : explode(',', $product->specifications_3);
                                    @endphp
                                    <div class="col-lg-7">
                                        <fieldset class="select-box">
                                            <label id="attribute_3_title">{{ empty($product->attribute_3) ? 'Attribute Title' : $product->attribute_3 }}</label>
                                            <select class="form-control" id="specifications_3" name="specifications_3[]" multiple onchange="setPriceHtml()">
                                                @if (!empty($specifications_3))
                                                    @foreach ($specifications_3 as $specification)
                                                        <option value="{{ $specification }}" selected>{{ $specification }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </fieldset>
                                    </div>
                                </div>
                            @elseif ($product->product_type == '1')
                                <div class="row" id="variableDiv" style="display: none;">
                                    <div class="col-lg-5">
                                        <fieldset>
                                            <label for="attribute_1">Attribute 1 <strong id="attribute_1_label" class="text-danger"></strong></label>
                                            <input id="attribute_1" class="form-control cfcrl" type="text" name="attribute_1" onkeyup="setVarientTitle(this)">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-7">
                                        <fieldset class="select-box">
                                            <label id="attribute_1_title">Attribute Title</label>
                                            <select class="form-control" id="specifications_1" name="specifications_1[]" multiple onchange="setPriceHtml()"></select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-5">
                                        <fieldset>
                                            <label for="attribute_2">Attribute 2 <strong id="attribute_2_label" class="text-danger"></strong></label>
                                            <input id="attribute_2" class="form-control cfcrl" type="text" name="attribute_2" onkeyup="setVarientTitle(this)">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-7">
                                        <fieldset class="select-box">
                                            <label id="attribute_2_title">Attribute Title</label>
                                            <select class="form-control" id="specifications_2" name="specifications_2[]" multiple onchange="setPriceHtml()"></select>
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-5">
                                        <fieldset>
                                            <label for="attribute_3">Attribute 3 <strong id="attribute_3_label" class="text-danger"></strong></label>
                                            <input id="attribute_3" class="form-control cfcrl" type="text" name="attribute_3" onkeyup="setVarientTitle(this)">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-7">
                                        <fieldset class="select-box">
                                            <label id="attribute_3_title">Attribute Title</label>
                                            <select class="form-control" id="specifications_3" name="specifications_3[]" multiple onchange="setPriceHtml()"></select>
                                        </fieldset>
                                    </div>
                                </div>
                            @endif

                            
                        </div>
                        <div class="tab-pane fade " id="pricingTab" role="tabpanel" aria-labelledby="pricing-tab">
                            @if ($product->product_type == '1')
                            
                            <div class="row mb-3">
                                <label for="mrp" class="col-sm-3 col-form-label">SKU Code</label>
                                <div class="col-sm-5">
                                    <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off" value="{{ $product->sku }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="mrp" class="col-sm-3 col-form-label">MRP <strong class="text-danger">*</strong></label>
                                <div class="col-sm-5">
                                    <input type="tel" id="mrp" class="form-control cfcrl" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off" value="{{ $product->mrp }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="discounted_price" class="col-sm-3 col-form-label">Discounted Ammount </label>
                                <div class="col-sm-5">
                                    <input type="tel" id="discounted_price" class="form-control cfcrl" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off" value="{{ $product->discounted_price }}">
                                </div>
                            </div>
                            @elseif ($product->product_type == '2')
                           
                            @if (!empty($product->specifications))
                                @php
                                    $specifications = json_decode($product->specifications, TRUE);
                                    $mrps = array_filter(json_decode($product->mrp, TRUE));
                                    $skus = array_filter(json_decode($product->sku, TRUE));
                                    $discounted_prices = array_filter(json_decode($product->discounted_price, TRUE));
                                @endphp
                                @foreach ($specifications as $key => $specification)
                                <div class="row">
                                    <div class="col-lg-3">
                                        <fieldset>
                                            <label for="varient_set">Title <strong class="text-danger">*</strong></label>

                                            <input id="varient_set" class="form-control cfcrl" type="tel" name="varient_set[]" required value="{{ (!empty($specification)) ? $specification : '' }}" readonly>
                                            
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-3">
                                        <fieldset>
                                            <label for="mrp">MRP <strong class="text-danger">*</strong></label>
                                            <input id="mrp" class="form-control cfcrl" type="tel" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off" value="{{ (!empty($mrps[$key])) ? $mrps[$key] : '0' }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-3">
                                        <fieldset>
                                            <label for="mrp">SKU Code </label>
                                            <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off" value="{{ (!empty($skus[$key])) ? $skus[$key] : '' }}">
                                        </fieldset>
                                    </div>
                                    <div class="col-lg-3">
                                        <fieldset>
                                            <label for="discounted_price">Discounted Ammount </label>
                                            <input id="discounted_price" class="form-control cfcrl" type="tel" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off" value="{{ (!empty($discounted_prices[$key])) ? $discounted_prices[$key] : '' }}">
                                        </fieldset>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            @endif
                        </div>
                        <div class="tab-pane fade" id="pimagesTab" role="tabpanel" aria-labelledby="pimages-tab">

                            <div class="row mb-5">
                                <label for="" class="col-sm-3 col-form-label">Product Featured Image </label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" name="featured_image" class="custom-file-input form-control" id="featured_image" accept=".png,.jpeg,.png">
                                        <label class="custom-file-label" for="featured_image">Choose file</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label for="" class="col-sm-3 col-form-label">Product Gallery Image </label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input type="file" name="images[]" multiple class="custom-file-input form-control" id="images" accept=".png,.jpeg,.png,.webp">
                                        <label class="custom-file-label" for="images">Choose file</label>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="row justify-content-center" style="min-height: 300px;">
                                <div class="col-md-11 mt-5 border preview-images-zone" id="preview-images-zone">
                                    {{-- <div class="row">
                                        <div class="col-md-4">
                                            <figure class="figure">
                                                <img src="https://c1.staticflickr.com/5/4023/5154094149_8c1345f634.jpg" class="figure-img img-fluid rounded" alt="...">
                                                <figcaption class="figure-caption text-right"><a href="javascript:void(0)">Delete</a></figcaption>
                                            </figure>
                                        </div>
                                    </div> --}}
                                    @if (!empty($product->images))
                                        @php
                                            $images = explode(',', $product->images);
                                        @endphp
                                        <div class="row">
                                        @foreach ($images as $key => $image)
                                        <div class="col-md-4 py-1">
                                            <figure class="figure">
                                                <img src="{{ asset($image) }}" class="figure-img img-thumbnail img-fluid rounded">
                                                <figcaption class="figure-caption text-right">
                                                    <a href="javascript:void(0)" class="text-danger" data-id="{{ $key }}" onclick="deleteImage(this)">
                                                        <b><i class="fa fa-trash"></i> Delete</b>
                                                    </a>
                                                </figcaption>
                                            </figure>
                                        </div>
                                        @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="row justify-content-center" style="min-height: 300px;">
                                <div class="col-md-11 mt-5 border py-2 mx-2 loading-preview-images-zone" id="preview-images-zone">
                                    <div class="loading"></div>
                                </div>
                            </div> --}}


                        </div>
                        
                        <div class="tab-pane fade" id="seoTab" role="tabpanel" aria-labelledby="seo-tab">
                            <div class="col-lg-12">
                                <fieldset>
                                    <label for="metakeywords">Meta Keywords</label>
                                    <input id="metakeywords" class="form-control cfcrl" type="text" name="metakeywords" value="{{ $product->metakeywords }}">
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <fieldset>
                                    <label for="metadescriptions">Meta Descriptions</label>
                                    <textarea name="metadescriptions" id="metadescriptions" class="form-control cfcrl" rows="4">{{ $product->metadescriptions }}</textarea>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" name="submit" class="btn-primary">Update</button>
        </form>
    </div>
</div>

@push('script')
    <script src="{{ asset('assests/ckeditor/ckeditor.js') }}"></script>
@endpush

@push('script')
<script>
    $(function(){
        $('#slug').slugify('#name');
        $('#product_form').parsley();
        CKEDITOR.replace( 'description' );
        $("#specifications_1").select2({
            placeholder: "Enter Varient 1",
            tags: true,
        });
        $("#specifications_2").select2({
            placeholder: "Enter Varient 2",
            tags: true,
        });
        $("#specifications_3").select2({
            placeholder: "Enter Varient 3",
            tags: true,
        });

        $('#product_form').submit(function (e) { 
            e.preventDefault();
            if ($('#product_form').parsley().isValid()) {
                const formID = 'product_form';
                formData = new FormData(this);
                const description = CKEDITOR.instances['description'].getData();
                formData.append('description', description);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.product.edit', ['id'=> $product->id]) }}",
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
                            $('.custom-file-label').html('');
                            setProductImages();
                            
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

    });
    function setVariablePriceForm(specifications) {

        let htmlString = '';
        if (specifications.length > 0) {
            for (let index = 0; index < specifications.length; index++) {
                
                htmlString +=`
                <div class="row">
                    <div class="col-lg-12">
                        <label for=""><strong>${specifications[index]}</strong></label>
                    </div>
                    <div class="col-lg-4">
                        <fieldset>
                            <label for="strip_price">Pices per Strip <strong class="text-danger">*</strong> </label>
                            <input id="strip_price" class="form-control cfcrl" type="tel" name="strip_price[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                        </fieldset>
                    </div>
                    <div class="col-lg-4">
                        <fieldset>
                            <label for="mrp">MRP <strong class="text-danger">*</strong></label>
                            <input id="mrp" class="form-control cfcrl" type="tel" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                        </fieldset>
                    </div>
                    <div class="col-lg-3">
                        <fieldset>
                            <label for="mrp">SKU Code </label>
                            <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off">
                        </fieldset>
                    </div>
                    <div class="col-lg-4">
                        <fieldset>
                            <label for="discounted_price">Discounted Ammount</label>
                            <input id="discounted_price" class="form-control cfcrl" type="tel" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                        </fieldset>
                    </div>
                </div>`;
            }
        }
        $('#pricingTab').html(htmlString);
    }
    function deleteImage(evt) {
        const row = evt.getAttribute('data-id');
        if (row !== '') {
            const formData = {row:row, product_id:'{{ $product->id }}'};
            $.ajax({
                type: "POST",
                url: "{{ route('admin.product.deleteimage') }}",
                data: formData,
                dataType: "JSON",
                beforeSend: function(){
                    const htmlString =`<div class="spinner-border spinner-border-sm" role="status"></div><strong>&nbsp;Processing</strong>`;
                    $(event.target).attr('disabled', true);
                    $(event.target).html(htmlString);
                },
                success: function (response) {
                    if (response.type == 'success') {
                        setProductImages();
                        toastr.success(response.message);
                    }else{
                        $(event.target).attr('disabled', false);
                        const htmlString =`<b><i class="fa fa-trash"></i> Delete</b>`;
                        $(event.target).html(htmlString);
                        setError(response.message);
                    }
                }
            });
        } else {
            toastr.error('ID is required');
        }
    }
    function setProductImages() {
        $.ajax({
            type: "POST",
            url: "{{ route('admin.product.getimage') }}",
            data: {product_id:'{{ $product->id }}'},
            dataType: "JSON",
            beforeSend: function(){
                const htmlString =`<div class="row justify-content-center" style="min-height: 300px; border:none;"><div class="col-md-12 loading-preview-images-zone"><div class="loading"></div></div></div>`;
                $('#preview-images-zone').html(htmlString);
            },
            success: function (response) {
                htmlString = '';
                if (response.type == 'success') {
                    const imagesArr = response.imagesArr;
                    htmlString +='<div class="row">';
                    for (let index = 0; index < imagesArr.length; index++) {
                        path = '{{ asset('') }}' + imagesArr[index];
                        htmlString +=`
                        <div class="col-md-4 py-1">
                            <figure class="figure">
                                <img src="${path}" class="figure-img img-thumbnail img-fluid rounded">
                                <figcaption class="figure-caption text-right"><a href="javascript:void(0)" class="text-danger" data-id="${index}" onclick="deleteImage(this)"><b><i class="fa fa-trash"></i> Delete</b></a></figcaption>
                            </figure>
                        </div>`;
                    }
                    htmlString +='</div>';
                } else {
                    setError(response.message);
                }
                $('#preview-images-zone').html(htmlString);
            }
        });
    }

    // PRODUCT VARIENT SETTING...
    function setVariableDiv() {
        const product_type = $('#product_type option:selected').val();
        if (product_type == '1') {
            $('#variableDiv').hide();

            $("#specifications_1").val('').trigger('change');
            $("#specifications_2").val('').trigger('change');
            $("#specifications_3").val('').trigger('change');

            $('#attribute_1').val('');
            $('#attribute_2').val('');
            $('#attribute_3').val('');

            $('#attribute_1').prop('required', false);
            $('#attribute_1_label').text('');
            $('#specifications_1').prop('required', false);
            $('#attribute_1_title').html('Attribute Title');

            setSimplePriceForm();

        }else if(product_type == '2'){

            $('#variableDiv').show();
            $('#attribute_1').prop('required', true);
            $('#attribute_1_label').text('*');
            $('#specifications_1').prop('required', true);
            $('#attribute_1_title').html('Attribute Title <strong class="text-danger">*</strong>');

            $("#specifications_1").select2({
                placeholder: "Enter Varient 1",
                tags: true,
            });
            $("#specifications_2").select2({
                placeholder: "Enter Varient 2",
                tags: true,
            });
            $("#specifications_3").select2({
                placeholder: "Enter Varient 3",
                tags: true,
            });

            setPriceHtml();
        }
        
    }
    function setPriceHtml() {
        
        let flag_1 = false; let flag_2 = false; let flag_3 = false; 
        const specifications_1 = $('#specifications_1').val();
        const specifications_2 = $('#specifications_2').val();
        const specifications_3 = $('#specifications_3').val();

        if (specifications_1 !== null) {
            if (specifications_1.length > 0) {
                flag_1 = true;
            }
        }
        if (specifications_2 !== null) {
            if (specifications_2.length > 0) {
                flag_2 = true;
                $('#attribute_2').prop('required', true);
                $('#attribute_2_label').text('*');
            }else{
                $('#attribute_2').prop('required', false);
                $('#attribute_2_label').text('');
            }
        }else{
            $('#attribute_2').prop('required', false);
            $('#attribute_2_label').text('');
        }
        if (specifications_3 !== null) {
            if (specifications_3.length > 0) {
                flag_3 = true;
                $('#attribute_3').prop('required', true);
                $('#attribute_3_label').text('*');
            }else{
                $('#attribute_3').prop('required', false);
                $('#attribute_3_label').text('');
            }
        }else{
            $('#attribute_3').prop('required', false);
            $('#attribute_3_label').text('');
        }

        /*
        if (flag_1 === true || flag_2 === true || flag_3 === true) {
            $('#product_type').html('<option value="2">Variable</option>');
        }else{
            $('#product_type').html('<option value="1">Simple</option>');
        } */

        if (flag_1 === true && flag_2 === false && flag_3 === false) {
            setVariablePriceForm_1(specifications_1);
        }else if(flag_1 === true && flag_2 === true && flag_3 === false){
            setVariablePriceForm_2(specifications_1, specifications_2);
        }else if(flag_1 === true && flag_2 === true && flag_3 === true){
            setVariablePriceForm_3(specifications_1, specifications_2, specifications_3);
        }else{
            /* setSimplePriceForm(); */
            $('#pricingTab').html('');
        }
    }
    function setSimplePriceForm() {

        let htmlString = `
        <div class="row mb-3">
            <label for="mrp" class="col-sm-3 col-form-label">SKU Code</label>
            <div class="col-sm-5">
                <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off">
            </div>
        </div>
        `;

        htmlString +=`
        <div class="row mb-3">
            <label for="mrp" class="col-sm-3 col-form-label">MRP <strong class="text-danger">*</strong></label>
            <div class="col-sm-5">
                <input type="tel" id="mrp" class="form-control cfcrl" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
            </div>
        </div>`;

        htmlString +=`
        <div class="row mb-3">
            <label for="discounted_price" class="col-sm-3 col-form-label">Discounted Ammount </label>
            <div class="col-sm-5">
                <input type="tel" id="discounted_price" class="form-control cfcrl" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
            </div>
        </div>`;
        $('#pricingTab').html(htmlString);
    }
    function setVariablePriceForm_1(specifications) {

        let htmlString = '';
        if (specifications.length > 0) {
            for (let index = 0; index < specifications.length; index++) {
                
                htmlString +=`
                <div class="row">
                    <div class="col-lg-3">
                        <fieldset>
                            <label for="varient_set">Title <strong class="text-danger">*</strong></label>
                            <input id="varient_set" class="form-control cfcrl" type="tel" name="varient_set[]" required value="${specifications[index]}" readonly>
                        </fieldset>
                    </div>
                    <div class="col-lg-3">
                        <fieldset>
                            <label for="mrp">MRP <strong class="text-danger">*</strong></label>
                            <input id="mrp" class="form-control cfcrl" type="tel" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                        </fieldset>
                    </div>
                    <div class="col-lg-3">
                        <fieldset>
                            <label for="mrp">SKU Code </label>
                            <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off">
                        </fieldset>
                    </div>
                    <div class="col-lg-3">
                        <fieldset>
                            <label for="discounted_price">Discounted Ammount </label>
                            <input id="discounted_price" class="form-control cfcrl" type="tel" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                        </fieldset>
                    </div>
                </div>`;
            }
        }
        $('#pricingTab').html(htmlString);
    }
    function setVariablePriceForm_2(specifications_1, specifications_2) {

        let htmlString = '';
        if (specifications_1.length > 0) {
            for (let i = 0; i < specifications_1.length; i++) {
                for (let j = 0; j < specifications_2.length; j++) {
                    let varient_set = specifications_1[i]+'-'+specifications_2[j];
                    let varient_lebel = specifications_1[i]+'-'+specifications_2[j];

                    htmlString +=`
                    <div class="row">
                        <div class="col-lg-3">
                            <fieldset>
                                <label for="varient_set">Title <strong class="text-danger">*</strong></label>
                                <input id="varient_set" class="form-control cfcrl" type="tel" name="varient_set[]" required value="${varient_set}" readonly>
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset>
                                <label for="mrp">MRP <strong class="text-danger">*</strong></label>
                                <input id="mrp" class="form-control cfcrl" type="tel" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset>
                                <label for="mrp">SKU Code </label>
                                <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off">
                            </fieldset>
                        </div>
                        <div class="col-lg-3">
                            <fieldset>
                                <label for="discounted_price">Discounted Ammount </label>
                                <input id="discounted_price" class="form-control cfcrl" type="tel" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                            </fieldset>
                        </div>
                    </div>`;
                    
                }
                
            }
        }
        $('#pricingTab').html(htmlString);
    }
    function setVariablePriceForm_3(specifications_1, specifications_2, specifications_3) {

        let htmlString = '';
        if (specifications_1.length > 0) {
            for (let i = 0; i < specifications_1.length; i++) {
                for (let j = 0; j < specifications_2.length; j++) {

                    for (let k = 0; k < specifications_3.length; k++) {
                        let varient_set = specifications_1[i]+'-'+specifications_2[j]+'-'+specifications_3[k];
                        let varient_lebel = specifications_1[i]+'-'+specifications_2[j]+'-'+specifications_3[k];

                        htmlString +=`
                        <div class="row">
                            <div class="col-lg-3">
                                <fieldset>
                                    <label for="varient_set">Title <strong class="text-danger">*</strong></label>
                                    <input id="varient_set" class="form-control cfcrl" type="tel" name="varient_set[]" required value="${varient_set}" readonly>
                                </fieldset>
                            </div>
                            <div class="col-lg-3">
                                <fieldset>
                                    <label for="mrp">MRP <strong class="text-danger">*</strong></label>
                                    <input id="mrp" class="form-control cfcrl" type="tel" name="mrp[]" required data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                                </fieldset>
                            </div>
                            <div class="col-lg-3">
                                <fieldset>
                                    <label for="mrp">SKU Code </label>
                                    <input id="sku_code" class="form-control cfcrl" type="text" name="sku_code[]" autocomplete="off">
                                </fieldset>
                            </div>
                            <div class="col-lg-3">
                                <fieldset>
                                    <label for="discounted_price">Discounted Ammount </label>
                                    <input id="discounted_price" class="form-control cfcrl" type="tel" name="discounted_price[]" data-parsley-type="digits" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);" autocomplete="off">
                                </fieldset>
                            </div>
                        </div>`;
                        
                    }
                }
            }
        }
        $('#pricingTab').html(htmlString);
    }
    function setVarientTitle(evt) {
        id = evt.getAttribute('id');
        title = document.getElementById(id).value;
        //console.log(title);
        if (title !== '') {
            $('#'+id+'_title').html(title +' <strong class="text-danger">*</strong>');
        }else{
            $('#'+id+'_title').text('Attribute Title ');
        }
        
    }
</script>
@endpush
@endsection
