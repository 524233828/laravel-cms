<div class="module-7">
    <div class="title">
        服务快速入口
    </div>
    <div class="service-group">
        @foreach($services['service1'] as $service)
            <div class="service">
                <a href="{{$service['url']}}"><img src="{{$service['images']}}">{{$service['name']}}</a>
            </div>
        @endforeach
    </div>
    <div class="service-group">
        @foreach($services['service2'] as $service)
            <div class="service">
                <a href="{{$service['url']}}"><img src="{{$service['images']}}">{{$service['name']}}</a>
            </div>
        @endforeach

    </div>
    <div class="service-group">
        @foreach($services['service3'] as $service)
            <div class="service">
                <a href="{{$service['url']}}"><img src="{{$service['images']}}">{{$service['name']}}</a>
            </div>
        @endforeach

    </div>
</div>