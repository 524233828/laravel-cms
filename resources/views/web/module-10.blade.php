<div class="module-10">
    <div class="title">{{$title}}</div>
    <div class="list">

        @if($pagination->isEmpty())
            <div style="width:100%; text-align: center;color: #999999;height: 40px;line-height: 40px">暂无更多文章</div>
        @endif
        @foreach($pagination as $news)
        <a href="{{url("/detail?id={$news['id']}")}}" class="item-contain">
            <div class="news-title">{{$news['title']}}</div>
            <div class="news-detail">
                <div class="news-content">{{ strip_tags($news['content'])}}</div>
                <div class="news-date-time">发布日期：{{$news['created_at']}}</div>
            </div>
        </a>
        <hr style="height:1px;border:none;border-top:1px dashed #ABABAB;" />
        @endforeach
    </div>
    <div class="pager">
        {{$pagination->render("web.pager", ["pagination"=>$pagination, "pages" => $pages, "query"=>$query])}}

    </div>
</div>