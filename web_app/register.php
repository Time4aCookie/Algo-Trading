<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    if(isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['email'])){
        require_once 'database.php';
        $username = (string)$_POST['username'];
        $password = (string)password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = (string)$_POST['email'];
        //making sure this username doesn't already exist
        $stmt = $mysqli->prepare("select username from users where username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($currentUsers);
        while($stmt->fetch()){
        }
        if($stmt->num_rows != 0){
            $response = array('success' => false, 'error' => 'this username is taken, please try again');
        }
        else{
            //putting username & password & email in if everything's good
            $stmt = $mysqli->prepare( "insert into users (username, password, email) values (?, ?, ?)");
            if(!$stmt){
                die("Error in execute: " . $stmt->error);
            }
            $stmt->bind_param("sss", $username, $password, $email);
            if($stmt->execute()){
                $response = array('success' => true);
            }
            else{
                die("Error in execute: " . $stmt->error);
            }
            $stmt->close();
        }
        $mysqli->close();
     }
     else{
        $response = array('success' => false, 'error' => 'Seems to be an internal issue: 1');
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    ?>