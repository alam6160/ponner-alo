@extends('fontend.layout.layout')
@section('title', 'Join Us')

@section('content')

@push('style')
<link rel="stylesheet" href="{{ asset('assests/parsley/parsley.css') }}">
<link rel="stylesheet" href="{{ asset('assests/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assests/select2/select2.min.css') }}">

<style>
    .select2-container .select2-search--inline .select2-search__field{
        height: 32px;
    }
</style>
@endpush

<main id="main">
    <section class="breadcrumbs">
        <div class="container"></div>
    </section><!-- End Breadcrumbs Section -->
    <section id="appointment" class="appointment section-bg">
        <div class="container">

            <div class="section-title">
                <h2>Join Us</h2>
                <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>

            <form action="" method="post" class="php-email-form" id="appl_form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="title">Select Title <strong class="text-danger">*</strong></label>
                            <select id="title" class="form-select" name="title">
                                <option value="Mr.">Mr.</option>
                                <option value="Mrs.">Mrs.</option>
                                <option value="Sri">Sri</option>
                                <option value="Smt.">Smt.</option>
                                <option value="Kumari">Kumari</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="fname">First Name <strong class="text-danger">*</strong></label>
                            <input id="fname" class="form-control" type="text" name="fname" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="lname">Last Name <strong class="text-danger">*</strong></label>
                            <input id="lname" class="form-control" type="text" name="lname" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="email">Email <strong class="text-danger">*</strong></label>
                            <input id="email" class="form-control" type="email" name="email" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="contact">Phone No. <strong class="text-danger">*</strong></label>
                            <input id="contact" class="form-control" type="tel" name="contact" minlength="10" maxlength="10" pattern="/^[0-9]{10}$/" onkeypress="return onlyNumberKey(event)" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="department">Select Department </label>
                            <select id="department" class="form-select" name="department" onchange="setJoinUsForm()">
                                <option value="hsp">HSP</option>
                                {{-- <option value="retailer">Retailer</option> --}}
                                <option value="statehead">Statehead</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="form-group">
                            <label for="organization_name">Address </label>
                            <textarea name="address" id="address" rows="1" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4 mb-2">
                        <div class="form-group">
                            <label for="state_id">Select State </label>
                            <select id="state_id" class="form-select" name="state_id">
                                <option value="">Select State</option>
                                @foreach ($allstates as $var)
                                    <option value="{{ $var->id }}">
                                        {{ $var->state_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <div class="form-group">
                            <label for="pin_code">Pin Code </label>
                            <input id="pin_code" class="form-control" type="tel" name="pin_code" data-parsley-type="integer" onkeypress="return onlyNumberKey(event)">
                        </div>
                    </div>
                </div>
                <div class="row" id="formHtml"></div>
                <div class="text-center"><button type="submit" name="submit">Join Us</button></div>
            </form>
        </div>
    </section>
</main><!-- End #main -->

@push('script')
<script src="{{ asset('assests/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('assests/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assests/select2/select2.min.js') }}"></script>
@include('fontend.joinus.joinusjs')
@endpush

@endsection
<!-- ======= Hero Section ======= -->