<?php 
    ob_start(); session_start(); 
    if(isset($_SESSION['loggedIn'])){
        $sessionid = $_SESSION['loggedIn'];
    }else{
        $sessionid = "";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Job Search - Lorveet Genie</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" media="screen and (min-device-width: 100px)" href="assets/css/main-mobile.css">
    <link rel="stylesheet" type="text/css" media="screen and (min-width: 768px)" href="assets/css/main.css" />
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:400|Amaranth|Chivo|Signika:700|Cabin+Condensed:700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="shortcut icon" href="assets/img/lorveetgenie.png" type="image/x-icon">
</head>