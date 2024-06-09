<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    
    if(isset($_POST['username']) && isset($_POST['description']) && isset($_POST['time']) && isset($_POST['email'])){
        if(!hash_equals($_SESSION['token'], $_SESSION['token'])){
            die("Request forgery detected");
        }
        require_once('database.php');
        $username = (string)$_POST['username'];
        $description = (string)$_POST['description'];
        $SessionUserID = $_SESSION['user_id'];
        $time = $_POST['time'];
        $email =(string)$_POST['email'];
        
        //lets get all user_id's
        $userID;
       
            $stmt = $mysqli->prepare("select user_id from users where email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($user_id);
            while ($stmt->fetch()) {
                $userID = $user_id;
            }
            $stmt->close();
        
        //insert into events
            $stmt = $mysqli -> prepare("insert into events (user_id, description, time) values (?, ?, ?)");
            $stmt -> bind_param("iss", $userID, $description, $time);
            if($stmt->execute()){
            } else {
                $response = array('success' => false, 'error' => $stmt->error);
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            $stmt->close();
        
        $response = array('success' => true);
        $mysqli -> close();
    }
    else{
        $response = array('success' => false, 'error' => 'Seems to be an internal issue: 2');
    }
    header('Content-Type: application/json');
    echo json_encode($response);
?>