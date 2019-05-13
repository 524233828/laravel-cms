<div class="tab">欢迎进入新丰中小企业公共服务平台</div>
<div class="tab-mobile"> {{ $title }}</div>
<div class="header">
    <div class="title-group">
        <div class="title">新丰中小企业公共服务平台</div>
        <div class="sub_title">
            <div class="cn"> | 新丰中小企业网</div>
            <div class="site">www.xfzxqy.com</div>
        </div>
    </div>
    <div class="date-time">{{$datetime}} {{$week}}</div>
    <div class="other">
        <div class="operation"><a onclick="this.style.behavior=’url(#default#homepage)’;this.setHomePage(’{{env("APP_URL")}}’);" href="{{env("APP_URL")}}">设为首页</a> | <a href="http://www.baidu.com">加入收藏</a> | <a href="http://www.baidu.com">联系我们</a></div>
        <div class="search">
            <input placeholder="输入您想要搜索的内容">
            <button><img src="images/search.png"></button>
        </div>
    </div>
</div>