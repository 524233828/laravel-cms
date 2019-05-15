<div class="container">

    @foreach($children as $child)
        {!! $child->render() !!}
    @endforeach
</div>