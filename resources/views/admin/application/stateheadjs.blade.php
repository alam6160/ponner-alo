<script>
    $(function(){
        $('#statehead_form').parsley();
        
        $('#approve').click(function (e) { 
            e.preventDefault();
            if ($('#statehead_form').parsley().validate()) {
                formId = 'statehead_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.application.approve.satehead') }}",
                    data: new FormData($('#statehead_form')[0]),
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
</script>