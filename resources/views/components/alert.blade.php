@if (session()->has($type))
    <div class="alert alert-{{$type}}">
        {{session($type)}}
    </div>
@endif

{{-- @if (session()->has($type))
    <div class="alert alert-danger">
        {{session('delete')}}
    </div>
@endif --}}
