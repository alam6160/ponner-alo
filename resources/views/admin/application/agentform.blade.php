@php
    $pincodeCollection = collect( explode(',', $userApplication->servicable_pincodes));
    $commonCollection = $pincodeCollection->diff($collectionARPincodes);

    $intersectCollection = $pincodeCollection->intersect($collectionARPincodes);
@endphp

@if ($intersectCollection->isNotEmpty())
<div class="row mb-3">
    <div class="col-md-12 text-danger"> <strong>Message :</strong> {{ "This ".$intersectCollection->implode(',')." pincode already Reserve" }}</div>
</div>
@endif


<form action="" method="post" id="agent_form">
    <input type="hidden" name="user_application_id" value="{{ $userApplication->id }}">
    <div class="row">

        @include('admin.application.common')

        <div class="col-lg-5">
            <fieldset class="select-box">
                <label for="">State Heads (Optional)</label>
                <select class="form-control" name="satehead_id" id="satehead_id">
                    <option value="">Select StateHead</option>
                    @foreach ($allstateheads as $var)
                    <option value="{{ $var->id }}">
                        {{ $var->fname.' '.$var->lname.' ( '.$var->email.' )' }}
                    </option>
                    @endforeach
                </select>
            </fieldset>
        </div>
        
        <div class="col-lg-12">
            <fieldset class="select-box">
                <label>Servicable Pincodes</label>
                <select class="form-control" id="servicable_pincodes" name="servicable_pincodes[]" multiple required data-parsley-errors-container="#servicable_pincodes_error">
                    @foreach ($commonCollection as $var)
                        <option value="{{ $var }}" selected>{{ $var }}</option>
                    @endforeach
                </select>
                <div id="servicable_pincodes_error"></div>
            </fieldset>
        </div>

        <fieldset class="col-lg-3">
            <button type="button" id="approve" class="btn-block btn-primary">Approve</button>
        </fieldset>
        <fieldset class="col-lg-3">
            <button type="button" id="cancel" data-toggle="modal" data-target="#cancelmodal" class="btn-block btn-primary">Cancel</button>
        </fieldset>
    </div>
</form>