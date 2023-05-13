@extends('web.common')
@section('content')

@push('style')
    <link rel="stylesheet" href="{{asset('assests/web/css/checkout.css')}}">
@endpush

<section class="inner-section single-banner" style="background: url({{asset('assests/web/images/single-banner.jpg')}}) no-repeat center;">
    <div class="container">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </div>
</section>

<section class="inner-section checkout-part">
    <div class="container">
        <div class="row">
            
            <div class="col-lg-12">
                <div class="account-card">
                    <div class="account-title"><h4>Your order</h4></div>
                    <div class="account-content">
                        <div class="table-scroll">
                            <table class="table-list">
                                <thead>
                                    <tr>
                                        <th scope="col">Serial</th>
                                        <th scope="col">Product</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">quantity</th>
                                        <th scope="col">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                    <tr>
                                        <td class="table-serial"><h6>{{ $loop->iteration }}</h6></td>
                                        <td class="table-image">
                                            <img src="{{ asset($item->attributes->product_image) }}" />
                                        </td>
                                        <td class="table-name"><h6>{{ $item->name }}</h6></td>
                                        <td class="table-price">
                                            <h6>{!! Helper::ccurrency().''.$item->price !!}</h6>
                                        </td>
                                        <td class="table-quantity"><h6>{{ $item->quantity }}</h6></td>
                                        <td class="table-action">
                                            <h6>{!! Helper::ccurrency().''.$item->getPriceSum() !!}</h6>
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="chekout-coupon">
                            {{-- <button class="coupon-btn">Do you have a coupon code?</button>
                            <form class="coupon-form">
                                <input type="text" placeholder="Enter your coupon code" /><button type="submit"><span>apply</span></button>
                            </form> --}}
                        </div>
                        <div class="checkout-charge">
                            <ul>
                                <li><span>Sub total</span><span>{!! Helper::ccurrency().''.$subTotal !!}</span></li>
                                {{-- <li><span>delivery fee</span><span>$10.00</span></li>  --}}
                                @if (!blank($cartCondition))
                                    <li><span>discount</span><span>{!! Helper::ccurrency().''.$cartCondition->getValue() !!}</span></li>
                                @endif
                                
                                <li>
                                    <span>Total</span><span>{!! Helper::ccurrency().''.$total !!}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="col-lg-12">
                <div class="account-card">
                    <div class="account-title">
                        <h4>delivery address</h4>
                    </div>
                    <div class="account-content">
                        <div class="row">
                            <div class="col-md-6 col-lg-4 alert fade show">
                                <div class="profile-card address active">
                                    <h6 style="font-weight:bold;">Billing Address:</h6>
                                    <p>
                                        @if(!empty($user_data->b_address))
                                            Name: {{$user_data->b_title}} {{$user_data->b_fname}} {{$user_data->b_lname}}<br>
                                            Contact No.: {{$user_data->b_contact}}<br>
                                            E-Mail ID: {{$user_data->b_email}}<br>
                                            Address: {{$user_data->b_address.', '.Helper::user_address_state($user_data->b_stateid).', '.$user_data->b_pincode.'.'}}
                                        @else
                                            {{'N/A'}}
                                        @endif
                                    </p>
                                    {{-- <ul class="user-action">
                                        <li><button class="edit icofont-edit" title="Edit This" data-bs-toggle="modal" data-bs-target="#view-baddress"></button></li>
                                    </ul> --}}
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 alert fade show">
                                <div class="profile-card address">
                                    <h6 style="font-weight:bold;">Shipping Address:</h6>
                                    <p>
                                        @if(!empty($user_data->s_address))
                                            Name: {{$user_data->s_title}} {{$user_data->s_fname}} {{$user_data->s_lname}}<br>
                                            Contact No.: {{$user_data->s_contact}}<br>
                                            E-Mail ID: {{$user_data->s_email}}<br>
                                            Address: {{$user_data->s_address.', '.Helper::user_address_state($user_data->s_stateid).', '.$user_data->s_pincode.'.'}}
                                        @else
                                            {{'N/A'}}
                                        @endif
                                    </p>
                                    {{-- <ul class="user-action">
                                        <li><button class="edit icofont-edit" title="Edit This" data-bs-toggle="modal" data-bs-target="#view-saddress"></button></li>
                                        
                                    </ul> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="" method="post" id="checkout_form">
                <div class="col-lg-12">
                    <div class="account-card mb-0">
                        <div class="account-title">
                            <h4>payment option</h4>
                        </div>
                        
                        <div class="checkout-check">
                            <input type="radio" id="cod" value="1" name="payment_mode" required /><label for="cod">Cash On Delivery</label>
                            <input type="radio" style="margin-left: 15px;" id="online" value="2" name="payment_mode" required /><label for="online">Online Payment</label>
                        </div>
                        <div class="checkout-proced">
                            <button type="submit" name="submit" class="btn btn-inline">proced to checkout</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<div class="modal fade" id="view-baddress">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
            <form class="modal-form">
                <div class="form-title">
                    <h3>Billing Address</h3>
                </div>
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <select class="form-select" id="write_btitle" required>
                        <option value="Mr." @if((!empty($user_data->b_title)) AND ($user_data->b_title == 'Mr.')){{'selected'}}@endif>Mr.</option>
                        <option value="Mrs." @if((!empty($user_data->b_title)) AND ($user_data->b_title == 'Mrs.')){{'selected'}}@endif>Mrs.</option>
                        <option value="Sri" @if((!empty($user_data->b_title)) AND ($user_data->b_title == 'Sri')){{'selected'}}@endif>Sri</option>
                        <option value="Smt." @if((!empty($user_data->b_title)) AND ($user_data->b_title == 'Smt.')){{'selected'}}@endif>Smt.</option>
                        <option value="Kumari" @if((!empty($user_data->b_title)) AND ($user_data->b_title == 'Kumari')){{'selected'}}@endif>Kumari</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input class="form-control" type="text" id="write_bfname" value="@if(!empty($user_data->b_fname)){{$user_data->b_fname}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input class="form-control" type="text" id="write_blname" value="@if(!empty($user_data->b_lname)){{$user_data->b_lname}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">E-Mail ID</label>
                    <input class="form-control" type="email" id="write_bemail" value="@if(!empty($user_data->b_email)){{$user_data->b_email}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Contact No.</label>
                    <input class="form-control" type="text" id="write_bcontact" value="@if(!empty($user_data->b_contact)){{$user_data->b_contact}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" id="write_baddress" required>@if(!empty($user_data->b_address)){{$user_data->b_address}}@endif</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">PIN Code</label>
                    <input class="form-control" type="text" id="write_bpincode" value="@if(!empty($user_data->b_pincode)){{$user_data->b_pincode}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <select class="form-select" id="write_bstate" required>
                        @if($states_list->isNotEmpty())
                            @foreach($states_list as $state_data)
                                <option value="{{$state_data->id}}" @if((!empty($user_data->b_stateid)) AND ($user_data->b_stateid == $state_data->id)){{'selected'}}@endif>{{$state_data->state_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="view-saddress">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
            <form class="modal-form">
                <div class="form-title">
                    <h3>Shipping Address</h3>
                </div>
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <select class="form-select" id="write_stitle" required>
                        <option value="Mr." @if((!empty($user_data->s_title)) AND ($user_data->s_title == 'Mr.')){{'selected'}}@endif>Mr.</option>
                        <option value="Mrs." @if((!empty($user_data->s_title)) AND ($user_data->s_title == 'Mrs.')){{'selected'}}@endif>Mrs.</option>
                        <option value="Sri" @if((!empty($user_data->s_title)) AND ($user_data->s_title == 'Sri')){{'selected'}}@endif>Sri</option>
                        <option value="Smt." @if((!empty($user_data->s_title)) AND ($user_data->s_title == 'Smt.')){{'selected'}}@endif>Smt.</option>
                        <option value="Kumari" @if((!empty($user_data->s_title)) AND ($user_data->s_title == 'Kumari')){{'selected'}}@endif>Kumari</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input class="form-control" type="text" id="write_sfname" value="@if(!empty($user_data->s_fname)){{$user_data->s_fname}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input class="form-control" type="text" id="write_slname" value="@if(!empty($user_data->s_lname)){{$user_data->s_lname}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">E-Mail ID</label>
                    <input class="form-control" type="email" id="write_semail" value="@if(!empty($user_data->s_email)){{$user_data->s_email}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Contact No.</label>
                    <input class="form-control" type="text" id="write_scontact" value="@if(!empty($user_data->s_contact)){{$user_data->s_contact}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Address</label>
                    <textarea class="form-control" id="write_saddress" required>@if(!empty($user_data->s_address)){{$user_data->s_address}}@endif</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">PIN Code</label>
                    <input class="form-control" type="text" id="write_spincode" value="@if(!empty($user_data->s_pincode)){{$user_data->s_pincode}}@endif" required>
                </div>
                <div class="form-group">
                    <label class="form-label">State</label>
                    <select class="form-select" id="write_sstate" required>
                        @if($states_list->isNotEmpty())
                            @foreach($states_list as $state_data)
                                <option value="{{$state_data->id}}" @if((!empty($user_data->s_stateid)) AND ($user_data->s_stateid == $state_data->id)){{'selected'}}@endif>{{$state_data->state_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>
        $(function(){

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#checkout_form').submit(function (e) { 
                e.preventDefault();
                formData = new FormData(this);
                formID = 'checkout_form';
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.checkout.submitcheckout') }}",
                    data: formData,
                    dataType: "JSON",
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        setProcessingButton(formID);
                    },
                    success: function (response) {
                        if (response.type == 'success') {
                            //toastr.success(response.message);
                            alert(response.message);
                            window.location.replace(response.url);
                        } else {
                            resetButton(formID, 'proced to checkout');
                            setErrorMessage(response);
                        }
                    }
                });
            });
        });
    </script>
@endpush

@endsection