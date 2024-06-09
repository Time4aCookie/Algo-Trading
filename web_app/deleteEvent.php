<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    if(isset($_POST['time']) && isset($_POST['description'])){
        if(!hash_equals($_SESSION['token'], $_SESSION['token'])){
            die("Request forgery detected");
        }
        require_once('database.php');
        $time = $_POST['time'];
        $description = $_POST['description'];
       
        
        $stmt = $mysqli -> prepare("delete from events where time = ? and description = ?");
        
        $stmt -> bind_param('ss', $time, $description);
        if($stmt->execute()){
        } 
        else {
            $response = array('success' => false, 'error' => $stmt->error);
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
        
        while($stmt -> fetch()){

        }
        
        if ($stmt) {
            $stmt->close();
        }
        $response = array('success' => true);
        if ($mysqli) {
            $mysqli->close();
        }
                
    }
    else{
        $response = array('success' => false, 'error' => 'Seems to be an internal issue: 2');
    }
    header('Content-Type: application/json');
    echo json_encode($response);

?>