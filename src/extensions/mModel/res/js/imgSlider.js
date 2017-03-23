jQuery(document).ready(function ($) {
    $(".guidelist li").hover(
        function () {
            $(this).attr("class", "mouseon");
        },
        function () {
            $(this).attr("class", "mouseout");
        }
    );
    $(".ftoollist li").mouseover(function () {
        $(this).siblings().removeClass("on");
        $(this).addClass("on");
        var preNumber = $(this).prevAll().size();
        $(".fimglist li").removeClass("onpre");
        $(".fimglist li:nth-child(" + preNumber + ")").addClass("onpre");
        var margin = 990;
        margin = margin * preNumber;
        margin = margin * -1;
        $(".fimglist").stop().animate({ marginLeft: margin + "px" }, { duration: 500 });
    });
}); 