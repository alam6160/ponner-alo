@extends('admin.layout.layout')
@section('title', 'Dashboard')
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="top-columns">
                <div class="row">
                    
                    
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countVendors }}</h3><small>Total Vendors</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countRegVendors }}</h3><small>Total Regular Vendors</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countSubVendors }}</h3><small>Total Subscriber Vendors</small>
                            </div>
                        </div>
                    </div>

                    

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countOrders }}</h3><small>Total Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="item-title">
                                <h3>{{ $countDeliveredOrders }}</h3><small>Total Delivery</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-12 mb-2">
                        <div class="col-item">
                            <div class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
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
                                <h3>{{ $countCustomers }}</h3><small>Total Cutomers</small>
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
            <!-- Top Columns End -->
            {{-- <div class="charts-wrap">
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
            </div> --}}
            {{-- <!-- Charts Wrap End -->
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
            </div> --}}
            <!--  -->
        </div>
    </div>

</div>
@endsection