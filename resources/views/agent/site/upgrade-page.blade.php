@extends('agent.layout.layout')
@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card bg-light mb-3">
            <div class="card-header">Upgrade Details</div>
            <div class="card-body">
                <div>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis officiis incidunt consequuntur beatae voluptatum distinctio, ea qui possimus numquam eum maiores obcaecati odit consectetur suscipit magni facere amet quo tempore voluptatibus. Reiciendis laborum totam doloremque magnam consequatur doloribus ea sit maxime sed. Laudantium quod quis dolor unde fugiat natus ex? <br>
                    

                    <blockquote class="blockquote">
                        <p class="mb-0">Lorem ipsum dolor sit amet consectetur adipisicing elit. Corporis officiis incidunt consequuntur beatae voluptatum distinctio, ea qui possimus numquam eum maiores obcaecati odit consectetur suscipit magni facere amet quo tempore voluptatibus. Reiciendis laborum totam doloremque magnam consequatur doloribus ea sit maxime sed. Laudantium quod quis dolor unde fugiat natus ex?</p>
                    </blockquote>
                </div>

                <form action="" method="post" id="payment_form" action="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-3">
                            <fieldset>
                                <label for="remark">Amount</label>
                                <input type="text" class="form-control" disabled value="{{ $membership_price }}">
                                
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <fieldset>
                                {{-- <label>&nbsp;</label> --}}
                                <button type="submit" name="submit" class="btn btn-primary btn-block" style="margin-top: 22px;">Upgrade</button>
                                
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){
            $('#payment_form').parsley();
            $('#payment_form').submit(function (e) { 
                e.preventDefault();

                if (confirm('Are You Sure')) {
                    if ($('#payment_form').parsley().isValid()) {
                        const formId = 'payment_form';
                        $.ajax({
                            type: "POST",
                            url: "{{ route('agent.upgrade.index') }}",
                            data: new FormData(this),
                            dataType: "JSON",
                            cache: false,
                            contentType: false,
                            processData: false,
                            beforeSend: function(){
                                $('#'+formId+' [name=submit]').html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                                $('#'+formId+' [name=submit]').prop('disabled', true);
                            },
                            success: function (response) {
                                if (response.type == 'success') {
                                    alert(response.message);
                                    window.location.replace(response.url);
                                } else {
                                    $('#'+formId+' [name=submit]').prop('disabled', false);
                                    $('#'+formId).parsley().reset();
                                    $('#'+formId+' [name=submit]').text('Upgrade');
                                    setError(response.message);
                                }
                            }
                        });
                    }
                }
                
            });


        });
    </script>
@endpush

@endsection
