@extends('statehead.layout.layout')
@section('title', 'Dashboard')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
        @if ($statehead->status == '1')
            @if (!empty($statehead->statehead_prfile->amount))
            <div class="top-columns">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card bg-light mb-3">
                            <div class="card-body" id="applicationPayment_div">
                                @if (blank($applicationPayment))
                                <div class="filter-wrap">
                                    <h4>Activation Amount &#8377;{{ $statehead->statehead_prfile->amount }}</h4>
                                    <form class="form-wrap mt-3" id="payment_form" action="POST" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <fieldset class="select-box">
                                                    <label for="payment_mode">Select Mode</label>
                                                    <select class="form-control" id="payment_mode" name="payment_mode">
                                                        <option value="1">Offline</option>
                                                        {{-- <option>Online</option> --}}
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
                                        <h4>Activation Amount &#8377;{{ $statehead->statehead_prfile->amount }}</h4>
                                        <form class="form-wrap mt-3" id="payment_form" action="POST" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <fieldset class="select-box">
                                                        <label for="payment_mode">Select Mode</label>
                                                        <select class="form-control cfcrl" id="payment_mode" name="payment_mode">
                                                            <option value="1">Offline</option>
                                                            {{-- <option>Online</option> --}}
                                                        </select>
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-9">
                                                    <fieldset>
                                                        <label for="remark">Remark <strong class="text-danger">*</strong></label>
                                                        <textarea class="form-control cfcrl" rows="1" placeholder="Enter message" name="remark" id="remark" required></textarea>
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
                                            Your ID is {{ $statehead->code }} and currently Inactive <br> <small>please contact admin</small>
                                        </h4>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @elseif ($statehead->status == '2')
            <div class="top-columns">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h4 class="text-success">
                                    Your ID is {{ $statehead->code }} and currently Active</small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
            

            <div class="top-columns">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="bicycle-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>3,256</h3><small>Total Cutomers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>3,256</h3><small>PIN Codes Covered</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>3,256</h3><small>Weekly Orders</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>3,256</h3><small>Monthly Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>3,256</h3><small>Monthly Delivery</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>3,256</h3><small>Monthly Returns</small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Top Columns End -->
            <div class="charts-wrap">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bg-light mb-3">
                            <div class="card-header">Daily Orders</div>
                            <div class="card-body">
                                <img src="{{ asset('assests/theme/images/chart-2.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card bg-light mb-3">
                            <div class="card-header">Daily Customers Signups</div>
                            <div class="card-body">
                                <img src="{{ asset('assests/theme/images/chart-2.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card bg-light mb-3">
                            <div class="card-header">Monthly Sales</div>
                            <div class="card-body">
                                <img src="{{ asset('assests/theme/images/chart-2.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Charts Wrap End -->
            <div class="table-wrap">
                <div class="card bg-light mb-3">
                    <div class="card-header">New HSP Application</div>
                    <div class="card-body">
                        <div class="table-wrap">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Agent Details</th>
                                        <th scope="col">Contact Details</th>
                                        <th scope="col">Available Pincodes</th>
                                        <th scope="col">Address</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Mark Wilium<br>
                                            Company Name<br>
                                        </td>
                                        <td>
                                            +91-1234567890<br>
                                            agentemail@gmail.com
                                        </td>
                                        <td>123456, 123456, 123456</td>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Mark Wilium<br>
                                            Company Name<br>
                                        </td>
                                        <td>
                                            +91-1234567890<br>
                                            agentemail@gmail.com
                                        </td>
                                        <td>123456, 123456, 123456</td>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Mark Wilium<br>
                                            Company Name<br>
                                        </td>
                                        <td>
                                            +91-1234567890<br>
                                            agentemail@gmail.com
                                        </td>
                                        <td>123456, 123456, 123456</td>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <!-- Charts Wrap End -->
            <div class="table-wrap">
                <div class="card bg-light mb-3">
                    <div class="card-header">New Retail Application</div>
                    <div class="card-body">
                        <div class="table-wrap">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Agent Details</th>
                                        <th scope="col">Contact Details</th>
                                        <th scope="col">Available Pincodes</th>
                                        <th scope="col">Address</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            Mark Wilium<br>
                                            Company Name<br>
                                        </td>
                                        <td>
                                            +91-1234567890<br>
                                            agentemail@gmail.com
                                        </td>
                                        <td>123456, 123456, 123456</td>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Mark Wilium<br>
                                            Company Name<br>
                                        </td>
                                        <td>
                                            +91-1234567890<br>
                                            agentemail@gmail.com
                                        </td>
                                        <td>123456, 123456, 123456</td>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Mark Wilium<br>
                                            Company Name<br>
                                        </td>
                                        <td>
                                            +91-1234567890<br>
                                            agentemail@gmail.com
                                        </td>
                                        <td>123456, 123456, 123456</td>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
            <!-- Charts Wrap End -->
            <div class="table-wrap">
                <div class="card bg-light mb-3">
                    <div class="card-header">New RX Orders</div>
                    <div class="card-body">
                        <div class="table-wrap">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Date & Time</th>
                                        <th scope="col">RX</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">
                                            Customer Name<br>
                                            +91-7125369585<br>
                                            email@gmail.com
                                        </th>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>5th June 9:50am</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                        <td>
                                            <a href=""><i class="fa fa-cart-plus fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Customer Name<br>
                                            +91-7125369585<br>
                                            email@gmail.com
                                        </th>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>5th June 9:50am</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                        <td>
                                            <a href=""><i class="fa fa-cart-plus fa-lg"></i></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">
                                            Customer Name<br>
                                            +91-7125369585<br>
                                            email@gmail.com
                                        </th>
                                        <td>Tribeni Kalitala Bazar, Hooghly, WB - 712503</td>
                                        <td>5th June 9:50am</td>
                                        <td>
                                            <a href=""><i class="fa fa-eye fa-lg"></i></a>
                                        </td>
                                        <td>
                                            <a href=""><i class="fa fa-cart-plus fa-lg"></i></a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>

</div>

@push('script')
    <script>
        $(function(){
            $('#payment_form').parsley();
            $('#payment_form').submit(function (e) { 
                e.preventDefault();
                if ($('#payment_form').parsley().isValid()) {
                    const formID = 'payment_form';
                    $.ajax({
                        type: "POST",
                        url: "{{ route('statehead.apply-payment') }}",
                        data: new FormData(this),
                        dataType: "JSON",
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend: function(){
                            setSubmitButton(formID);
                        },
                        success: function (response) {
                            if (response.type == 'success') {

                                const htmlString = `<h5>Your Request ID: ${response.applicationPayment.req_no} & waiting approvation..</h5>`;
                                $('#applicationPayment_div').html(htmlString);

                                /*
                                $('#'+formID+' [name=submit]').prop('disabled', false);
                                $('#'+formID+' [name=submit]').text('Submit');
                                $('#'+formID).parsley().reset(); 
                                $('#'+formID)[0].reset();
                                */

                                toastr.success(response.message);
                            } else {
                                setRestButton(formID, 'submit');
                                setError(response.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
@endsection