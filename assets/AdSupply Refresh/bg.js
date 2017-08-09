console.log("Loaded....");
/*$(this).scrollTop(0);*/
var random_locations = [100, 200, 300, 400, 500, 600, 700, 800, 900, 1000];
/*if ('scrollRestoration' in history) {
 history.scrollRestoration = 'manual';
 }*/
$('body').prepend("<input type='hidden' id='hdn_x_pro'/>");
$(document).ready(function () {
    setTimeout(function () {
        /*$('#eflyer .close').trigger('click');*/
        manageAutoClick();
    }, 9000);
});
function manageAutoClick() {
    $('body').animate({scrollTop: random_locations[Math.floor(Math.random() * random_locations.length)]}, 'slow', 'swing', function () {
        $(".asinf-admedia iframe").contents().find("a").detach().appendTo('body').addClass("ada");
        if ($(".ada").length > 0)
        {
            setTimeout(function () {
                /*$(".ada")[0].click();*/
                /*var external_window = window.open($(".ada").attr("href"), '_blank');
                 self.focus();*/
                openNewBackgroundTab($(".ada").attr("href"));
                location.reload();
            }, 1000);
        }
    });
}

function openNewBackgroundTab(url) {
    var a = document.createElement("a");
    a.href = url;
    var evt = document.createEvent("MouseEvents");
    //the tenth parameter of initMouseEvent sets ctrl key
    evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0,
            true, false, false, false, 0, null);
    a.dispatchEvent(evt);
}