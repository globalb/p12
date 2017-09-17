<?php

$res = file_get_contents("testy.txt");
$res = preg_replace("/\\\\u([0-9a-f]{3,4})/i", "&#x\\1;", $res);
$res = html_entity_decode($res, null, 'UTF-8');
$res = utf8_encode($res);
$res =  (array) json_decode($res , 1);
//echo $res;

echo '<pre>' . print_r($res) . '</pre>';
echo json_last_error();


exit;


phpinfo();exit;

?>