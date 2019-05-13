<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    @include('web.css')

</head>
<body>


{!! $container !!}

@include("web.js")

<script id="jsID" type="text/javascript">
    var ary = location.href.split("&");
    jQuery(".slideBox").slide( { mainCell:".bd ul",effect:"left", trigger: "click", autoPlay: true, interTime: 4000});

    //计算模块2各块大小
    let module_group_1_width = $(".module-group").eq(1).width();

    // let module_group_1_padding = module_group_1_width * 0.2
    let module_1_img_width = $(".module-1").width();

    let module_2_width = module_group_1_width * 0.97 - module_1_img_width;

    // console.log(module_2_width);

    $(".module-2").width(module_2_width);

    //计算模块8各块大小
    let company_group_width = $(".module-8 .content").width() * 0.9668;

    let company_sum_width = company_group_width - 294;

    let company_width = parseInt(company_sum_width/7);

    let button_padding = (company_width-24)/2;

    if(button_padding < 0){
        button_padding = 0;
    }

    $(".company").width(company_width);

    $(".left-button").css("padding",button_padding + "px 0");
    $(".right-button").css("padding",button_padding + "px 0");

    jQuery(".module-8 .content").slide( { titCell:".hd ul",mainCell:".company-group",effect:"leftLoop",autoPlay:false,scroll:1,vis:7,easing:"swing",pnLoop:true,trigger:"click",mouseOverStop:true });
</script>

</body>