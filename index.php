<?php include_once('inc/config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo $osintercom; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Philippe Lemaire (djphil)">
    <link rel="icon" href="img/favicon.ico">
    <link rel="author" href="inc/humans.txt" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/intercom.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/intercom.js"></script>
    <script>
    // get nickname from OpenSim
    var name = getQuerystring('nickname');

    if (!name || name === ' ') {
        // ask user for name with popup prompt 
        var name = prompt("Enter your username:", "Visitor");
    }

    // default name is 'Visiteur'
    if (!name || name === ' ') {name = "Visitor";}

    // strip tags
    name = name.replace(/(<([^>]+)>)/ig, "");
    name = decodeURI(name);

    // kick off chat
    var chat =  new Chat();

    $(function() {
        chat.getState(); 

        // watch textarea for key presses
        $("#sendie").keydown(function(event) {  
            var key = event.which;  

            // all keys including return.  
            if (key >= 33) {
                var maxLength = $(this).attr("maxlength");  
                var length = this.value.length;  

                // don't allow new content if length is maxed out
                if (length >= maxLength) {  
                    event.preventDefault();  
                }  
            }  
        });

        // watch textarea for release of key press
        $('#sendie').keyup(function(e) {	
            if (e.keyCode == 13) { 
                var text = $(this).val();
                var maxLength = $(this).attr("maxlength");  
                var length = text.length; 

                // send 
                if (length <= maxLength + 1) {
                    chat.send(text, name);	
                    $(this).val("");
                }

                else {
                    $(this).val(text.substring(0, maxLength));	
                }
            }
        });

        // display name on page
        $("#name-area").html("Welcome <span>" + name + "</span>");
    });
    </script>
</head>

<body onload="setInterval('chat.update()', 1000)">
<div id="page-wrap">
    <h1 class="center">
        <i class="glyphicon glyphicon-grain"></i>
        <?php echo $osintercom; ?>
        <i class="glyphicon glyphicon-grain"></i>
    </h1>
    <div id="chat-wrap"><div id="chat-area" ></div></div>
    <div id="name-area" class="pull-right"></div>
    <a class='help' role='button' data-toggle='collapse' href='#help' aria-expanded='false' aria-controls='logs'>help</a> - 
    <a class='logs' role='button' data-toggle='collapse' href='#logs' aria-expanded='false' aria-controls='logs'>logs</a>
    <form id="send-message-area" class="form" role="form">
        <textarea id="sendie" class="form-control" rows="1" maxlength="100" placeholder="Type your message here and press enter ..."></textarea>
    </form>
    <div id="logs" class="collapse">
        <?php 
        if (file_exists($log_url)) $content = file_get_contents($log_url);
        else $content = "Welcome to ".$osintercom;
        echo '<h1>'.$osintercom.' Logs</h1>';
        echo '<pre>';
        echo $content;
        echo '</pre>';
        ?>
    </div>
    <div id="help" class="collapse">
        <div class="page-wrap">
            <h1><?php echo $osintercom; ?> Help</h1>
            <center>
                <h2>Outworld:</h2>
                <h3>Login with javascript nickname alert:</h3> 
                    http://domain.com/osintercom/
                <a href="./" class="btn btn-success btn-xs">Demo</a>
                <h3>Auto login with nickname "Visitor":</h3>
                    http://domain.com/osintercom/?nickname=Visitor
                <a href="./?nickname=Visitor" class="btn btn-success btn-xs">Demo</a>
                <h2>Inworld:</h2>
                    Put the script "OpenSim Intercom v0.1" in a prim, configure, compile and click on desired face.
                <h2>Download:</h2>
                    Source: <a href="https://github.com/djphil/osintercom" target="_blank" class="btn btn-success btn-xs">Github</a>
            </center>
        </div>
    </div>
    <div class="footer text-center">
        <?php echo $osintercom; ?> by djphil (CC-BY-NC-SA 4.0) 
    </div>
</div>
</body>
</html>