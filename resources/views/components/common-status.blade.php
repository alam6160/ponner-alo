@props(['status' => '3'])

@php
    $meg = ''; $class = '';
    if($status == '1'){
        $meg = 'Active';
        $class = 'primary';
    }elseif ($status == '2') {
        $meg = 'Inactive';
        $class = 'danger';
    }
@endphp
<span class="badge badge-{{ $class }}">{{ $meg }}</span>