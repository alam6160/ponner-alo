@extends('agent.layout.layout')
@section('title', $title)
@section('content')
<div class="row">


    <div class="col-lg-12">
        <div class="content-wrap">
            <div class="table-wrap">
                <div class="card bg-light mb-3">
            
                    <div class="card-header">{{ $title }}</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        @foreach ($table as $val)
                                            <th>{{ $val }}</th>
                                        @endforeach
                                        {{-- <th style="width: 15%"></th> --}}
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

@php $tableKeys = $table->keys(); @endphp

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
                url : '{{ $indexURL }}',
                type: "GET",
                data: function(d){
                    //d.query = $('#query').val();
                }
            },
            columns: [
                @foreach ($tableKeys as $var)
                {data: '{{ $var }}', name: '{{ $var }}'},
                @endforeach
                // {data: 'actions', name: 'actions',orderable:false,serachable:false,sClass:'text-center'},
            ]
        });
    });


</script>

@endpush
@endsection
