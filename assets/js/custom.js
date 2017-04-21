var current_class = 'bg-03';
setInterval(function(){
	switch(current_class) {
		case 'bg-01':current_class = 'bg-02'; break;
		case 'bg-02':current_class = 'bg-03'; break;
		case 'bg-03':current_class = 'bg-01'; break;
	}
	$('.with-login #header').removeClass('bg-01').removeClass('bg-02').removeClass('bg-03');
	$('.with-login #header').addClass(current_class);
},4000);

function show_notification(noti_title,noti_message,noti_alert_type) {
    $.notify({
        // options
        icon: 'fa fa-warning',
        title: noti_title,
        message: noti_message,
        target:'_self'
    }, {
        // settings
        delay:1000,
        element: 'body',
        position: null,
        type: noti_alert_type,
        allow_dismiss: true,
        newest_on_top: true,
        showProgressbar: false,
        placement: {
            from: "top",
            align: "right"
        },
        offset: {x:20,y:100},
        spacing: 10,
        animate: {
            enter: 'animated fadeInRightBig',
            exit: 'animated fadeOutRightBig'
        },
        onShow: null,
        onShown: null,
        onClose: null,
        onClosed: null,
        icon_type: 'class',
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title" class="icon-tick-inside-circle">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
    });
}