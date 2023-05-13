<script>
    $(function(){
        $('#dataTable').on('click', '.btn-delete', function () {
            event.preventDefault();

            var status = $(this).attr('data-status');
            var id = $(this).attr('data-row');

            if (status !=='' && id !=='') {

                let confirmButtonText = '';
                if (status == '2') {
                    confirmButtonText = 'Yes, delete it!';
                } else {
                    confirmButtonText = 'Yes, restore it!';
                }
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: confirmButtonText,
                    closeOnConfirm: false
                },
                function(){
                    swal.close();
                    form_data = {status:status, id:id };
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.ajax.changestatus.user') }}",
                        data: form_data,
                        dataType: "JSON",
                        beforeSend: function(){
                            
                        },
                        success: function (response) {
                            if (response.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                setError(response.message);
                            }
                        }
                    });
                });
                
            }else{
                toastr.error('Agent ID or Status is required');
            }
        });

        $('#dataTable').on('click', '.btn-status',function () {
            event.preventDefault();

            var status = $(this).attr('data-status');
            var id = $(this).attr('data-row');

            if (status !=='' && id !=='') {

                let confirmButtonText = '';
                if (status == '2') {
                    confirmButtonText = 'Yes, Active it!';
                } else {
                    confirmButtonText = 'Yes, Inactive it!';
                }
                swal({
                    title: "Are you sure?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: confirmButtonText,
                    closeOnConfirm: false
                },
                function(){
                    swal.close();
                    form_data = {status:status, id:id };
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.ajax.changestatus.active') }}",
                        data: form_data,
                        dataType: "JSON",
                        beforeSend: function(){
                            $(event.target).prop('disabled', true);
                            $(event.target).html('<i class="fa fa-circle-o-notch fa-spin fa-4x"></i>');
                        },
                        success: function (response) {
                            if (response.type == 'success') {
                                $('#dataTable').DataTable().ajax.reload();
                                toastr.success(response.message);
                            } else {
                                setError(response.message);
                            }
                        }
                    });
                });

            }else{
                toastr.error('Agent ID or Status is required');
            }
        });
    });
</script>