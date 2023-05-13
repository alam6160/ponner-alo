<script>
    $(function(){
        setJoinUsForm();
        $('#appl_form').parsley();
        $('#appl_form').submit(function (e) { 
            e.preventDefault();
            const formId = 'appl_form';
            if ($('#appl_form').parsley().isValid()) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('joinus') }}",
                    data: new FormData(this),
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('#'+formId+' [name=submit]').prop('disabled', true);
                        $('#'+formId+' [name=submit]').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                    },
                    success: function (response) {
                        $('#'+formId+' [name=submit]').prop('disabled', false);
                        $('#'+formId+' [name=submit]').text('Join Us');
                        if (response.type == 'success') {
                            $('#'+formId).parsley().reset();
                            $('#'+formId)[0].reset();
                            $("#servicable_pincodes").val('').trigger('change');
                            toastr.success(response.message);
                        } else {
                            setError(response.message);
                        }
                    }
                });
            }
        });
        
    });
    function availablePincode() {
        const department = $('#department option:selected').val();
        let pinCode = '';
        if (department == 'hsp') {
            pinCode = '{{ $agentReservePincodes->reservepincodes }}';
        }else if(department == 'retailer'){
            pinCode = '{{ $retailerReservePincodes->reservepincodes }}';
        }
        //console.log(pinCode);
        const pincodeArr = pinCode.split(",");
        const pincodes = $('#servicable_pincodes').val();
        //console.log(pincodes);
        if (pincodes !== null) {
            if (pincodes.length > 0) {
                for (let index = 0; index < pincodes.length; index++) {
                    const element = pincodes[index].toString();
                    if(pincodeArr.indexOf(element) >= 0){
                        toastr.error(element+' is Already Reserved');
                    }
                }
            }
        }
    }

    /* USE :  onkeypress="return onlyNumberKey(event)" type="tel" */
    function onlyNumberKey(evt) {
        /* Only ASCII character in that range allowed */
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode;
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)){
            return false;
        }
        return true;
    }
    function setJoinUsForm() {
        const department = $('#department option:selected').val();
        if (department !== '') {
            if (department == 'hsp' || department == 'retailer') {
                HSPFormBuilder(department);
            }else if (department == 'statehead') {
                stateHeadFormBuilder(department);
            }
        }
    }
    function HSPFormBuilder(department) {
        let htmlString = ``;
        htmlString += organizationForm(department);
        htmlString += licenceForm(department);
        htmlString += servicablePincodesForm(department);
        htmlString += brandLogoForm(department);
        htmlString += gstForm(department);
        htmlString += druglicenceForm(department);
        htmlString += aadhaarForm(department);

        $('#formHtml').html(htmlString);

        $("#servicable_pincodes").select2({
            placeholder: "Enter Pincode",
            tags: true,
            
        });
    }
    function stateHeadFormBuilder(department) {
        let htmlString = ``;
        htmlString += organizationForm(department);
        htmlString += licenceForm(department);
        htmlString += servicableStateForm(department);
        htmlString += brandLogoForm(department);
        htmlString += gstForm(department);
        htmlString += druglicenceForm(department);
        htmlString += aadhaarForm(department);
        
        $('#formHtml').html(htmlString);
    }
    function organizationForm(department) {
        const rowclass = (department == 'statehead') ? 8 : 4 ;
        const labeltitle = (department == 'statehead') ? 'Company Name' : 'Organization Name' ;
        return `<div class="col-md-4 mb-2"><div class="form-group"><label for="organization_name">${labeltitle} </label><input id="organization_name" class="form-control" type="text" name="organization_name"></div></div>`;
    }
    function licenceForm(department) {
        return `<div class="col-md-4 mb-2"><div class="form-group"><label for="licence">Licence </label><input id="licence" class="form-control" type="text" name="licence"></div></div>`;
    }
    function servicableStateForm() {
        let html = `
        <div class="col-md-4 mb-2">
            <div class="form-group">
                <label for="servicable_state_id">Servicable State <strong class="text-danger">*</strong></label>
                <select id="servicable_state_id" class="form-select" name="servicable_state_id" required>
                    <option value="">Select State</option>`;
                    @foreach ($stateReserveStates as $var)
        html +=         `<option value="{{ $var->id }}">{{ $var->state_name }}</option>`;
                    @endforeach
        html +=`</select>
            </div>
        </div>`;

        return html;
    }
    function servicablePincodesForm(department) {
        return `<div class="col-md-4 mb-2">
                    <div class="form-group">
                        <label for="servicable_pincodes">Servicable Pincodes <strong class="text-danger">*</strong></label>
                        <select class="form-control" id="servicable_pincodes" name="servicable_pincodes[]" multiple="multiple" onchange="availablePincode()" required data-parsley-errors-container="#servicable_pincode_error"></select>
                        <div id="servicable_pincode_error"></div>
                    </div>
                </div>`;
    }
    function brandLogoForm(department) {
        return `<div class="col-md-6 mb-3"><div class="form-group"><label for="brand_logo_file">Upload Brand Logo (only jpg,jpeg,png or maximum file size 2MB)</label><input id="brand_logo_file" class="form-control" type="file" name="brand_logo_file" accept=".png,.jpeg,.jpg"></div></div>`;
    }
    function gstForm(department) {
        return `<div class="col-md-6 mb-3"><div class="form-group"><label for="gst_file">Upload GST (only jpg,jpeg,png or maximum file size 2MB)</label><input id="gst_file" class="form-control" type="file" name="gst_file" accept=".png,.jpeg,.jpg"></div></div>`;
    }
    function druglicenceForm(department) {
        return `<div class="col-md-6 mb-3"><div class="form-group"><label for="drug_licence_file">Upload Drug Licence (only jpg,jpeg,png or maximum file size 2MB)</label><input id="drug_licence_file" class="form-control" type="file" name="drug_licence_file" accept=".png,.jpeg,.jpg"></div></div>`;
    }
    function aadhaarForm(department) {
        return `<div class="col-md-6 mb-3"><div class="form-group"><label for="aadhaar_file">Upload Aadhaar (only jpg,jpeg,png or maximum file size 2MB)</label><input id="aadhaar_file" class="form-control" type="file" name="aadhaar_file" accept=".png,.jpeg,.jpg"></div></div>`;
    }
</script>