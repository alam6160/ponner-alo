@extends('web.common')
@section('content')

@push('style')
    <link rel="stylesheet" href="{{asset('assests/web/css/contact.css')}}">
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

<section class="inner-section contact-part">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="contact-card">
                    <i class="icofont-location-pin"></i>
                    <h4>head office</h4>
                    <p>{!! Helper::site_data('address') !!}</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="contact-card active">
                    <i class="icofont-phone"></i>
                    <h4>phone number</h4>
                    <p>
                        <a href="#">{!! Helper::site_data('contact_no') !!} <span>(toll free)</span></a><a href="#">{!! Helper::site_data('contact_no_2') !!}</a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="contact-card">
                    <i class="icofont-email"></i>
                    <h4>Support mail</h4>
                    <p>
                        <a href="#">{!! Helper::site_data('email_id') !!}</a>
                        <a href="#">{!! Helper::site_data('email_id_2') !!}</a>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <iframe
  width="600"
  height="450"
  style="border:0"
  loading="lazy"
  allowfullscreen
  referrerpolicy="no-referrer-when-downgrade"
  src="https://www.google.com/maps/embed/v1/place?key={{ env('GOOGLE_API_KEY') }}
    &q={{ Helper::site_data('latitude') }},{{ Helper::site_data('longitude') }}">
</iframe>
            </div>
            <div class="col-lg-6">
                <form class="contact-form" id="contact_form">
                    @csrf
                    <h4>Drop Your Thoughts</h4>
                    <div class="form-group">
                        <div class="form-input-group"><input class="form-control" type="text" name="name" placeholder="Your Name" required /><i class="icofont-user-alt-3"></i></div>
                    </div>
                    <div class="form-group">
                        <div class="form-input-group"><input class="form-control" type="text" name="email" placeholder="Your Email" required /><i class="icofont-email"></i></div>
                    </div>
                    <div class="form-group">
                        <div class="form-input-group"><input class="form-control" type="text" placeholder="Your Subject" name="subject" required /><i class="icofont-book-mark"></i></div>
                    </div>
                    <div class="form-group">
                        <div class="form-input-group"><textarea class="form-control" name="message" placeholder="Your Message" required></textarea ><i class="icofont-paragraph"></i></div>
                    </div>
                    <button type="submit" name="submit" class="form-btn-group"><i class="fas fa-envelope"></i><span>send message</span></button>
                </form>
            </div>
        </div>
        
    </div>
</section>

@push('script')
	
	
    <script>
        $(function(){

            $('#contact_form').submit(function (e) { 
                e.preventDefault();
                formID = 'contact_form';
                label = '<i class="fas fa-envelope"></i><span>send message</span>';
                $.ajax({
                    type: "POST",
                    url: "{{ route('contactus') }}",
                    data: new FormData(this),
                    dataType: "JSON",
                    cache:false,
                    contentType:false,
                    processData:false,
                    beforeSend: function(){
                        
                        $("#"+formID+" [name='submit']").prop('disabled', true);
                        $("#"+formID+" [name='submit']").html('<span class="spinner-border spinner-border-sm"></span>&nbsp;&nbsp;Processing'); 
                    },
                    success: function (response) {
                        
                        if (response.type == 'success') {
                            $("#"+formID+" [name='submit']").prop('disabled', false);
                            $("#"+formID+" [name='submit']").html(label);
                            $("#"+formID)[0].reset();
                            toastr.success(response.message);
                        } else {
                            $("#"+formID+" [name='submit']").prop('disabled', false);
                            $("#"+formID+" [name='submit']").html(label);
                            if (Array.isArray(response.message)) {
                                response.message.forEach(function (val) { toastr.error(val); });
                            } else { toastr.error(response.message); }
                        }
                    }
                });
                
            });
            
        });
      
 
        
      
    </script>

@endpush


@endsection