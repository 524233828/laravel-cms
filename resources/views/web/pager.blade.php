<a href="{{$pages[1]}}&{{$query}}" >首页</a>
@if($pagination->lastPage() > 5)
    @for($i = $pagination->lastPage()-2; $i <= $pagination->lastPage() + 2; $i++)
        <a href="{{$pages[$i]}}&{{$query}}" @if($i == $pagination->currentPage())class="select"@endif>{{$i}}</a>
    @endfor
@else
    @for($i = 1; $i <= $pagination->lastPage(); $i++)
        <a href="{{$pages[$i]}}&{{$query}}" @if($i == $pagination->currentPage())class="select"@endif>{{$i}}</a>
    @endfor
@endif
<a href="{{$pages[$pagination->lastPage()]}}&{{$query}}">末页</a>