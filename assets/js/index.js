'use strict';
/*var Video = require('twilio-video');*/
var activeRoom;
var previewTracks;
var identity;
var roomName;

// Attach the Tracks to the DOM.
function attachTracks(tracks, container) {
    tracks.forEach(function (track) {
        container.appendChild(track.attach());
    });
}

// Attach the Participant's Tracks to the DOM.
function attachParticipantTracks(participant, container) {
    var tracks = Array.from(participant.tracks.values());
    attachTracks(tracks, container);
}

// Detach the Tracks from the DOM.
function detachTracks(tracks) {
    tracks.forEach(function (track) {
        track.detach().forEach(function (detachedElement) {
            detachedElement.remove();
        });
    });
}

// Detach the Participant's Tracks from the DOM.
function detachParticipantTracks(participant) {
    var tracks = Array.from(participant.tracks.values());
    detachTracks(tracks);
}

// Check for WebRTC
/*if (!navigator.webkitGetUserMedia && !navigator.mozGetUserMedia) {
    alert('WebRTC is not available in your browser.');
}*/

// When we are about to transition away from this page, disconnect
// from the room, if joined.
window.addEventListener('beforeunload', leaveRoomIfJoined);

// Successfully connected!
function roomJoined(room) {
    activeRoom = room;

    console.log("Joined as '" + identity + "'");
    log_status("Call connected.");
    window.clearTimeout(tmptout);
    call_timeout = 0;
    audioElement.pause();
    audioElement.currentTime = 0;
    socket.emit('CALL Action Web', {
        'id': $("#msgid").val(),
        'caller_id': $("#callerid").val(),
        'sender_id': $("#callerid").val(),
        'call_status': 2,
        'message': 2
    }, function (data) {
    });
    $("#button-call,#button-reject").hide();
    $("#local-media").addClass("col-sm-4 col-md-4 col-xs-4").css('position', 'absolute');
    document.getElementById('button-join').style.display = 'none';
    document.getElementById('button-leave').style.display = 'inline';

    // Attach LocalParticipant's Tracks, if not already attached.
    var previewContainer = document.getElementById('local-media');
    if (!previewContainer.querySelector('video')) {
        attachParticipantTracks(room.localParticipant, previewContainer);
    }

    // Attach the Tracks of the Room's Participants.
    room.participants.forEach(function (participant) {
        console.log("Already in Room: '" + participant.identity + "'");
        var previewContainer = document.getElementById('remote-media');
        attachParticipantTracks(participant, previewContainer);
    });

    // When a Participant joins the Room, log the event.
    room.on('participantConnected', function (participant) {
        console.log("Joining: '" + participant.identity + "'");
    });

    // When a Participant adds a Track, attach it to the DOM.
    room.on('trackAdded', function (track, participant) {
        console.log(participant.identity + " added track: " + track.kind);
        var previewContainer = document.getElementById('remote-media');
        attachTracks([track], previewContainer);
    });

    // When a Participant removes a Track, detach it from the DOM.
    room.on('trackRemoved', function (track, participant) {
        console.log(participant.identity + " removed track: " + track.kind);
        detachTracks([track]);
    });

    // When a Participant leaves the Room, detach its Tracks.
    room.on('participantDisconnected', function (participant) {
        log_status("Participant '" + participant.identity + "' left the room");
        $("#button-leave").trigger('click');
        detachParticipantTracks(participant);
    });

    // Once the LocalParticipant leaves the room, detach the Tracks
    // of all Participants, including that of the LocalParticipant.
    room.on('disconnected', function () {
        console.log('Left');
        detachParticipantTracks(room.localParticipant);
        room.participants.forEach(detachParticipantTracks);
        activeRoom = null;
        document.getElementById('button-join').style.display = 'inline';
        document.getElementById('button-leave').style.display = 'none';
    });
}

// Activity log.
function log_status(message) {
    /*var logDiv = document.getElementById('log');
     logDiv.innerHTML += '<p>&gt;&nbsp;' + message + '</p>';
     logDiv.scrollTop = logDiv.scrollHeight;*/
    $('#log').html(message);
}

// Leave Room.
function leaveRoomIfJoined() {
    if (activeRoom) {
        activeRoom.disconnect();
    }
}
