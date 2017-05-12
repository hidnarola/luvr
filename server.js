var socket = require('socket.io');
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = socket.listen(server);
var port = process.env.PORT || 3000;

server.listen(port, function() {
    console.log('Server listening at port %d', port);
});


io.on('connection', function(socket) {

    //console.log("=======Connection done");

    /***********************************************************************************************************/

    socket.on('new_msg', function(data) {
        io.sockets.emit('new_msg', {
            'sender_media_img': data.sender_media_img,
            'receiver_media_img': data.receiver_media_img,
            'success': data.success,
            'sender_id': data.sender_id,
            'receiver_id': data.receiver_id,
            'message': data.message,
            'message_type': data.message_type
        });
    });

});
