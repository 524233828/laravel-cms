<div class="module-8">
    <div class="title">合作机构</div>
    <div class="content">
        <div class="left-button">
            <a href="javascript:void(0);" class="prev"><div class="left-arrow"></div></a>
        </div>
        <div class="company-group">
            @foreach($images as $image)
                <div class="company"><img src="{{$image['path']}}"></div>
            @endforeach
        </div>
        <div class="right-button">
            <a href="javascript:void(0);" class="next"><div class="right-arrow"></div></a>
        </div>
    </div>
</div>