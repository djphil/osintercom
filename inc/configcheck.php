<?php if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {die('Access denied ...');} ?>
<?php
$config = 'inc/config.php';
if (!file_exists($config) || filesize($config ) <= 0)
{
    echo "<br /><center><b>Alert!</b> Rename the file <b>config.php.exemple</b> to <b>config.php</b> and configure it ...</center>";
    exit();
}
?>