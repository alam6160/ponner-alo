<div class="col-lg-4">
    <fieldset>
        <label for="email">Email <strong class="text-danger">*</strong></label>
        <input class="form-control" type="email" name="email" id="email" required value="{{ $userApplication->email }}">
    </fieldset>
</div>
<div class="col-lg-3">
    <fieldset>
        <label for="amount">Amount <strong class="text-danger">*</strong></label>
        <input class="form-control" type="text" name="amount" id="amount" required value="" data-parsley-type="digits" autocomplete="off" onclick="firstDeci()" onkeypress="return isNumberKey(this, event);">
    </fieldset>
</div>