<div class="footer">
    <div class="bar">
        <div class="title">新丰中小企业公共服务平台</div>
        <div class="operation">
            <a href="{{url("/")}}">网站首页</a>
            <a>关于我们</a>
            <a>联系我们</a>
            <a href="javascript:setHome(this,window.location)">设为首页</a>
            <a href="javascript:addFavorite()">加入收藏</a>
        </div>
    </div>
    <div class="content">
        <div class="block" style="margin-left: 5%;">
            <div class="qrcode">
                <img src="images/qrcode_03.png" align="left">
                <div class="info">
                    联系方式：13800138000<br>
                    粤ICP备1234567890号<br>
                    粤公网案备12345667890
                </div>

            </div>
        </div>

        <div class="block link" style="margin-left: 5%;">
            <div class="title" id="link-title-0">
                政务链接
            </div>
            <div class="title" id="link-title-1">
                其他链接
            </div>
            <div class="link-group" id="links0" >
                @foreach($link0 as $link)
                    <a href="{{$link['link']}}">{{$link["name"]}}</a>&nbsp;
                @endforeach
            </div>
            <div class="link-group" id="links1">
                @foreach($link1 as $link)
                    <a href="{{$link['link']}}">{{$link["name"]}}</a>&nbsp;
                @endforeach
            </div>
        </div>
        {{--<div class="block">--}}
            {{--<div class="title">--}}
                {{--其他链接--}}
            {{--</div>--}}
            {{--<div class="link-group">--}}
                {{--@foreach($link1 as $link)--}}
                    {{--<a href="{{$link['link']}}">{{$link["name"]}}</a>&nbsp;--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
</div>