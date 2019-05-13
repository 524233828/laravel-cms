<!-- banner -->
<div id="slideBox" class="slideBox">
    <div class="hd">
        <ul>
            @foreach($banners as $banner)
                <li><img src="{{$banner['path']}}"></li>
            @endforeach
        </ul>
    </div>
    <div class="bd">
        <ul>
            @foreach($banners as $banner)
                <li><a href="{{$banner['url']}}" target="_blank"><img src="{{$banner['path']}}"></a></li>
            @endforeach
        </ul>
    </div>

    <!-- 下面是前/后按钮代码，如果不需要删除即可 -->
    <a class="prev" href="javascript:void(0)"></a>
    <a class="next" href="javascript:void(0)"></a>

</div>
<!-- bannner -->