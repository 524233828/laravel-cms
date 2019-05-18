<div class="menu">
    @foreach($menus as $menu)
    <li @if($menu->link == request()->fullUrl()) class="select" @endif onclick="location.href = '{{$menu->link}}'">{{$menu->name}}</li>
    @endforeach
</div>
<div class="menu-button"></div>