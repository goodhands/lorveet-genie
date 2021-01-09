<?php 
    
    include_once "jobsController.php";

    if(isset($_POST['job-search']) && !empty($_POST['job-search']) && $_POST['job-search'] != ""){
        $page_title = $_POST['job-search'];
        //building query strings
        $searchId = $job->getSearchId($job->sanitize($_POST['job-search']));

        $keyword = urlencode($_POST['job-search']);

        if($_POST['job-search'] == "" || empty($_POST['job-search'])){
            return false;
        }

        header("location: search?q=$keyword&sd=$searchId");
    }elseif(isset($_GET['clk'])){
        $job->redirectJob($_GET['clk']);
    }