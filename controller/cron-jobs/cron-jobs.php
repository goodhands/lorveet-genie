<?php 
    include "../../controller/connection.php";
    include "../../controller/genie.class.php";

    echo __DIR__."../genie.class.php";

    print_r(pathinfo(__DIR__."../genie.class.php"));

    // $xml = file_get_contents("https://ngcareers.com/xmlfeed.xml");
    // $jobs = new SimpleXMLElement($xml);

    $db = new DBConnection();

    $genie = new genie();

    // $totalJobs = count($jobs->jobs->job);

    // for($i = 0; $i < count($jobs->jobs->job); $i++){
    //     $title = $jobs->jobs->job[$i]->title;
    //     $url = $jobs->jobs->job[$i]->url;
    //     $salary = $jobs->jobs->job[$i]->salary;
    //     $category = $jobs->jobs->job[$i]->category;
    //     $company = $jobs->jobs->job[$i]->company;
    //     $date = date("Y-m-d", strtotime($jobs->jobs->job[$i]->date));
    //     $experience = $jobs->jobs->job[$i]->experience;
    //     $location = $jobs->jobs->job[$i]->state;
    //     $type = $jobs->jobs->job[$i]->jobtype;
    //     $specialization = $jobs->jobs->job[$i]->category;
    //     $description = $jobs->jobs->job[$i]->description;
    //     $experience = $jobs->jobs->job[$i]->experience." experience";

    //     $queryStatus[] = $db->execQuery("INSERT INTO jobs(job_title, company, job_link, job_location, 
    //                                 job_industry, date_posted, job_requirements, 
    //                                 job_time, salary) 
    //                     VALUES ('$title', '$company', '$url', '$location', '$specialization',
    //                             '$date', '$experience', '$type', '$salary')");
    // }

    // $statusMsg = "";

    // foreach($queryStatus as $status){
    //     $statusMsg .= $status."<br/>";
    // }

    // //send email 
    // $emailTemplate = file_get_contents("../../html/cron-notification.html");
            
    // $emailTemplate = str_replace("{job-source}", "https://ngcareers.com/xmlfeed.xml", $emailTemplate);
    // $emailTemplate = str_replace("{num-jobs}", $totalJobs, $emailTemplate);
    // $emailTemplate = str_replace("{date-time}", date("Y-m-d"), $emailTemplate);
    // $emailTemplate = str_replace("{status}", $statusMsg, $emailTemplate);

    // $genie->EmailNotify("Cron Job run successful", $emailTemplate);
?>