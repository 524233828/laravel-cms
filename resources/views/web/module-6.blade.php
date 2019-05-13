<div class="module-6">
    <div class="block">
        <div class="title color-orange"><div style="background-color: #f9c06c"></div>工作动态</div>
        <div class="container" style="background-color: #FFF4DF">
            @foreach($chapter3 as $chapter)
                <div class="news-title">{{$chapter['title']}}</div>
            @endforeach
        </div>
    </div>
    <div class="block">
        <div class="title color-green"><div style="background-color: #89C997"></div>政策法规</div>
        <div class="container" style="background-color: #e3f2e7">
            @foreach($chapter1 as $chapter)
                <div class="news-title">{{$chapter['title']}}</div>
            @endforeach
        </div>
    </div>
    <div class="block">
        <div class="title color-blue"><div style="background-color: #56a4e6"></div>企业展示</div>
        <div class="container" style="background-color: #e1effb">
            @foreach($chapter4 as $chapter)
                <div class="news-title">{{$chapter['title']}}</div>
            @endforeach
        </div>
    </div>
    <div class="block">
        <div class="title color-red"><div style="background-color: #f47886"></div>项目申报</div>
        <div class="container" style="background-color: #ffe6e6">
            @foreach($chapter5 as $chapter)
                <div class="news-title">{{$chapter['title']}}</div>
            @endforeach
        </div>
    </div>
</div>