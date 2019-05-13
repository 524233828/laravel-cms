<div class="module module-3">
    @foreach($images as $key => $image)
        <img src="{{$image['path']}}" @if($key==2)style="margin: 0px"@endif>
    @endforeach
</div>