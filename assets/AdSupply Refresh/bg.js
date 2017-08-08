console.log("Loaded....");
$(this).scrollTop(0);
$(document).ready(function () {
    $('body').append("<input type='hidden' id='hdn_x_pro'/>")
    setTimeout(function () {
        /*$('#eflyer .close').trigger('click');*/
        manageAutoClick();
    }, 9000);
});
function manageAutoClick() {
    $('html,body').animate({scrollTop: $('#packages').offset().top}, 'slow', 'swing', function () {
        $(".asinf-admedia iframe").contents().find("a").detach().appendTo('body').addClass("ada");
        if ($(".ada").length > 0)
        {
            setTimeout(function () {
                /*$(".ada")[0].click();*/
                var external_window = window.open($(".ada").attr("href"), '3rdparty');
                self.focus();
                location.reload();
            }, 1000);
        }
    });
}