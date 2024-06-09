<?php
ini_set('session.cookie_httponly', 1);
session_start();
$response = array();
if(isset($_SESSION['username']) && isset($_SESSION['user_id']) && isset($_SESSION['password'])){
    
    $response['success'] = true;
    $response['username'] = $_SESSION['username'];
    $response['user_id'] = $_SESSION['user_id'];
    $response['password'] = $_SESSION['password'];
}
else{
    $response['success'] = false;
}
header('Content-Type: application/json');
echo json_encode($response);
?>