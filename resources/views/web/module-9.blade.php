<div class="module-9">
    <div class="news">
        <div class="title">最新公告</div>
        <div class="news_list">
            <ul>
                @foreach($chapters as $chapter)
                <li><a href="{{url("/detail?id={$chapter['id']}")}}">{{$chapter['title']}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="info">
        <div class="title">联系我们</div>
        <div class="addr">电话：0751-8754745<br>地址：韶关市武江区前进路18号</div>
        <div class="map" id="map"></div>
        <div class="qrcode"><img src="images/qrcode_03.png"></div>
    </div>
</div>