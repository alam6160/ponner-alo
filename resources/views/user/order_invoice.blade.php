@extends('web.common')
@section('content')

@push('style')
<link rel="stylesheet" href="{{asset('assests/web/css/invoice.css')}}">
@endpush

@php
    //dump($orderDetails);
    $shipping_address = (empty($orderDetails->shipping_address)) ? NULL : json_decode($orderDetails->shipping_address);
@endphp

<section class="inner-section single-banner" style="background: url({{asset('assests/web/images/single-banner.jpg')}}) no-repeat center;">
    <div class="container">
        <h2>{{$title}}</h2>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
        </ol>
    </div>
</section>

<section class="inner-section invoice-part">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="alert-info"><p>Thank you! We have recieved your order.</p></div>
            </div>
            <div class="col-lg-12">
                <div class="account-card">
                    <div class="account-title"><h4>order recieved</h4></div>
                    <div class="account-content">
                        <div class="invoice-recieved">
                            <h6>order number <span>{{ $orderDetails->order_no }}</span></h6>
                            <h6>order date <span>{{ date('F j, Y', strtotime($orderDetails->order_date) ) }}</span></h6>
                            <h6>total amount <span>{!! Helper::ccurrency().''.$orderDetails->grand_total !!}</span></h6>
                            <h6>payment method <span>{{ Helper::getOrderPaymentModel($orderDetails->payment_mode) }}</span></h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="account-card">
                    <div class="account-title"><h4>Shipping Address</h4></div>
                    <div class="account-content">
                        @if (!empty($shipping_address))
                        <ul class="invoice-details">
                            <li>
                                <h6>Full Name</h6>
                                <p>{{ $shipping_address->title.' '.$shipping_address->fname.' '.$shipping_address->lname }}</p>
                            </li>
                            <li>
                                <h6>Email</h6>
                                <p>{{ $shipping_address->email }}</p>
                            </li>
                            <li>
                                <h6>Contact</h6>
                                <p>{{ $shipping_address->contact }}</p>
                            </li>
                            <li>
                                <h6>Address</h6>
                                <p>{{ $shipping_address->address }}</p>
                            </li>
                            <li>
                                <h6>Pincode</h6>
                                <p>{{ $shipping_address->pin_code }}</p>
                            </li>
                        </ul>
                        @endif
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="account-card">
                    <div class="account-title"><h4>Amount Details</h4></div>
                    <div class="account-content">
                        <ul class="invoice-details">
                            <li>
                                <h6>Sub Total</h6>
                                <p>{!! Helper::ccurrency().''.$orderDetails->sub_total !!}</p>
                            </li>
                            @if ($orderDetails->discount > 0)
                            <li>
                                <h6>discount</h6>
                                <p>{!! Helper::ccurrency().''.$orderDetails->discount !!}</p>
                            </li>
                            @endif
                            
                            <li>
                                <h6>Payment Method</h6>
                                <p>{{ Helper::getOrderPaymentModel($orderDetails->payment_mode) }}</p>
                            </li>
                            <li>
                                <h6>Total</h6>
                                <p>{!! Helper::ccurrency().''.$orderDetails->grand_total !!}</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-scroll">
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th scope="col">Serial</th>
                                <th scope="col">Product</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails->order_items as $order_item)
                            @php
                                $product_desc = (empty($order_item->product_desc)) ? NULL : json_decode($order_item->product_desc);
                            @endphp
                            <tr>
                                <td class="table-serial"><h6>{{ $loop->iteration }}</h6></td>
                                <td class="table-image">
                                    @if (!empty($product_desc))
                                        <img src="{{ asset($product_desc->product_image) }}" alt="product" />
                                    @endif
                                </td>
                                <td class="table-name"><h6>{{ $order_item->product_name }}</h6></td>
                                <td class="table-price">
                                    <h6>{!! Helper::ccurrency().''.$order_item->grand_total !!}</h6>
                                </td>
                                <td class="table-quantity"><h6>{{ $order_item->qty }}</h6></td>
                            </tr>
                            @endforeach
                            
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center mt-5">
                {{-- <a class="btn btn-inline" href="#"><i class="icofont-download"></i><span>download invoice</span></a> --}}
                <div class="back-home"><a href="{{url('/')}}">Back to Home</a></div>
            </div>
        </div>
    </div>
</section>

@endsection