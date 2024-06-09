<?php
    ini_set('session.cookie_httponly', 1);
    session_start();
    //handling user input
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if(!empty($_POST["username"]) && !empty($_POST["password"])){
            
            require_once 'database.php';
            $username = (string)$_POST['username'];
            $password = (string)$_POST['password'];
            $stmt = $mysqli->prepare("select user_id, password from users where username = ?");
            //helps with error tracking in case column's names are ever changed
            if ($stmt == false) {
                die("Error in prepare: " . $mysqli->error);
            }
            $stmt->bind_param("s", $username);
            $stmt->execute();

            $stmt->bind_result($user_id, $correct_password);
            while($stmt->fetch()){
            }
            //checking to see if such a username exists and if so then comparing passwords
            if($stmt->num_rows == 1){
                if(password_verify($password, $correct_password)){
                    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
                    $_SESSION['user_id'] = $user_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['password'] = $password;
                    $response = array('success' => true);
                }
                else{
                    $response = array('success' => false, 'error' => 'wrong password');
                }
            }
            else{
                $response = array('success' => false, 'error' => 'No account found with this username, please register or try again');
            }
            $stmt->close();
            $mysqli->close();            
        }
        else{
            $response = array('success' => false, 'error' => 'seems to be an internal issue: 1');
        }
    }
    else{
        $response = array('success' => false, 'error' => 'Seems to be an internal issue: 2');
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    ?>