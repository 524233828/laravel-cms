<div class="footer">
    <div class="bar">
        <div class="title">新丰中小企业公共服务平台</div>
        <div class="operation">
            <a>网站首页</a>
            <a>关于我们</a>
            <a>联系我们</a>
            <a>设为首页</a>
            <a>加入收藏</a>
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

        <div class="block" style="margin-left: 5%;">
            <div class="title">
                政务链接
            </div>
            <div class="link-group">
                @foreach($link0 as $link)
                    <a href="{{$link['link']}}">{{$link["name"]}}</a>&nbsp;
                @endforeach
            </div>
        </div>
        <div class="block">
            <div class="title">
                其他链接
            </div>
            <div class="link-group">
                @foreach($link1 as $link)
                    <a href="{{$link['link']}}">{{$link["name"]}}</a>&nbsp;
                @endforeach
            </div>
        </div>
    </div>
</div>