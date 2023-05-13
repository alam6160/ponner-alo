<script>
    $(function(){
        $('#agent_form').parsley();

        $('#approve').click(function (e) { 
            e.preventDefault();
            
            if ($('#agent_form').parsley().validate()) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.application.approve.agent') }}",
                    data: new FormData($('#agent_form')[0]),
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $('#approve').prop('disabled', true);
                        $('#approve').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                        $('#cancel').prop('disabled', true);
                    },
                    success: function (response) {
                        $('#approve').prop('disabled', false);
                        $('#cancel').prop('disabled', false);
                        $('#approve').text('Approve');
                        if (response.type == 'success') {
                            $('#formDiv').hide();
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
        const pinCode = '{{ $agentReservePincodes->reservepincodes }}';
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
</script>