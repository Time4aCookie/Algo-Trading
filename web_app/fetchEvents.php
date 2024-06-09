<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    if(isset($_POST['date'])){
        if(!hash_equals($_SESSION['token'], $_SESSION['token'])){
            die("Request forgery detected");
        }
        require_once('database.php');
        $userID = $_SESSION['user_id'];
        $date = $_POST['date'];
        $modDate = (string)($_POST['date'].'%');
        $descriptions = array();
        
        
        $stmt = $mysqli -> prepare("select description from events where user_id = ? AND time LIKE ?");
        if($stmt == false){
            $descriptions['success'] = false;
        }
        $stmt -> bind_param('is', $userID, $modDate);
        $stmt -> execute();

        if($stmt->error) {
            $descriptions['success'] = false;
        }
        $stmt -> bind_result($description);
        
        //bind results to an array if one or more. 
        while ($stmt->fetch()){
            $descriptions[] = $description;
        }
        
        if ($stmt) {
            $stmt->close();
        }
        
        if ($mysqli) {
            $mysqli->close();
        }
                
    }
    header('Content-Type: application/json');
    echo json_encode($descriptions);

?>
