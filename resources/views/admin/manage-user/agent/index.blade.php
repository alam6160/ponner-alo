@extends('admin.layout.layout')
@section('title', $title)
@section('content')
<div class="row">

    {{-- <div class="col-lg-12">
        <div class="card bg-light mb-3">
            <div class="card-header">Filter BY</div>
            <div class="card-body">
                <form class="filter-wrap row" id="search_form" method="POST" action="">
                    <fieldset class="col-lg-12 select-box">
                        <label>Select State</label>
                        <select class="form-control" name="state_id" id="state_id">
                            <option value="">Select State</option>
                            @foreach ($allstates as $var)
                            <option value="{{ $var->id }}">{{ $var->state_name }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="col-lg-12">
                        <label>Query</label>
                        <input type="text" name="query" id="query" class="form-control" placeholder="First Name, Last Name, hone No or  Email">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Last Name</label>
                        <input type="text" name="lname" id="lname" class="form-control" placeholder="Last Name">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Phone No</label>
                        <input type="tel" name="contact" id="contact" class="form-control" placeholder="Phone No">
                    </fieldset>
                    <button onclick="search()" class="btn-primary">Search</button> 
                    <button class="btn-primary" disabled>Export CSV</button>
                </form>
            </div>
        </div>
    </div> --}}

    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">
                        {{ $title }}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Vendor ID</th>
                                        <th>Vendor Details</th>
                                        <th>Contact Details</th>
                                        {{-- <th>Available Pincodes</th> --}}
                                        {{-- <th>Address</th> --}}
                                        <th>Active Status</th>
                                        <th>Type</th>
                                        <th>Wallet</th>
                                        <th style="width: 15%"></th>
                                    </tr>
                                </thead>
                            </table>
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

    
        var dataTable = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
			searching: true,
			lengthChange: false,
			responsive: true,
            pageLength: 10,
            // scrollX: true,
            "order": [[ 0, "desc" ]],
            ajax: {
                url : '{{ $indexURL }}',
                type: "GET",
                data: function(d){
                    d.query = $('#query').val();
                }
            },
            columns: [
                {data: 'code', name: 'code'},
                {data: 'agent_det', name: 'agent_det'},
                {data: 'contact_det', name: 'contact_det'},
                //{data: 'servicable_pincodes', name: 'servicable_pincodes'},
                // {data: 'address', name: 'address'},
                {data: 'active_status', name: 'active_status'},
                {data: 'vendor_type', name: 'vendor_type',serachable:false},
                {data: 'wallet', name: 'wallet',serachable:false},
                {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });
    });

    function setDateRequired() {
        const start_date = $('#start_date').val();
        const close_date = $('#close_date').val();

        if (start_date === '' && close_date === '') {
            $('#start_date').prop('required', false);
            $('#close_date').prop('required', false);
        }else{
            $('#start_date').prop('required', true);
            $('#close_date').prop('required', true);
        }
    }

    function search() {
        event.preventDefault();
        if ($('#search_form').parsley().validate()) {
            $('#dataTable').DataTable().draw(true);
        }
    }
</script>

@include('admin.manage-user.common.common')

@endpush
@endsection
