@extends('web.common')
@section('content')

@push('style')
<link rel="stylesheet" href="{{asset('assests/web/css/orderlist.css')}}">
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

<section class="inner-section orderlist-part">
    <div class="container">
        @if ($orderDetails->isNotEmpty())
            
        
        <div class="row">
            <div class="col-lg-12">
                <div class="orderlist-filter">
                    <h5>total order <span>- ({{ $totalorders }})</span></h5>
                    {{-- <div class="filter-short">
                        <label class="form-label">short by:</label>
                        <select class="form-select">
                            <option value="all" selected>all order</option>
                            <option value="recieved">recieved order</option>
                            <option value="processed">processed order</option>
                            <option value="shipped">shipped order</option>
                            <option value="delivered">delivered order</option>
                        </select>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @foreach ($orderDetails as $order)
                    @php
                        $shipping_address = (empty($order->shipping_address)) ? NULL : json_decode($order->shipping_address);
                    @endphp
                    <div class="orderlist">
                        <div class="orderlist-head">
                            <h5>order#{{ $loop->iteration }}</h5>
                            <h5>order recieved</h5>
                        </div>
                        <div class="orderlist-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="order-track">
                                        <ul class="order-track-list">
                                            @if ( intval($order->status) >= 1 && intval($order->status) <= 4)

                                                @php
                                                    $recieved_status = ''; 
                                                    $processed_status = ''; 
                                                    $shipped_status = '';
                                                    $delivered_status = '';

                                                    $recieved_icofont = 'icofont-close';
                                                    $processed_icofont = 'icofont-close';
                                                    $shipped_icofont = 'icofont-close';
                                                    $delivered_icofont = 'icofont-close';

                                                    $order_status = intval($order->status);
                                                    if ($order_status >= 1) {
                                                        $recieved_status = 'active';
                                                        $recieved_icofont = 'icofont-check';
                                                    }
                                                    if ($order_status >= 2) {
                                                        $processed_status = 'active';
                                                        $processed_icofont = 'icofont-check';
                                                    }
                                                    if ($order_status >= 3) {
                                                        $shipped_status = 'active';
                                                        $shipped_icofont = 'icofont-check';
                                                    }
                                                    if ($order_status >= 4) {
                                                        $delivered_status = 'active';
                                                        $delivered_icofont = 'icofont-check';
                                                    }
                                                @endphp
                                                <li class="order-track-item {{ $recieved_status }}">
                                                    <i class="{{ $recieved_icofont }}"></i><span>order recieved</span>
                                                </li>
                                                <li class="order-track-item {{ $processed_status }}">
                                                    <i class="{{ $processed_icofont }}"></i><span>order processed</span>
                                                </li>
                                                <li class="order-track-item {{ $shipped_status }}">
                                                    <i class="{{ $shipped_icofont }}"></i><span>order shipped</span>
                                                </li>
                                                <li class="order-track-item {{ $delivered_status }}">
                                                    <i class="{{ $delivered_icofont }}"></i><span>order delivered</span>
                                                </li>
                                            @elseif ($order->status == '5')
                                                <li class="order-track-item active">
                                                    <i class="icofont-check"></i><span>order recieved</span>
                                                </li>
                                                <li class="order-track-item active">
                                                    <i class="icofont-check"></i><span>order cancel</span>
                                                </li>
                                            @elseif ($order->status == '6')
                                                @php
                                                    // $recieved_status = ''; 
                                                    // $processed_status = ''; 

                                                    // $recieved_icofont = 'icofont-close';
                                                    // $processed_icofont = 'icofont-close';

                                                    // $order_status = intval($order->status);
                                                    // if ($order_status >= 1) {
                                                    //     $recieved_status = 'active';
                                                    //     $recieved_icofont = 'icofont-check';
                                                    // }
                                                    // if ($order_status >= 2) {
                                                    //     $processed_status = 'active';
                                                    //     $processed_icofont = 'icofont-check';
                                                    // }
                                                @endphp

                                                <li class="order-track-item active">
                                                    <i class="icofont-check"></i><span>reurn recieved</span>
                                                </li>
                                                @if ($order->return_status == '2')
                                                <li class="order-track-item active">
                                                    <i class="icofont-check"></i><span>reurn approved</span>
                                                </li>
                                                @elseif ($order->return_status == '3')
                                                <li class="order-track-item active">
                                                    <i class="icofont-check"></i><span>reurn reject</span>
                                                </li>
                                                @endif
                                            @endif
                                            
                                        </ul>
                                    </div>
                                </div>
                                @if ($order->status == '5')
                                <div class="col-lg-12">
                                    <ul class="orderlist-details">
                                        <p><strong>Why Cancel Order</strong></p>
                                        {{ $order->cancel_remark }}
                                    </ul>
                                </div>
                                @endif
                                
                                <div class="col-lg-5">
                                    <ul class="orderlist-details">
                                        <li>
                                            <h6>order id</h6>
                                            <p>{{ $order->order_no }}</p>
                                        </li>
                                        <li>
                                            <h6>Total Item</h6>
                                            <p>{{ $order->order_items->count() }}</p>
                                        </li>
                                        
                                    </ul>
                                </div>
                                <div class="col-lg-4">
                                    <ul class="orderlist-details">
                                        <li>
                                            <h6>Sub Total</h6>
                                            <p>{!! Helper::ccurrency().''.$order->sub_total !!}</p>
                                        </li>
                                        @if ($order->discount > 0)
                                        <li>
                                            <h6>discount</h6>
                                            <p>{!! Helper::ccurrency().''.$order->discount !!}</p>
                                        </li>
                                        @endif
                                        
                                        {{-- <li>
                                            <h6>delivery fee</h6>
                                            <p>$49.00</p>
                                        </li> --}}
                                        <li>
                                            <h6>Total</h6>
                                            <p>{!! Helper::ccurrency().''.$order->grand_total !!}</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-3">
                                    <div class="orderlist-deliver">
                                        <h6>Delivery location</h6>
                                        @if (!empty($shipping_address))
                                            <p>{{ $shipping_address->address }} <br> Pincode : {{ $shipping_address->pin_code }}</p>
                                        @endif
                                        
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
                                                @foreach ($order->order_items as $ikey => $order_item)
                                                    @php
                                                        $product_desc = (empty($order_item->product_desc)) ? NULL : json_decode($order_item->product_desc);

                                                        $product = \App\Models\Product::select(['id','slug'])->withTrashed()->find($order_item->product_id);
                                                    @endphp
                                                    <tr>
                                                        <td class="table-serial"><h6>{{ ($ikey+1) }}</h6></td>
                                                        <td class="table-image">
                                                            @if (!empty($product_desc))
                                                                <a href="{{url('/')}}/product/{{$product->slug}}" target="blank">
                                                                    <img src="{{ asset($product_desc->product_image) }}" alt="product" />
                                                                </a>
                                                            @endif
                                                        </td>
                                                        <td class="table-name">
                                                            <a href="{{url('/')}}/product/{{$product->slug}}" target="blank">
                                                                <h6>{{ $order_item->product_name }}</h6>
                                                            </a>
                                                        </td>
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

                                
                                @if ($order->status == '4')

                                    @php
                                        
                                        $delivery_date = date('Y-m-d', strtotime($order->delivery_date));
                                        $return_last_date = date('Y-m-d', strtotime($order->return_last_date));
                                    @endphp

                                    @if ( ($delivery_date >= date('Y-m-d')) && (date('Y-m-d') <= $return_last_date  ))
                                        <div class="col-md-2 mb-3">
                                            <div class="profile-btn">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#profileedit{{ $order->id }}">Return Order</button>
                                            </div>
        
                                            <div class="modal fade" id="profileedit{{ $order->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <button class="modal-close" data-bs-dismiss="modal"><i class="icofont-close"></i></button>
                                                        <form class="modal-form" id="return_form_{{ $order->id }}">
                                                            @csrf
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <div class="form-title"><h3>Return Order</h3></div>
                                                            <div class="form-group">
                                                                <label class="form-label" for="return_remark{{ $order->id }}">Why Return Order <strong>*</strong> </label>
                                                                <textarea name="return_remark" id="return_remark{{ $order->id }}" cols="3" class="form-control" required></textarea>
                                                            </div>
                                                            {{-- <button class="form-btn" value="{{ $order->id }}" onclick="returnOrderSubmit(this)" type="button">Return</button> --}}
                                                            <button class="form-btn" id="submitbutton_{{ $order->id }}" value="{{ $order->id }}" onclick="returnOrderSubmit(this)" type="button">Return</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
        
                                        </div>
                                    @endif

                                
                                @endif
                                
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                {{$orderDetails->links()}}
            </div>
        </div>
        @else
        <div class="row">
            <div class="col-lg-12">No Orders</div>
        </div>
        @endif


        {{-- <div class="row">
            <div class="col-lg-12">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="icofont-arrow-left"></i></a>
                    </li>
                    <li class="page-item"><a class="page-link active" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">...</li>
                    <li class="page-item"><a class="page-link" href="#">65</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#"><i class="icofont-arrow-right"></i></a>
                    </li>
                </ul>
            </div>
        </div> --}}
    </div>
</section>

@push('script')
    <script>
        function returnOrderSubmit(evt) {
            //event.preventDefault();
            id = evt.value;
            buttonid = '#'+evt.id;

            formData = new FormData( $('#return_form_'+id)[0] );
            $.ajax({
                type: "POST",
                url: "{{ route('user.order.returnorder') }}",
                data: formData,
                dataType: "JSON",
                cache: false,
                processData: false,
                contentType: false,
                beforeSend: function(){
                    $(buttonid).prop('disabled', true);
                    $(buttonid).html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing');
                },
                success: function (response) {
                    if (response.type == 'success') {
                        $('#return_form_'+id)[0].reset();
                        alert(response.message);
                        window.location.reload();
                    } else {
                        $(buttonid).prop('disabled', false);
                        $(buttonid).text('Return');
                        if (Array.isArray(response.message)) {
                            response.message.forEach(function (val) { toastr.error(val); });
                        } else { toastr.error(response.message); }
                    }
                }
            });
        }
    </script>
@endpush


@endsection