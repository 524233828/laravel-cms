<div class="tab">欢迎进入新丰县中小企业公共服务平台</div>
<div class="tab-mobile"> {{ $title }}</div>
<div class="header">
    <div class="title-group">
        <div class="title">新丰县中小企业公共服务平台</div>
        <div class="sub_title">
            <div class="cn"> | 新丰县中小企业网</div>
            <div class="site">www.xfgxservice.cn</div>
        </div>
    </div>
    <div class="date-time"><div id="date_time">{{$datetime}}</div><div id="week">{{$week}}</div></div>
    <div class="other">
        <div class="operation"><a href="javascript:setHome(this,window.location)">设为首页</a> | <a href="javascript:addFavorite()">加入收藏</a> | <a href="http://www.baidu.com">联系我们</a></div>
        <div class="search">
            <input id="search" @if($keyword !== "") value="{{$keyword}}" @endif placeholder="输入您想要搜索的内容">
            <button id="search_button"><img src="images/search.png"></button>
        </div>
    </div>

</div>