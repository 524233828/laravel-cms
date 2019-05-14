<div class="module module-2">
    <div class="title">通知公告</div>
    <div class="content">
        <div class="left">
            @foreach($images as $image)
                <img src="{{$image['path']}}"><br>
            @endforeach
        </div>
        <div class="right">
            @foreach($chapters as $chapter)
                <a href="{{ url("/detail?id={$chapter['id']}") }}}">
                <div class="news">
                    <div class="news-title">{{$chapter['title']}}</div>
                    <div class="news-time">{{$chapter['created_at']}}</div>
                </div>
                </a>
            @endforeach
        </div>
    </div>
</div>