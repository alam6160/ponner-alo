@extends('admin.layout.layout')
@section('title', 'All Applications')
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
            
                    <div class="card-header">All Applications</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Appl No</th>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Apply For</th>
                                        <th>Apply Date</th>
                                        <th>Status</th>
                                        <th style="width: 5%"></th>
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
                url : '{{ route('admin.application.index') }}',
                type: "GET",
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false},
                {data: 'appl_no', name: 'appl_no'},
                {data: 'name', name: 'name'},
                {data: 'contact_det', name: 'contact_det'},
                {data: 'department', name: 'department'},
                {data: 'created_at', name: 'created_at'},
                {data: 'status', name: 'status'},
                {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });
    });
</script>

@endpush
@endsection
