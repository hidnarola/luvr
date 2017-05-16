<?php
$sess_user_data = $this->session->userdata('user');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Twilio Video - Video Quickstart</title>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/index.css'; ?>"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js"></script>
    </head>
    <body>
        <div id="remote-media"></div>
        <div id="controls">
            <div id="preview">
                <p class="instructions">Hello Beautiful</p>
                <div id="local-media"></div>
                <button id="button-preview">Preview My Camera</button>
            </div>
            <div id="room-controls">
                <p class="instructions">Room Name:</p>
                <input id="room-name" type="hidden" placeholder="Enter a room name" value="<?php echo random_string('alnum', 10); ?>"/>
                <button id="button-join">Call this user</button>
                <button id="button-leave">Disconnect</button>
            </div>
            <div id="log"></div>
        </div>

        <script type="text/javascript" src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
        <script src="<?php echo base_url() . 'assets/js/index.js'; ?>"></script>
        <script>
            var socket = io.connect('http://' + window.location.hostname + ':8100');
            /*socket.on('connect', function () {
             socket.emit('connected', {id: socket.id});
             console.log("socket:", socket.id);
             
             });*/

            socket.emit('join_socket_web', {
                'userID': '<?php echo $sess_user_data['id']; ?>',
                'is_login': '1',
                'app_version': 2
            });

            socket.emit('CALL Action', {
                'id': 0,
                'caller_id': '<?php echo $sess_user_data['id']; ?>',
                'calling_id': '<?php echo $chat_user_data['id']; ?>',
                'call_unique_id': Math.random().toString(36).slice(2),
                'app_version': 2,
                'call_status': 1
            }, function (data) {
                console.log(data);
            });

            socket.on('CALL Action', function (data) {
                console.log("Response : " + data);               
            });
            
        </script>
    </body>
</html>