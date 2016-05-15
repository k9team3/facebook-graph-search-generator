<?php

require_once('model.php');
$FbGSG = new FbGSG();

$request = htmlentities($_GET['request']);
$value = htmlentities($_GET['value']);

// REQUEST
switch ($request) {

    // Get Facebook User ID
    case 'get-fb-uid':

        $result = $FbGSG->get_fb_uid($value);

        print_r($result);

    break;
}