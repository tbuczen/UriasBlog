/**
 * Created by urias on 22.08.16.
 */


$(function() {

});

$(".g-share a").click(function (e) {
    window.open($(this).data("href"),'_blank','width=500,height=300');
    console.log("clicked G");
    e.stopPropagation();
})

$(".l-share a").click(function (e) {
    window.open($(this).data("href"),'_blank','width=500,height=300');
    console.log("clicked L");
    e.stopPropagation();
})