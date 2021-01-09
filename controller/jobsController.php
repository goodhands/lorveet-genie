<?php 
    include "jobs.class.php";

    $job = new Jobs();

    if($job->checkCookieId() === false){
        $job->setCookieId();
    }
