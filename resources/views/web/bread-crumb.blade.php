<div class="bread-crumb">
    @foreach($menu as $key => $item)
        @if($key != 0) > @endif
        <a href="{{$item['url']}}">{{$item['name']}}</a>
    @endforeach
</div>