<div class="module-11">
    <div class="title">{{$title['name']}}</div>
    <div class="detail">
        <div class="info">
            <div class="news-title"> <span class="triangle">▶</span> {{$chapter['title']}}</div>
            <div class="date-time">{{$chapter['created_at']}}</div>
        </div>
        <div class="news-content">
            {!! $chapter['content'] !!}
        </div>
    </div>
</div>