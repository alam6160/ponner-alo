@props(['type'=> 'error', 'message'=> ''])
@php
    $class = ''; $msg_title = 'Message';
    switch ($type) {
        case 'success':
            $class = 'success';
            break;
        case 'error':
            $class = 'danger';
            $msg_title = 'Whoops!';
            break;
        case 'info':
            $class = 'info';
            break;
        case 'warning':
            $class = 'warning';
            break;
        
        default:
            $class = 'info';
            break;
    }
@endphp
<div class="callout callout-{{ $class }}">
    
    @if (!empty($message) )
        <strong>{{ $msg_title }} </strong>
        @if ( is_array($message) )
            <ul style="padding:5px 0px 0px 20px">
                @foreach ( $message as $msg)
                    <li>{{ $msg }}</li>
                @endforeach
            </ul>
        @else
            {{ $message }}
        @endif
    @else
        <strong>Whoops!</strong> no message
    @endif
</div>