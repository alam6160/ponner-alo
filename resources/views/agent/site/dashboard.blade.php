@extends('agent.layout.layout')
@section('title', 'Dashboard')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">

        
        {{-- @if ($agent->status == '1')
            @if (!empty($agent->agent_prfile->amount))
            <div class="top-columns">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card bg-light mb-3">
                            <div class="card-body" id="applicationPayment_div">
                                @if (blank($applicationPayment))
                                <div class="filter-wrap">
                                    <h4>Activation Amount &#8377;{{ $agent->agent_prfile->amount }}</h4>
                                    <form class="form-wrap mt-3" id="payment_form" action="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <fieldset class="select-box">
                                                    <label for="payment_mode">Select Mode</label>
                                                    <select class="form-control" id="payment_mode" name="payment_mode">
                                                        <option value="1">Offline</option>
                                                        <option>Online</option>
                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-9">
                                                <fieldset>
                                                    <label for="remark">Remark <strong class="text-danger">*</strong></label>
                                                    <textarea class="form-control" rows="1" placeholder="Enter message" name="remark" id="remark" required></textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-lg-7">
                                                <fieldset>
                                                    <div class="form-group">
                                                        <label for="payment_proof">Upload Payment Proof <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                                        <div class="custom-file">
                                                            <input type="file" name="payment_proof" class="custom-file-input form-control" id="payment_proof" accept=".png,.jpeg,.jpg">
                                                            <label class="custom-file-label" for="payment_proof">Choose file</label>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset>
                                                    <label>&nbsp;</label>
                                                    <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                                </fieldset>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                                @else
                                    @if ($applicationPayment->status == '1')
                                        <h5>Your Request ID: {{ $applicationPayment->req_no }} & waiting approvation..</h5>
                                    @elseif ($applicationPayment->status == '3')
                                        <h5>Your Request ID: {{ $applicationPayment->req_no }}</h5>
                                        <p>Message : <strong class="text-danger">{{ $applicationPayment->cancel_remark }}</strong> </p>
                                        <div class="filter-wrap">
                                            <h4>Activation Amount &#8377;{{ $agent->agent_prfile->amount }}</h4>
                                            <form class="form-wrap mt-3" id="payment_form" action="POST" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <fieldset class="select-box">
                                                            <label for="payment_mode">Select Mode</label>
                                                            <select class="form-control" id="payment_mode" name="payment_mode">
                                                                <option value="1">Offline</option>
                                                                <option>Online</option> 
                                                            </select>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <fieldset>
                                                            <label for="remark">Remark <strong class="text-danger">*</strong></label>
                                                            <textarea class="form-control" rows="1" placeholder="Enter message" name="remark" id="remark" required></textarea>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <fieldset>
                                                            <div class="form-group">
                                                                <label for="payment_proof">Upload Payment Proof <small>(only jpg,jpeg,png or maximum file size 2MB)</small></label>
                                                                <div class="custom-file">
                                                                    <input type="file" name="payment_proof" class="custom-file-input form-control" id="payment_proof" accept=".png,.jpeg,.jpg">
                                                                    <label class="custom-file-label" for="payment_proof">Choose file</label>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <fieldset>
                                                            <label>&nbsp;</label>
                                                            <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                
                                            </form>
                                        </div>
                                    @else
                                        <h4 class="text-danger">
                                            Your ID is {{ $agent->code }} and currently Inactive <br> <small>please contact admin</small>
                                        </h4>
                                    @endif
                                    
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @elseif ($agent->status == '2')
            <div class="top-columns">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h4 class="text-success">
                                    Your ID is {{ $agent->code }} and currently Active</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif --}}

           
            {{-- <div class="top-columns">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card bg-light mb-3">
                            @if ( Auth::user()->vendor_type == '1')
                                <div class="card-body" id="applicationPayment_div">
                                    <div class="filter-wrap">
                                        <h4>Upgrade Account</h4>
                                        <form class="form-wrap mt-3" id="payment_form" action="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <fieldset>
                                                        <label>Do you want to upgrade your account?   
                                                        </label>
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-2">
                                                    <fieldset>
                                                        <label for="remark">Amount</label>
                                                        @if (blank($membership_price))
                                                            <input type="text" class="form-control" disabled>
                                                        @else
                                                            <input type="text" class="form-control" disabled value="{{ blank($membership_price->key_value) ? '0' : $membership_price->key_value }}">
                                                        @endif
                                                        
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-3">
                                                    <fieldset>
                                                        @if (blank($membership_price))
                                                            <button type="submit" name="submit" class="btn btn-primary btn-block" disabled>Upgrade</button>
                                                        @else
                                                            <button type="submit" name="submit" class="btn btn-primary btn-block" style="margin-top: 30px;">Upgrade</button>
                                                        @endif
                                                        
                                                    </fieldset>
                                                </div>
                                            </div>
                                            
                                        </form>
                                    </div>
                                </div>
                            @elseif (Auth::user()->vendor_type == '2')
                                <div class="card-body" id="applicationPayment_div">
                                    @if (!blank($subscriptionLog))
                                        <h5 class="text-success">Your subscription pack will be expired at {{ date('d-m-Y', strtotime($subscriptionLog->expaire_date)) }}</h5>
                                    @endif
                                    
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}

            @if (Auth::user()->vendor_type == '2')
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card bg-light mb-3">
                            @if (blank($subscriptionLog))
                                <div class="card-body" id="applicationPayment_div">
                                    <div class="filter-wrap">
                                        <h4>Upgrade Account</h4>
                                        <form class="form-wrap mt-3" id="payment_form" action="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <fieldset>
                                                        <label>Do you want to upgrade your account? <br>
                                                        </label>
                                                    </fieldset>
                                                    <fieldset>
                                                        <label for="remark">Amount</label>
                                                        <input type="text" class="form-control" disabled value="{{ $membership_price }}">
                                                   
                                                        <button type="submit" name="submit" class="btn btn-primary btn-block" style="margin-top: 30px;">Upgrade</button>
                                                    </fieldset>
                                                </div>
                                                
                                                
                                            
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="card-body" id="applicationPayment_div">
                                    <h5 class="text-success">Your subscription pack will be expired at {{ date('d-m-Y', strtotime($subscriptionLog->expaire_date)) }}</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            

            <div class="top-columns">
                <div class="row">
                    

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="bicycle-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countOrders }}</h3><small>Total Orders</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="bicycle-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countDeliveredOrders }}</h3><small>Total Delivery</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="bicycle-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countReturnOrders }}</h3><small>Total Returns</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="bicycle-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ Auth::user()->wallet }}</h3><small>Wallet</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="bicycle-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countProducts }}</h3><small>Total Products</small>
                            </div>
                        </div>
                    </div>

                </div>
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

                if ( confirm('Are You Sure') ) {
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
                                    /*
                                    const htmlString = `<h5 class="text-success">${response.text}</h5>`;
                                    $('#applicationPayment_div').html(htmlString);
                                    toastr.success(response.message); 
                                    url = '{{ route('agent.dashboard') }}'; */
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