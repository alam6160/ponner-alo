

@if (in_array($userApplication->servicable_state_id, $servicable_state_ids) == TRUE )
<div class="row mb-3">
    <div class="col-md-12 text-danger"> <strong>Message :</strong> {{ "The ".$userApplication->servicable_state->state_name ." state is already Reserve" }}</div>
</div>
@endif
<form action="" method="post" id="statehead_form">
    <input type="hidden" name="user_application_id" value="{{ $userApplication->id; }}">
    <div class="row">
        @include('admin.application.common')
        <div class="col-lg-5">
            <fieldset class="select-box">
                <label for="servicable_state_id">Servicable State</label>
                <select class="form-control" name="servicable_state_id" id="servicable_state_id" required>
                    <option value="">Select State</option>
                    @foreach ($stateReserveStates as $var)
                    <option value="{{ $var->id }}" {{ $userApplication->servicable_state_id == $var->id ? 'selected' : '' }} >{{ $var->state_name }}</option>
                    @endforeach
                </select>
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