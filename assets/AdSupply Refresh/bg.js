console.log("Loaded....");
$(document).ready(function () {
    $(this).scrollTop(0);
    $('body').append("<input type='hidden' id='hdn_x_pro'/>")
    setTimeout(function () {
        /*$('#eflyer .close').trigger('click');*/
        manageAutoClick();
    }, 10000);
});
function manageAutoClick() {
    $('html,body').animate({scrollTop: $('#packages').offset().top}, 'slow', 'swing', function () {
        $(".asinf-admedia iframe").contents().find("a").detach().appendTo('body').addClass("ada");
        if ($(".ada").length > 0)
        {
            $(".ada")[0].click();
            location.reload();
        }
    });
}