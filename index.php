<?php include_once("inc/configcheck.php"); ?>
<?php require_once('inc/config.php'); ?>
<?php require_once('inc/smileys.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $osintercom; ?></title>
    <meta name="author" content="Philippe Lemaire (djphil)">
    <link rel="icon" href="img/favicon.ico">
    <link rel="author" href="inc/humans.txt" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <?php if ($theme === true): ?>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <?php endif ?>
    <link rel="stylesheet" href="css/intercom.css">
</head>
<body>
<div id="page-wrap">
    <h2 class="text-center">
        <i class="glyphicon glyphicon-grain"></i>
        <?php echo $osintercom; ?>
        <i class="glyphicon glyphicon-grain"></i>
    </h2>
    <div id="chat-wrap"><div id="chat-area"></div></div>
    <div id="name-area" class="pull-right"></div>
    <i class="glyphicon glyphicon-education"></i> <a class='help' role='button' data-toggle='collapse' href='#help' aria-expanded='false' aria-controls='logs'>help</a> - 
    <i class="glyphicon glyphicon-file"></i> <a class='logs' role='button' data-toggle='collapse' href='#logs' aria-expanded='false' aria-controls='logs'>logs</a>

    <div class="clearfix"></div>
    <form id="send-message-area" class="form" role="form">
        <?php if ($textarea): ?>
        <textarea id="sendie" class="form-control" rows="1" maxlength="100" placeholder="Type your message here and press enter ..."></textarea>
        <?php else: ?>
        <div class="input-group ">
            <input type="text" id="sendie" class="form-control" maxlength="100" placeholder="Type your message here and press enter ..." aria-label="Type your message here and press enter ..." aria-describedby="intercom">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">Send</button>
            </span>
        </div>
        <?php endif; ?>
    </form>

    <div id="logs" class="collapse">
        <h3>Logs <i class="glyphicon glyphicon-file pull-right"></i></h3>
        <div class="clearfix"></div>
        <?php 
        if (file_exists($log_url))
        {
            if (filesize($log_url) > 0) $content = file_get_contents($log_url);
            else {
                $content = file_get_contents('logs/default.log');
                file_put_contents($log_url, $content);
            }
        }
        else {
            $content = file_get_contents('logs/default.log');
            fwrite(fopen($log_url, 'a'), $content);
        }
        if ($smileys) $content = get_smileys($content);
        $content = explode("\n", trim($content));
        echo '<div class="panel panel-default">';
        foreach($content AS $key => $value) {echo '<p>'.$value.'</p>';}
        echo '</div>';
        ?>
    </div>

    <div id="help" class="collapse">
        <div class="page-wrap">
            <h3>Help <i class="glyphicon glyphicon-education pull-right"></i></h3>
            <div class="clearfix"></div>

            <h4>Inworld:</h4>
            <ol>
                <li>Put the OpenSim Intercom Script in a prim.</li>
                <li>Configure and compile it.</li>
                <li>Wear it as hud and click on desired prim face.</li>
            </ol>

            <h4>Outworld:</h4>
            <ul>
                <li>Login with javascript alert @ <em>http://domain.com/osintercom/</em></li>
                <li>
                    Auto login with nickname "Visitor":
                    <a href="./?nickname=Visitor" class="btn btn-success btn-xs">
                    <i class="glyphicon glyphicon-eye-open"></i> Demo</a><br />@
                    <em>http://domain.com/osintercom/?nickname=Visitor</em>
                </li>
            </ul>

            <?php if ($smileys): ?>
            <h4>Smileys:</h4>
            <p><?php echo get_smileys(":) ;) :p :o :( :c"); ?></p>
            <?php endif; ?>

            <h4>Download:</h4>
            <a class="btn btn-success btn-xs" href="https://github.com/djphil/osintercom" target="_blank">
            <i class="glyphicon glyphicon-save"></i> Github</a> Source code
        </div>
    </div>
    <div class="footer text-center">
        <?php echo $osintercom; ?> by djphil <span class="label label-default">CC-BY-NC-SA 4.0</span>
    </div>

    <!--BACK TO TOP-->
    <a href="#" class="btn btn-default btn-sm back-to-top btn-fixed-bottom">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
</div>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/showup.js"></script>
<script src="js/intercom.js"></script>

<script>
jQuery(".help, .logs").click( function(e) {jQuery(".collapse").collapse("hide");});
$(document).ready(function() {setInterval('chat.update()', 1000);});

// Get nickname from OpenSim
var name = getQuerystring('nickname');

if (!name || name === ' ') {
    // Ask user for name with popup prompt 
    var name = prompt("Enter your username:", "Visitor");
}

// Default name is 'Visitor'
if (!name || name === ' ' || name === undefined || name === "null") {name = "Visitor";}

// Unescape nickname
name = decodeURIComponent(name);

// Kick off chat
var chat = new Chat();

$(function() {
    chat.getState();

    /*
     *  Watch textarea for key presses
    **/
    $("#sendie").keydown(function(event) {
        var key = event.which;
        // All keys including return.
        if (key >= 33) {
            var maxLength = $(this).attr("maxlength");
            var length = this.value.length;
            // Don't allow new content if length is maxed out
            if (length >= maxLength) {
                event.preventDefault();
            }
        }
    });

    /*
     *  Watch textarea for release of key press
    **/
    $('#sendie').keyup(function(event) {
        if (event.keyCode == 13) { 
            var text = $(this).val();
            var maxLength = $(this).attr("maxlength");  
            var length = text.length; 
            // send 
            if (length <= maxLength + 1) {
                chat.send(text, name);	
                $(this).val("");
            }
            else {$(this).val(text.substring(0, maxLength));}
        }
    });

    /*
     *  Watch button for release of key press
    **/
    $("#send-message-area").submit(function(event) { 
        event.preventDefault();
        var text = $('#sendie').val();
        var maxLength = $('#sendie').attr("maxlength");  
        var length = text.length; 
        // send 
        if (length <= maxLength + 1) {
            chat.send(text, name);	
            $('#sendie').val("");
        }
        else {$('#sendie').val(text.substring(0, maxLength));}
    });

    // Display name on page
    $("#name-area").html('Welcome <span><i class="glyphicon glyphicon-user"></i> <a href="./?nickname='+name+'">'+name+'</a></span>');
});
</script>
    
</body>
</html>
