<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{

    // Grab post data
    $msg = $_POST['msg'];
    $senderId = $_POST['sender_id'];
    $receiverId = $_POST['receiver_id'];

    // Instantiate Login controller class
    include '../app/core/dbh.classes.php';

    // Load Composer's autoloader
    @include_once '../vendor_pgp/autoload.php';

    // Encryption scripts
    require_once '../lib/openpgp.php';
    require_once '../lib/openpgp_crypt_rsa.php';
    require_once '../lib/openpgp_crypt_symmetric.php';

    // include '../app/core/updateLastActivity.classes.php';
    include '../app/models/sendMessage.classes.php';
    include '../app/controllers/sendMessage-contr.classes.php';
    
    // Create new message object
    $sendMessage = new SendMessageContr($msg, $senderId, $receiverId);

    // Running error handlers and user registration
    $sendMessage->sendMessageUser();

} 
else
{
    $alert['message'] = 'Error! The server is having trouble connecting to the database';
    $alert['type'] = 'error';
    echo json_encode($alert);
    die();
}