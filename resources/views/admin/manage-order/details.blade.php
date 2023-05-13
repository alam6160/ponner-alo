@extends('admin.layout.layout')
@section('title', 'Order Details')
@section('content')

@php
    //dump($orderDetails);
    $ccurrency = Helper::ccurrency();
    $shipping_address = (empty($orderDetails->shipping_address)) ? [] : json_decode($orderDetails->shipping_address);
    $billing_address = (empty($orderDetails->billing_address)) ? [] : json_decode($orderDetails->billing_address);

@endphp

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Order No</th>
                        <th>Order Date</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                    </tr>
                    <tr>
                        <td>{{ $orderDetails->order_no }}</td>
                        <td>{{ date('d-m-Y', strtotime($orderDetails->order_date)) }}</td>
                        <td>{{ Helper::getOrderPaymentModel($orderDetails->payment_mode) }}</td>
                        <td>{{ Helper::getOrderStatus($orderDetails->status) }}</td>
                    </tr>
                    <tr>
                        <th>Customer Name</th>
                        <th>Customer Contact</th>
                        <th>Customer Email</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>{{ $orderDetails->customer->fname.' '.$orderDetails->customer->lname }}</td>
                        <td>{{ $orderDetails->customer->email }}</td>
                        <td>{{ $orderDetails->customer->contact }}</td>
                        <td></td>
                    </tr>
                    @if (!empty($orderDetails->coupon_id))
                        @php $coupon_desc = json_decode($orderDetails->coupon_desc); @endphp
                        <tr>
                            <th>Coupon Code</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>{{ $orderDetails->coupon_code }}</td>
                            <td>{{ Helper::getDiscountType($coupon_desc->discount_type) }}</td>
                            <td>{{ $coupon_desc->discount }}</td>
                            <td></td>
                        </tr>
                    @endif
                    
                    @if ($orderDetails->payment_mode == '2')
                        @php $shurjopayment_desc = json_decode($orderDetails->shurjopayment_desc); @endphp
                        <tr>
                            <th>Payment ID</th>
                            <th>Payable Amount</th>
                            <th>Method</th>
                            <th>Currency</th>
                        </tr>
                        <tr>
                            <td>{{ $orderDetails->shurjopayment_order_id }}</td>
                            <td>{{ $shurjopayment_desc->payable_amount }}</td>
                            <td>{{ $shurjopayment_desc->method }}</td>
                            <td>{{ $shurjopayment_desc->currency }}</td>
                        </tr>
                    @endif

                    @if ($orderDetails->status == '5')
                        <tr>
                            <th colspan="4" class="text-danger">Cancel Message</th>
                        </tr>
                        <tr>
                            <td colspan="4">{{ $orderDetails->cancel_remark }}</td>
                        </tr>
                    @endif

                    @if ($orderDetails->status == '6')
                        <tr>
                            <th colspan="3">Return Message</th>
                            <th>Return Status</th>
                        </tr>
                        <tr>
                            <td colspan="3">{{ $orderDetails->return_remark }}</td>
                            <td>{{ Helper::getOrderReturnStatus($orderDetails->return_status) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Vendor Name</th>
                        <th>Vendor Code</th>
                        <th>Wallet</th>
                        <th>Comission</th>
                        <th>Admin Charge</th>
                        <th>Qty x Price</th>
                        <th>Total</th>
                    </tr>
                    @foreach ($orderDetails->order_items as $item)
                        @php
                            $product_desc = json_decode($item->product_desc);
                        @endphp
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>
                                @if (!empty($product_desc))
                                    <img class="img-thumbnail" src="{{ asset($product_desc->product_image) }}" alt="" style="width: 80px; height: 60px;">
                                @endif
                                
                            </td>
                            @if (!empty($item->agent_id))
                                <td>{{ $item->vendor->fname.' '.$item->vendor->lname }}</td>
                                <td>{{ $item->vendor->code }}</td>
                                <td>{!! $ccurrency.''.$item->wallet !!}</td>
                                <td>{!! (empty($item->commission)) ? '' : $ccurrency.''.$item->commission !!}</td>
                                <td>{!! (empty($item->admin_charge)) ? '' : $ccurrency.''.$item->admin_charge !!}</td>
                            @else
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            @endif
                            <td>{!! $item->qty.' x '.$ccurrency.''.$item->sub_total !!}</td>
                            <td>{!! $ccurrency.''.$item->grand_total !!}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6"></th>
                        <th colspan="2">Sub Total</th>
                        <td>{!! $ccurrency.''.$orderDetails->sub_total !!}</td>
                    </tr>
                    @if ($orderDetails->delivery_charge > 0)
                    <tr>
                        <th colspan="6"></th>
                        <th colspan="2">Delivery Charge</th>
                        <td>{!! $ccurrency.''.$orderDetails->delivery_charge !!}</td>
                    </tr>
                    @endif
                    @if ($orderDetails->discount > 0)
                    <tr>
                        <th colspan="6"></th>
                        <th colspan="2">Discount</th>
                        <td>{!! $ccurrency.''.$orderDetails->discount !!}</td>
                    </tr>
                    @endif
                    <tr>
                        <th colspan="6"></th>
                        <th colspan="2">Grand Total</th>
                        <td>{!! $ccurrency.''.$orderDetails->grand_total !!}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <p><strong>Shipping Address</strong></p>
        @if (!empty($shipping_address))
            @php  if(isset($shipping_address->state_id)) {unset($shipping_address->state_id);} @endphp
            <address>
                <strong>Full Name : {{ $shipping_address->title.' '.$shipping_address->fname.' '.$shipping_address->lname }}</strong> <br>
                <strong>Email : {{ $shipping_address->email }}</strong> <br>
                <strong>Contact : {{ $shipping_address->contact }}</strong> <br>
                <strong>Alt contact : {{ $shipping_address->alt_contact }}</strong> <br>
                <strong>Address : {{ $shipping_address->address }}</strong> <br>
                <strong>Pin code : {{ $shipping_address->pin_code }}</strong> <br>
            </address>
        @endif
    </div>
    <div class="col-md-6">
        <p><strong>Shipping Address</strong></p>
        @if (!empty($billing_address))
            @php if(isset($billing_address->state_id)) {unset($billing_address->state_id);}  @endphp
            <address>
                <strong>Full Name : {{ $billing_address->title.' '.$billing_address->fname.' '.$billing_address->lname }}</strong> <br>
                <strong>Email : {{ $billing_address->email }}</strong> <br>
                <strong>Contact : {{ $billing_address->contact }}</strong> <br>
                <strong>Alt contact : {{ $billing_address->alt_contact }}</strong> <br>
                <strong>Address : {{ $billing_address->address }}</strong> <br>
                <strong>Pin code : {{ $billing_address->pin_code }}</strong> <br>
            </address>
        @endif
    </div>
</div>

@endsection