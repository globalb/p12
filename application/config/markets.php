<?php


$config['markets'] = array();



$dir = APPPATH . "helpers/parsers/core/";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if (is_file($dir . $file . "/info.php")) {
            	require_once $dir . $file . "/info.php";
            }
        }
    }
}


?>