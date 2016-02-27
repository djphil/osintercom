/* 
  * Created by: Kenrick Beckett
  * Name: Chat Engine
  * Adapted by: Philippe Lemaire
  * Name: OpenSim Intercom v0.1
*/

var instanse = false;
var state;
var mes;
var file;

// gets the nickname
function getQuerystring(key, default_)
{
    if (default_ == null) default_ = ""; 
        key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
    var qs = regex.exec(window.location.href);
    if (qs == null) return default_;
    else return qs[1];
}

function Chat () {
    this.update = updateChat;
    this.send = sendChat;
    this.getState = getStateOfChat;
}

// gets the state of the chat
function getStateOfChat() {
    if (!instanse) {
        instanse = true;
        $.ajax({
            type: "POST",
            url: "./inc/intercom.php",
            data: {  
                'function': 'getState',
                'file': file
            },
            dataType: "json",
            success: function(data) {
                state = data.state;
                instanse = false;
            },
        });
    }	 
}

// Updates the chat
function updateChat() {
    if (!instanse) {
        instanse = true;
        $.ajax({
            type: "POST",
            url: "./inc/intercom.php",
            data: {  
                'function': 'update',
                'state': state,
                'file': file
            },
            dataType: "json",
            success: function(data) {
                if (data.text) {
                    for (var i = 0; i < data.text.length; i++) {
                        $('#chat-area').append($("<p>"+ data.text[i] +"</p>"));
                    }								  
                }
                document.getElementById('chat-area').scrollTop = document.getElementById('chat-area').scrollHeight;
                instanse = false;
                state = data.state;
            },
        });
    }

    else {
        setTimeout(updateChat, 1500);
    }
}

// send the message
function sendChat(message, nickname)
{       
    updateChat();
    $.ajax({
        type: "POST",
        url: "./inc/intercom.php",
        data: {  
            'function': 'send',
            'message': message,
            'nickname': nickname,
            'file': file
        },
        dataType: "json",
        success: function(data) {
            updateChat();
        },
    });
}
