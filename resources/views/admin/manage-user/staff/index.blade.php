@extends('admin.layout.layout')
@section('title', 'All Staff')
@section('content')
<div class="row">

    <div class="col-lg-12">
        <div class="card bg-light mb-3">
            <div class="card-header">Filter BY</div>
            <div class="card-body">
                <form class="filter-wrap row">
                    <fieldset class="col-lg-6 select-box">
                        <label>Select State</label>
                        <select class="form-control">
                            <option value="">Select State</option>
                            @foreach ($allstates as $var)
                            <option value="{{ $var->id }}">{{ $var->state_name }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Search Query</label>
                        <input type="text" class="form-control" placeholder="Name, Email, Phone, Address">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>Start Date</label>
                        <input type="date" class="form-control" placeholder="Lorem Ipsum">
                    </fieldset>
                    <fieldset class="col-lg-6">
                        <label>End Date</label>
                        <input type="date" class="form-control" placeholder="Lorem Ipsum">
                    </fieldset>
                    <button class="btn-primary" disabled>Search</button> <button class="btn-primary" disabled>Export CSV</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">
                        All Staff
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Staff ID</th>
                                        <th>Staff Details</th>
                                        <th>Contact Details</th>
                                        <th>Dept</th>
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
            ajax: '{{ route('admin.manage-user.staff.index') }}',
            columns: [
                {data: 'code', name: 'code'},
                {data: 'staff_det', name: 'staff_det'},
                {data: 'contact_det', name: 'contact_det'},
                {data: 'user_type', name: 'user_type'},
                {data: 'address', name: 'address'},
                {data: 'active_status', name: 'active_status'},
                {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });
        
    });
</script>

@include('admin.manage-user.common.common')

@endpush
@endsection
