<?php
header('Content-Type: text/html; charset=utf-8');

require_once('model.php');
$FbGSG = new FbGSG();

$request = htmlentities($_GET['request']);
$var1 = htmlentities($_GET['var1']);
$var2 = htmlentities($_GET['var2']);

// REQUEST
switch ($request) {

    // ADD FACEBOOK ITEM
    case 'add-fb-item':

        $result = $FbGSG->add_fb_item($var1, $var2);
        echo json_encode($result);

    break;

    // GET FACEBOOK USER ID
    case 'get-fb-uid':

        echo $FbGSG->get_fb_uid($var1);

    break;
}