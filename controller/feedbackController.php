<?php 
    include "connection.php";
    include "user.class.php";
    include "genie.class.php";

    $db = new DBConnection();
    $genie = new genie();
    $user = new User();

    if(isset($_POST['feedback'])){
        $email = $db->sanitize($_POST['email']);
        $message = $db->sanitize($_POST['feedbackText']);
        $mood = $db->sanitize($_POST['mood']);
        $url = $db->sanitize($_POST['url']);

        $savedFeedback = $user->feedback($email, $message, $mood, $url);

        if($savedFeedback){

            //send email 
            $emailTemplate = file_get_contents("../html/notify-email.html");
            
            $emailTemplate = str_replace("{user-email}", $email, $emailTemplate);
            $emailTemplate = str_replace("{user-feedback}", $message, $emailTemplate);
            $emailTemplate = str_replace("{user-mood}", $mood, $emailTemplate);
            $emailTemplate = str_replace("{user-time}", date("Y-m-d"), $emailTemplate);
            $emailTemplate = str_replace("{user-ip}", $_SERVER['REMOTE_ADDR'], $emailTemplate);

            $genie->EmailNotify("New feedback on lorveet job search engine", $emailTemplate);
            
            echo "Thank you for your feedback!";
        }
    }  