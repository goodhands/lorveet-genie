<?php

include "controller/user.class.php";


$user = new User();

if(isset($_POST['submit'])){
    //create account 

    $fname = $_POST['firstname'];
    $fname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $industry = $_POST['industry'];

    array_pop($_POST);

    foreach($_POST as $key => $value){
        if( empty($_POST[$key]) && $key != "submit"){
            $error['type'] = "error";
            $error['message'][] = "The ".ucfirst($key)." field is required.";
        }
    }

    if(empty($error)){
        $registered = $user->register($_POST);

        if(!is_bool($registered) && $registered !== true){
            $error['type'] = "error";
            $error['message'][] = $registered;
        }else{
            $error['type'] = "success";
            $_SESSION['register_email'] = $email;
            header("location: ?setpassword");
        }
    }
}elseif(isset($_POST['submitLogin'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $didLogin = $user->login($email, $password);

    if(is_bool($didLogin) && $didLogin === true){
        $_SESSION['loggedIn'] = $email;
        header("location: /");
    }elseif($didLogin === false){
        $error[] = "An unknown error occured";
    }else{
        $error[] = $didLogin;
    }
}elseif(isset($_POST['submitActivateAccount'])){
    $email = $_SESSION['register_email'];
    $pass = $_POST['password'];
    $c_pass = $_POST['cpassword'];
    $didValidate = $user->validateAccount($email, $pass, $c_pass);

    if(is_bool($didValidate) && $didValidate === true){
        $error['type'] = "success";
        $error['message'][] = "Account successfully activated. You will be redirected now.";
        $_SESSION['loggedIn'] = $_SESSION['register_email'];
        header("refresh:3; url=/");
    }else{
        $error['type'] = "error";
        $error['message'][] = $didValidate;
    }
}