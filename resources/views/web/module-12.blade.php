<div class="module-12">
    <div class="title">下载专区</div>
    <div class="list">
        <div class="row">
            @foreach($pagination as $key => $download)
                <a href="{{$download['path']}}">
                    <div class="download-item">
                        <div class="download-icon"><img src="images/download_{{$key % 3 + 1}}.png"></div>
                        <div class="download-info">
                            <div class="file_name">{{$download['name']}}</div>
                            <div class="file_info">上传时间：{{$download['created_at']}}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="pager">
        {{$pagination->render("web.pager", ["pagination"=>$pagination, "pages" => $pages, "query"=>$query])}}

    </div>
</div>