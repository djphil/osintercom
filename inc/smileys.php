<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
function get_smileys($text)
{
    $smileys = [
        ':)' => '<img class="smiley" src="img/smileys/icon_smile.gif"/>',
        ';)' => '<img class="smiley" src="img/smileys/icon_wink.gif"/>',
        ':p' => '<img class="smiley" src="img/smileys/icon_razz.gif"/>',
        ':o' => '<img class="smiley" src="img/smileys/icon_surprised.gif"/>',
        ':(' => '<img class="smiley" src="img/smileys/icon_sad.gif"/>',
        ':c' => '<img class="smiley" src="img/smileys/icon_cool.gif"/>'
    ];
    return str_replace(array_keys($smileys), array_values($smileys), $text);
}      
?>
