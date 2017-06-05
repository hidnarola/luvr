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

var success_svg = '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="510px" height="510px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"> <g> <g id="check-circle-outline"> <path d="M150.45,206.55l-35.7,35.7L229.5,357l255-255l-35.7-35.7L229.5,285.6L150.45,206.55z M459,255c0,112.2-91.8,204-204,204 S51,367.2,51,255S142.8,51,255,51c20.4,0,38.25,2.55,56.1,7.65l40.801-40.8C321.3,7.65,288.15,0,255,0C114.75,0,0,114.75,0,255 s114.75,255,255,255s255-114.75,255-255H459z"/> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>';
var error_svg = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 490.442 490.442" style="enable-background:new 0 0 490.442 490.442;" xml:space="preserve"> <g> <g> <path d="M270.421,245.342l42-42c7.3-7.1,7.3-18.5,0.2-25.5c-7-7-18.4-7-25.5,0l-42,42l-42-42c-7-7-18.4-7-25.5,0 c-7,7-7,18.4,0,25.5l42,42l-42,42c-7,7-7,18.4,0,25.5c3.5,3.5,8.1,5.3,12.7,5.3c4.6,0,9.2-1.8,12.7-5.3l42-42l42,42 c3.5,3.5,8.1,5.3,12.7,5.3c4.6,0,9.2-1.8,12.7-5.3c7-7,7-18.4,0-25.5L270.421,245.342z"/> </g> </g> <g> <g> <path d="M418.621,71.842c-7-7-18.4-7-25.4,0l-56,55.9c-7,7-7,18.4,0,25.5c7,7,18.4,7,25.5,0l42.7-42.7 c69.2,82.1,65.1,205.3-12.2,282.6c-39.5,39.5-92.1,61.3-148,61.3c-55.9,0-108.4-21.7-148-61.2c-81.6-81.6-81.6-214.3,0-295.9 c50.1-50.1,121.6-71.4,191.1-56.8c9.7,2,19.3-4.2,21.3-13.9c2-9.7-4.2-19.3-13.9-21.3c-81.4-17.2-165.1,7.7-223.9,66.5 c-46.3,46.3-71.8,107.9-71.8,173.4s25.5,127.1,71.8,173.4c46.3,46.3,107.9,71.8,173.4,71.8s127.1-25.5,173.4-71.8 s71.8-107.9,71.8-173.4S464.921,118.142,418.621,71.842z"/> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>';

function show_notification(noti_title,noti_message,noti_alert_type) {
    var template_str = '';
    if(noti_alert_type == 'success'){
        template_str = '<div class="success-message message-css"><div class="message-l">'+success_svg+'</div><div class="message-r"> <h3>Success</h3> <p>{2}</p> </div> </div>';
    }else{
        template_str = '<div class="cancel-message message-css"><div class="message-l">'+error_svg+'</div><div class="message-r"> <h3>Opps!</h3> <p>{2}</p> </div> </div>';
    }

    $.notify({
        // options
        icon: 'fa fa-warning',
        title: noti_title,
        message: noti_message,
        target:'_self'
    }, {
        // settings
        delay:1500,
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
        template: template_str
    });
}

$(function() {
    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });
    // We can watch for our custom `fileselect` event like this
    $(document).ready( function() {
        $(':file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.length ) {
                input.val(log);
            } else {
                // if( log ) alert(log);
            }
        });
    });
});

function my_img_url_js(img_type, img_url){
    if(img_type == '1'){
        return "bio/show_img/"+img_url+'/1';
    }
    if(img_type == '2'){
        img_url = img_url.replace(".mp4", ".png");
        return 'bio/show_img/'+img_url+'/1';
    }
    if(img_type == '3'){
        return img_url;
    }
    if(img_type == '4'){
        return img_url;
    }
}