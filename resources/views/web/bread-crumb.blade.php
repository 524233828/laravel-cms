<div class="bread-crumb">
    @foreach($menu as $key => $item)
        @if($key != 0) > @endif
        {{$item}}
    @endforeach
</div>