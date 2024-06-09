<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    if(isset($_POST['username']) && isset($_POST['description']) && isset($_POST['time'])){
        if(!hash_equals($_SESSION['token'], $_SESSION['token'])){
            die("Request forgery detected");
        }
        require_once('database.php');
        $username = (string)$_POST['username'];
        $description = (string)$_POST['description'];
        $userID = $_SESSION['user_id'];
        $time = $_POST['time'];


        $stmt = $mysqli -> prepare("insert into events (user_id, description, time) values (?, ?, ?)");
        if($stmt == false){
            die("Error in prepare: " . $stmt -> error);
        }
        $stmt -> bind_param("iss", $userID, $description, $time);
        if($stmt->execute()){
            $response = array('success' => true);
        }
        else{
            $response = array('success' => false, 'error' => 'event insertion was not succesfull');
        }
        
        $stmt -> close();
        

       
        $mysqli -> close();
    }
    else{
        $response = array('success' => false, 'error' => 'Seems to be an internal issue: 2');
    }
    header('Content-Type: application/json');
    echo json_encode($response);

?>