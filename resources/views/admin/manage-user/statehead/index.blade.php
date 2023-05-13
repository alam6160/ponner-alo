@extends('admin.layout.layout')
@section('title', 'All State-Head')
@section('content')
<div class="row">

    <div class="col-lg-12">
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
                    <fieldset class="col-lg-6">
                        <label>Search Query</label>
                        <input type="text" name="query" id="query" class="form-control" placeholder="Name, Email, Phone, Address">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Servisable Pincodes</label>
                        <input type="text" name="pincodes" id="pincodes" class="form-control" placeholder="Pincode">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" onchange="setDateRequired()">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>End Date</label>
                        <input type="date" name="close_date" id="close_date" class="form-control" onchange="setDateRequired()">
                    </fieldset>
                    <button onclick="search()" class="btn-primary" disabled>Search</button> 
                    <button class="btn-primary" disabled>Export CSV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">
                        All State-Head
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>StateHead ID</th>
                                        <th>StateHead Details</th>
                                        <th>Contact Details</th>
                                        <th>Pincode</th>
                                        <th>Address</th>
                                        <th>Active Status</th>
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
			searching: false,
			lengthChange: false,
			responsive: true,
            pageLength: 10,
            // scrollX: true,
            "order": [[ 0, "desc" ]],
            ajax: {
                url : '{{ route('admin.manage-user.statehead.index') }}',
                type: "GET",
                data: function(d){
                    d.state_id = $('#state_id').val();
                    d.query = $('#query').val();
                    d.pincodes = $('#pincodes').val();
                    d.start_date = $('#start_date').val();
                    d.close_date = $('#close_date').val();
                }
            },
            columns: [
                {data: 'code', name: 'code'},
                {data: 'statehead_det', name: 'agent_det'},
                {data: 'contact_det', name: 'contact_det'},
                {data: 'pin_code', name: 'pin_code'},
                {data: 'address', name: 'address'},
                {data: 'active_status', name: 'active_status'},
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
