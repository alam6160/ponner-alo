@extends('admin.layout.layout')
@section('title', 'All Products')
@section('content')
    {{-- @php
$vendor = $user->where('id', '2')->first();
echo "<pre>";
print_r($vendor);
exit;
@endphp --}}

    <div class="row">
        <div class="col-lg-12">
            <div class="content-wrap">
                <div class="table-wrap">
                    <div class="card bg-light mb-3">

                        <div class="card-header">{{ $list_title }}</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable no-footer dtr-inline" id="dataTable"
                                    aria-describedby="dataTable_info">
                                    <thead>
                                        <tr>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Product Name: activate to sort column ascending">
                                                Vendor Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Product Name: activate to sort column ascending">
                                                Product Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Product Type: activate to sort column ascending">
                                                Product Type</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="SKU Code: activate to sort column ascending">SKU
                                                Code</th>
                                            <th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1"
                                                colspan="1" aria-label="Short Desc: activate to sort column ascending">
                                                Short Desc</th>
                                            <th style="width: 15%" class="text-center sorting_disabled" rowspan="1"
                                                colspan="1" aria-label="">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($list->isNotEmpty())
                                            @foreach ($list as $index => $value)
                                                <tr class="odd" id="tr_{{ $value['id'] }}">
                                                    <td>
                                                        @php
                                                            if (!empty($value['user_id'])) {
                                                                $vendor = $user->where('id', $value['user_id'])->first();
                                                                echo $vendor->fname . ' ' . $vendor->lname . '<br>' . $vendor->email . '<br>' . $vendor->contact;
                                                            } else {
                                                                echo 'By Admin';
                                                            }
                                                        @endphp
                                                    </td>
                                                    <td>{{ $value['name'] }}</td>
                                                    <td>
                                                        @if ($value['product_type'] == 1)
                                                            {{ 'Simple' }}
                                                        @elseif($value['product_type'] == 2)
                                                            {{ 'Variable' }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $value['sku'] }}</td>
                                                    <td>{{ $value['short_desc'] }}</td>
                                                    <td class=" text-center">
                                                        <a target="_blank"
                                                            href="{{ url('admin/product/view') }}/{{ $value['id'] }}"
                                                            class="btn-ancher"><i class="fa fa-eye fa-lg"></i></a>
                                                        <a href="{{ route('admin.product.edit', ['id' => $value['id']]) }}"
                                                            class="btn-ancher"><i
                                                                class="fa fa-pencil-square-o fa-lg"></i></a>

                                                        @if ($value['admin_verify'] != 1)
                                                            <a onclick="status({{ $value['id'] }},1)"
                                                                class="btn-ancher text-success btn-delete" role="button"
                                                                title="Approve"><i class="fa fa-check fa-lg"></i></a>
                                                        @endif
                                                        @if ($value['admin_verify'] != 0)
                                                            <a onclick="status({{ $value['id'] }},0)"
                                                                class="btn-ancher text-danger btn-delete" role="button"
                                                                title="Reject"><i class="fa fa-times fa-lg"></i></a>
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                {{ $list->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            function status(id, status) {
                const approve_url = `${base_url}/admin/product/status/` + id + `/` + status;

                fetch(approve_url).then(response => {
                    return response.json();
                }).then(data => {
                    if (data.status == 1) {
                        let tr = document.getElementById(`tr_` + id);
                        tr.innerHTML = '';
                        console.log(tr);
                        toastr.success(data['message']);
                    } else if (data.status == 0) {
                        toastr.error(data['message']);
                    }
                });
            }
        </script>
    @endpush
@endsection
