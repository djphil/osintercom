<?php
require_once('config.php');
$log_url = '../'.$log_url;

$function = htmlentities(strip_tags($_POST['function']));
$log = array();

switch($function)
{
    case('getState'):
    if (file_exists($log_url))
    {
        $lines = file($log_url);
    }

    $log['state'] = count($lines); 
    break;	

    case('update'):
    $state = $_POST['state'];
    if (file_exists($log_url))
    {
        $lines = file($log_url);
    }

    $count =  count($lines);
    
    if ($state == $count)
    {
        $log['state'] = $state;
        $log['text'] = false;
    }

    else
    {
        $text= array();
        $log['state'] = $state + count($lines) - $state;

        foreach ($lines as $line_num => $line)
        {
            if($line_num >= $state)
            {
                $text[] =  $line = str_replace("\n", "", $line);
            }
        }
        $log['text'] = $text; 
    }
    break;
         
    case('send'):
    $nickname = trim(htmlentities(strip_tags($_POST['nickname'])));
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    $message = trim(htmlentities(strip_tags($_POST['message'])));

    if (!empty($message) && $message != "\n")
    {
        if (preg_match($reg_exUrl, $message, $url))
        {
            $message = preg_replace($reg_exUrl, '<a href="'.$url[0].'" target="_blank">'.$url[0].'</a>', $message);
        }
        fwrite(fopen($log_url, 'a'), "<span>". $nickname . "</span>" . $message = str_replace("\n", " ", ": ".$message) . "\n"); 
    }
    break;
}
echo json_encode($log);
?>
