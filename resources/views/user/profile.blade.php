@extends('web/common')
@section('content')

    <section class="inner-section single-banner" style="background: url({{asset('assests/web/images/single-banner.jpg')}}) no-repeat center;">
        <div class="container">
            <h2>{{$title}}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ol>
        </div>
    </section>

    <section class="inner-section profile-part">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="account-card">
                        <div class="account-title">
                            <h4>Basic Information</h4>
                            <button data-bs-toggle="modal" data-bs-target="#edit-profile" style="font-weight:bold;">Edit</button>
                        </div>
                        <div class="account-content">
                            <div class="row">
                                {{-- <div class="col-lg-2">
                                    <div class="profile-image">
                                        <a href="#"><img src="{{asset('assests/web/images/user.png')}}"></a>
                                    </div>
                                </div> --}}
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Title</label>
                                        <input class="form-control" type="text" id="fetch_title" value="@if(!empty($user_data->title)){{$user_data->title}}@else{{'N/A'}}@endif" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">First Name</label>
                                        <input class="form-control" type="text" id="fetch_fname" value="@if(!empty($user_data->fname)){{$user_data->fname}}@else{{'N/A'}}@endif" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="form-group">
                                        <label class="form-label">Last Name</label>
                                        <input class="form-control" type="text" id="fetch_lname" value="@if(!empty($user_data->lname)){{$user_data->lname}}@else{{'N/A'}}@endif" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-3">
                                    <div class="profile-btn">
                                        {{-- <a href="{{url('user/change-password')}}" style="font-weight:bold;">Change Password</a> --}}
                                        <button data-bs-toggle="modal" data-bs-target="#change-password" style="font-weight:bold;">Change Password</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="account-card">
                        <div class="account-title">
                            <h4>Contact Information</h4>
                        </div>
                        <div class="account-content">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 alert fade show">
                                    <div class="profile-card contact">
                                        <h6 style="font-weight:bold;">E-Mail ID:</h6>
                                        <p id="fetch_email">@if(!empty($user_data->email)){{$user_data->email}}@else{{'N/A'}}@endif</p>
                                        <ul>
                                            <li><button class="edit icofont-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-email"></button></li>
                                            {{-- <li><button class="trash icofont-ui-delete" title="Remove" data-bs-dismiss="alert"></button></li> --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 alert fade show">
                                    <div class="profile-card contact">
                                        <h6 style="font-weight:bold;">Phone No.:</h6>
                                        <p id="fetch_contact">@if(!empty($user_data->contact)){{$user_data->contact}}@else{{'N/A'}}@endif</p>
                                        <ul>
                                            <li><button class="edit icofont-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-phone"></button></li>
                                            {{-- <li><button class="trash icofont-ui-delete" title="Remove" data-bs-dismiss="alert"></button></li> --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 alert fade show">
                                    <div class="profile-card address">
                                        <h6 style="font-weight:bold;">Residential Address:</h6>
                                        <p id="fetch_raddress">
                                            @if(!empty($user_data->r_address))
                                                {{$user_data->r_address.', '.Helper::user_address_state($user_data->r_stateid).', '.$user_data->r_pincode.'.'}}
                                            @else
                                                {{'N/A'}}
                                            @endif
                                        </p>
                                        <ul>
                                            <li><button class="edit icofont-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-raddress"></button></li>
                                            {{-- <li><button class="trash icofont-ui-delete" title="Remove" data-bs-dismiss="alert"></button></li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="account-card">
                        <div class="account-title">
                            <h4>Delivery Information</h4>
                        </div>
                        <div class="account-content">
                            <div class="row">
                                <div class="col-md-6 col-lg-6 alert fade show">
                                    <div class="profile-card address">
                                        <h6 style="font-weight:bold;">Billing Address:</h6>
                                        <p id="fetch_baddress">
                                            @if(!empty($user_data->b_address))
                                                Name: {{$user_data->b_title}} {{$user_data->b_fname}} {{$user_data->b_lname}}<br>
                                                Contact No.: {{$user_data->b_contact}}<br>
                                                E-Mail ID: {{$user_data->b_email}}<br>
                                                Address: {{$user_data->b_address.', '.Helper::user_address_state($user_data->b_stateid).', '.$user_data->b_pincode.'.'}}
                                            @else
                                                {{'N/A'}}
                                            @endif
                                        </p>
                                        <ul class="user-action">
                                            <li><button class="edit icofont-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-baddress"></button></li>
                                            {{-- <li><button class="trash icofont-ui-delete" title="Remove" data-bs-dismiss="alert"></button></li> --}}
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6 alert fade show">
                                    <div class="profile-card address">
                                        <h6 style="font-weight:bold;">Shipping Address:</h6>
                                        <p id="fetch_saddress">
                                            @if(!empty($user_data->s_address))
                                                Name: {{$user_data->s_title}} {{$user_data->s_fname}} {{$user_data->s_lname}}<br>
                                                Contact No.: {{$user_data->s_contact}}<br>
                                                E-Mail ID: {{$user_data->s_email}}<br>
                                                Address: {{$user_data->s_address.', '.Helper::user_address_state($user_data->s_stateid).', '.$user_data->s_pincode.'.'}}
                                            @else
                                                {{'N/A'}}
                                            @endif
                                        </p>
                                        <ul class="user-action">
                                            <li><button class="edit icofont-edit" title="Edit" data-bs-toggle="modal" data-bs-target="#edit-saddress"></button></li>
                                            {{-- <li><button class="trash icofont-ui-delete" title="Remove" data-bs-dismiss="alert"></button></li> --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-lg-12">
                    <div class="account-card mb-0">
                        <div class="account-title">
                            <h4>payment option</h4><button data-bs-toggle="modal" data-bs-target="#payment-add">add card</button>
                        </div>
                        <div class="account-content">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 alert fade show">
                                    <div class="payment-card payment active"><img src="images/payment/png/01.png" alt="payment">
                                        <h4>card number</h4>
                                        <p><span>****</span><span>****</span><span>****</span><sup>1876</sup></p>
                                        <h5>miron mahmud</h5><button class="trash icofont-ui-delete" title="Remove This" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 alert fade show">
                                    <div class="payment-card payment"><img src="images/payment/png/02.png" alt="payment">
                                        <h4>card number</h4>
                                        <p><span>****</span><span>****</span><span>****</span><sup>1876</sup></p>
                                        <h5>miron mahmud</h5><button class="trash icofont-ui-delete" title="Remove This" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 alert fade show">
                                    <div class="payment-card payment"><img src="images/payment/png/03.png" alt="payment">
                                        <h4>card number</h4>
                                        <p><span>****</span><span>****</span><span>****</span><sup>1876</sup></p>
                                        <h5>miron mahmud</h5><button class="trash icofont-ui-delete" title="Remove This" data-bs-dismiss="alert"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <div class="modal fade" id="change-password">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Change Password</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Current Password</label>
                        <input class="form-control" type="password" id="write_cpass" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input class="form-control" type="password" id="write_npass" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Repeat Password</label>
                        <input class="form-control" type="password" id="write_rpass" minlength="6" required>
                    </div>
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="change_password();">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-profile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Update Basic Information</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <select class="form-select" id="write_title" required>
                            <option value="Mr." @if((!empty($user_data->title)) AND ($user_data->title == 'Mr.')){{'selected'}}@endif>Mr.</option>
                            <option value="Mrs." @if((!empty($user_data->title)) AND ($user_data->title == 'Mrs.')){{'selected'}}@endif>Mrs.</option>
                            <option value="Sri" @if((!empty($user_data->title)) AND ($user_data->title == 'Sri')){{'selected'}}@endif>Sri</option>
                            <option value="Smt." @if((!empty($user_data->title)) AND ($user_data->title == 'Smt.')){{'selected'}}@endif>Smt.</option>
                            <option value="Kumari" @if((!empty($user_data->title)) AND ($user_data->title == 'Kumari')){{'selected'}}@endif>Kumari</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input class="form-control" type="text" id="write_fname" value="@if(!empty($user_data->fname)){{$user_data->fname}}@endif" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input class="form-control" type="text" id="write_lname" value="@if(!empty($user_data->lname)){{$user_data->lname}}@endif" required>
                    </div>
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="update_name();">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-email">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Update E-Mail ID</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">E-Mail ID</label>
                        <input class="form-control" type="email" id="write_email" value="@if(!empty($user_data->email)){{$user_data->email}}@endif" required>
                    </div>
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="update_email();">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-phone">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Update Phone No.</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone No.</label>
                        <input class="form-control" type="text" id="write_contact" value="@if(!empty($user_data->contact)){{$user_data->contact}}@endif" required>
                    </div>
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="update_phone();">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-raddress">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Update Residential Address</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" id="write_raddress" required>@if(!empty($user_data->r_address)){{$user_data->r_address}}@endif</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">PIN Code</label>
                        <input class="form-control" type="text" id="write_rpincode" value="@if(!empty($user_data->r_pincode)){{$user_data->r_pincode}}@endif" required>
                    </div>
                    <input type="hidden" name="" value="1" id="write_rstate">
                    {{-- <div class="form-group">
                        <label class="form-label">State</label>
                        <select class="form-select" id="write_rstate" required>
                            @if($states_list->isNotEmpty())
                                @foreach($states_list as $state_data)
                                    <option value="{{$state_data->id}}" @if((!empty($user_data->r_stateid)) AND ($user_data->r_stateid == $state_data->id)){{'selected'}}@endif>{{$state_data->state_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div> --}}
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="update_raddress();">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-baddress">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Update Billing Address</h3>
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
                    <input id="write_bstate" type="hidden" name="write_bstate" value="1">
                    {{-- <div class="form-group">
                        <label class="form-label">State</label>
                        <select class="form-select" id="write_bstate" required>
                            @if($states_list->isNotEmpty())
                                @foreach($states_list as $state_data)
                                    <option value="{{$state_data->id}}" @if((!empty($user_data->b_stateid)) AND ($user_data->b_stateid == $state_data->id)){{'selected'}}@endif>{{$state_data->state_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div> --}}
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="update_baddress();">Update</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-saddress">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>Update Shipping Address</h3>
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
                    <input id="write_sstate" type="hidden" name="" value="1">
                    {{-- <div class="form-group">
                        <label class="form-label">State</label>
                        <select class="form-select" id="write_sstate" required>
                            @if($states_list->isNotEmpty())
                                @foreach($states_list as $state_data)
                                    <option value="{{$state_data->id}}" @if((!empty($user_data->s_stateid)) AND ($user_data->s_stateid == $state_data->id)){{'selected'}}@endif>{{$state_data->state_name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div> --}}
                    <button class="form-btn" style="font-weight:bold;" type="button" onclick="update_saddress();">Update</button>
                </form>
            </div>
        </div>
    </div>
    {{-- <div class="modal fade" id="payment-add">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"><button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                <form class="modal-form">
                    <div class="form-title">
                        <h3>add new payment</h3>
                    </div>
                    <div class="form-group"><label class="form-label">card number</label><input class="form-control" type="text" placeholder="Enter your card number"></div><button class="form-btn" type="submit">save card info</button>
                </form>
            </div>
        </div>
    </div> --}}

@endsection

@section('account-js')
    <script src="{{asset('assests/user/account.js')}}"></script>
@endsection