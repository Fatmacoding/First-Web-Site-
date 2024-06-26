<?php
    include ("dbconnection.php");
    session_start();
    $message = '';
    if(isset($_POST["signUp"])){

        $fName = $_POST["fName"];
        $lName = $_POST["lName"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        // $hash_password = Password_hash($password,PASSWORD_BCRYPT);
        $sqlSelc = "SELECT COUNT(*) FROM admin WHERE email = :email " ;        
        $stm = $connection->prepare($sqlSelc);
        $data = [
            ':email' =>$email            
        ];
        $stm->execute($data);
        $count = $stm->fetchColumn();

        if($count > 0) {
            $_SESSION['message'] = 'Email already exists!';
            header('Location:Admin-Login.php');
            exit(0); 
        }
        else{
            $sql = "INSERT INTO admin(firstName,lastName,email,password) VALUES (:firstName,:lastName,:email,:passw)";
            $stm = $connection->prepare($sql);
            $data = [
                ':firstName' =>$fName, 
                ':lastName' =>$lName, 
                ':email' =>$email, 
                ':passw' => $password
            ];
            $stm_execute = $stm->execute($data);
            if($stm_execute){
                $_SESSION['message'] = 'Your account create Successfully.';
                header('Location:superAdmin.php');
                exit(0); 
            }
            else{
                $_SESSION['message'] = 'Error';
                header('Location:add-admin.php');
                exit(0);
            }
        }
    }    
    
    if(isset($_POST["signIn"])){

        if(empty($_POST["email"]) || empty($_POST["password"])){
            $_SESSION['message'] = "All field is required";
        }
        else{
            $sql = "SELECT * FROM admin WHERE email = :email AND password = :passwor";
            $stm = $connection->prepare($sql);
            $data = [
                ":email" =>  $_POST["email"],
                ":passwor" => $_POST["password"]
            ];

            $stm->execute($data);
            $count = $stm->rowCount();

            if ($count > 0) {                
                $_SESSION['email'] =  $_POST["email"] ;
                header('Location:Admin.php');
            }
            else{
                $_SESSION['message'] = 'Email Or Password is wrong';   
                header('Location:Admin-Login.php');
            }
        }
    }
    if(isset($_POST['signIn-superAdmin'])){
        if(empty($_POST["email"]) || empty($_POST["password"])){
            $_SESSION['message'] = "All field is required";
        }
        else{
            $sql = "SELECT * FROM superadmin WHERE email = :email AND password = :passwor";
            $stm = $connection->prepare($sql);
            $data = [
                ":email" =>  $_POST["email"],
                ":passwor" => $_POST["password"]
            ];

            $stm->execute($data);
            $count = $stm->rowCount();

            if ($count > 0) {                
                $_SESSION['email'] =  $_POST["email"] ;
                header('Location:superAdmin.php');
            }
            else{
                $_SESSION['message'] = 'Email Or Password is wrong';   
                header('Location:superAdmin-Login.php');
            }
        }  
    }
?>