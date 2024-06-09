<?php
    session_start();
    if(isset($_POST['originDesc']) && isset($_POST['originTime'])&&isset($_POST['editDesc']) && isset($_POST['editTime'])){
        if(!hash_equals($_SESSION['token'], $_SESSION['token'])){
            die("Request forgery detected");
        }
        require_once('database.php');
        $originDesc = $_POST['originDesc'];
        $originTime = $_POST['originTime'];
        $editDesc = $_POST['editDesc'];
        $editTime = $_POST['editTime'];

        $stmt = $mysqli -> prepare("UPDATE events SET description = ?, time = ? WHERE description = ? AND time = ?");
        if(!$stmt){
            $response = array('success' => false);
        }
        $stmt -> bind_param('ssss', $editDesc, $editTime, $originDesc, $originTime);

        if($stmt -> execute()){

        }
        else {
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