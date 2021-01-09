<?php 

    require_once __DIR__."/connection.php";

    class Jobs extends DBConnection{
        /**
         * We save any error in a global $_SESSION['error'] with the error name as the multi-dim array
         */

        public function loadJobsFromFile($filePath){
            
            $jsonData = json_decode(file_get_contents($filePath), true);
            $tableName = "jobs";
        
            // Iterate through JSON and build INSERT statements
            foreach ($jsonData as $id=>$row) {
                $insertPairs = array();
                foreach ($row as $key=>$val) {
                    $insertPairs[addslashes($key)] = addslashes($val);
                }
                $insertKeys = '`' . implode('`,`', array_keys($insertPairs)) . '`';
                $insertVals = '"' . implode('","', array_values($insertPairs)) . '"';
            
                $didInsert = $this->execQuery("INSERT INTO `{$tableName}` ({$insertKeys}) VALUES ({$insertVals})");

                if(is_bool($didInsert)){
                    $didInsert = $this->execQuery("INSERT INTO `{$tableName}` ({$insertKeys}) VALUES ({$insertVals})");
                    echo "1 Insert successful <br/>";
                }else{
                    return "Error occured ".$didInsert;
                }
            }
        }

        private function validateKeyword($keyword){
            if(empty($keyword) || !isset($keyword)){
                return false;
            }
        }

        public function getSearchResult($keyword, $company, $locale){

            $this->validateKeyword($keyword);
            $browserId = $this->checkCookieId();
            $searchId = $this->getSearchId($keyword);

            if ( isset($_GET["page"]) ) { 
                $page  = $_GET["page"]; 
            } else {
                $page = 1; 
            }
            
            $limit = 10;
            
            $start_from = ($page - 1) * $limit; 

            if($company !== ""){
                $query = "SELECT j.* FROM (SELECT job_link, MAX(sn) as link 
                        FROM jobs GROUP BY job_link) a INNER JOIN jobs j 
                        ON (j.sn = a.link) WHERE (company LIKE '%$keyword%'
                        OR job_title LIKE '%$keyword%' 
                        OR job_description LIKE '%$keyword%' OR job_location 
                        LIKE '%$keyword%' OR job_industry LIKE '%$keyword%' OR 
                        job_specialization LIKE '%$keyword%') 
                        AND company = '$company' ORDER BY 
                        company ASC LIMIT $start_from, $limit";

                    //we need to register the search
                    $recordSearchQuery = $this->execQuery("INSERT INTO searches(search_id, browser_id, search_term, type)
                    VALUES('$searchId', '$browserId', '$keyword', 'progressive')");

                    if(!$recordSearchQuery){
                        $_SESSION['error']['record_search'] = "Failed to record search $keyword -> ".$this->getCurrTime();
                    } 
            }

            if($locale !== "" && !empty($locale)){
                $query = "SELECT j.* FROM (SELECT job_link, MAX(sn) as link 
                            FROM jobs GROUP BY job_link) a INNER JOIN jobs j 
                            ON (j.sn = a.link) WHERE (job_location LIKE '%$keyword%'
                            OR job_title LIKE '%$keyword%' 
                            OR job_description LIKE '%$keyword%' OR
                            company LIKE '%$keyword%' OR job_industry 
                            LIKE '%$keyword%' OR job_specialization LIKE '%$keyword%')
                            AND job_location = '$locale'
                            ORDER BY job_location ASC LIMIT $start_from, $limit";

                    //we need to register the search
                    $recordSearchQuery = $this->execQuery("INSERT INTO searches(search_id, browser_id, search_term, type)
                    VALUES('$searchId', '$browserId', '$keyword', 'progressive')");

                    if(!$recordSearchQuery){
                        $_SESSION['error']['record_search'] = "Failed to record search $keyword -> ".$this->getCurrTime();
                    } 
            }

            if($company !== "" && $locale !== ""){
                $query = "SELECT j.* FROM (SELECT job_link, MAX(sn) as link 
                        FROM jobs GROUP BY job_link) a INNER JOIN jobs j 
                        ON (j.sn = a.link) WHERE 
                        (job_location LIKE '%$locale%' AND company LIKE '%$company%') 
                        AND job_title LIKE '%$keyword%' 
                        OR job_description LIKE '%$keyword%' OR
                        company LIKE '%$keyword%' OR job_industry 
                        LIKE '%$keyword%' OR job_specialization LIKE '%$keyword%' 
                        ORDER BY company ASC LIMIT $start_from, $limit";

                //we need to register the search
                $recordSearchQuery = $this->execQuery("INSERT INTO searches(search_id, browser_id, search_term, type)
                VALUES('$searchId', '$browserId', '$keyword', 'progressive')");

                if(!$recordSearchQuery){
                    $_SESSION['error']['record_search'] = "Failed to record search $keyword -> ".$this->getCurrTime();
                } 
            }

            if($company == "" && $locale == ""){
                //src searches
                if(isset($_GET['src']) && $_GET['src'] == "companies"){
                    $query = "SELECT j.* FROM (SELECT job_link, MAX(sn) as link 
                        FROM jobs GROUP BY job_link) a INNER JOIN jobs j 
                        ON (j.sn = a.link) WHERE (job_title LIKE '%$keyword%' 
                        OR job_description LIKE '%$keyword%' OR job_location 
                        LIKE '%$keyword%' OR company LIKE '%$keyword%')
                        ORDER BY company 
                        ASC LIMIT $start_from, $limit";

                    //we need to register the search
                    $recordSearchQuery = $this->execQuery("INSERT INTO searches(search_id, browser_id, search_term, type)
                    VALUES('$searchId', '$browserId', '$keyword', 'src_companies')");
                }else{
                    $query = "SELECT j.* FROM (SELECT job_link, MAX(sn) as link 
                        FROM jobs GROUP BY job_link) a INNER JOIN jobs j 
                        ON (j.sn = a.link) WHERE job_title LIKE '%$keyword%' 
                        OR job_description LIKE '%$keyword%' OR job_location 
                        LIKE '%$keyword%' OR company LIKE '%$keyword%'
                        OR job_industry LIKE '%$keyword%' 
                        OR job_specialization LIKE '%$keyword%'
                        ORDER BY sn ASC LIMIT $start_from, $limit";

                        //we need to register the search
                        $recordSearchQuery = $this->execQuery("INSERT INTO searches(search_id, browser_id, search_term, type)
                        VALUES('$searchId', '$browserId', '$keyword', 'organic')");

                        if(!$recordSearchQuery){
                            $_SESSION['error']['record_search'] = "Failed to record search $keyword -> ".$this->getCurrTime();
                        } 
                }
            }
            
            //hacking the total results query
            $queryForTotalRecords = substr($query, 0, strpos($query, "ORDER BY") - 1);
            $response['sub_query'] = $queryForTotalRecords;
            $total_records = $this->execQuery($queryForTotalRecords)->num_rows;  
                
            $total_pages = ceil($total_records / $limit);
            
            $response['pagination'] = $total_pages;

            $didQuery = $this->execQuery($query);

            if($didQuery->num_rows > 0):
                if($didQuery){

                    while($resultsRows = $didQuery->fetch_assoc()):
                        $response['searches'][] = $resultsRows;
                    endwhile;

                    $response['total_searches'] = $total_records;

                    $response['query'] = $query;

                    return $response;
                }else{
                    return $didQuery;
                }
            else: 
                return "Your search didn't match our record. Try again with other keywords.";
            endif;
        }

        public function paginateUrl($pageNumber) {
            $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
            $host     = $_SERVER['HTTP_HOST'];
            $script   = $_SERVER['SCRIPT_NAME'];
            $currentURL = explode("&page=", $_SERVER['QUERY_STRING'])[0];
            return $protocol . '://' . $host . explode(".", $script)[0] . '?' . $currentURL . '&page=' .$pageNumber;
        }

        public function redirectJob($jobId, $redirect = true){

            $query = "SELECT job_link FROM jobs WHERE sn = '$jobId'";

            $didQuery = $this->execQuery($query);

            if($didQuery){
                while($jobLink = $didQuery->fetch_assoc()):
                    $jobURL = $jobLink['job_link'];
                endwhile;

                //redirect
                if($redirect === true){
                    header("location: $jobURL");
                }

                //analytics
                $browserId = $this->checkCookieId();
                $resultPage = isset($_GET['page']) ? $_GET['page'] : '1';
                $analyticsQuery = $this->execQuery("UPDATE searches SET selected_result_id = '$jobId', 
                                                    page = '$resultPage' 
                                                    WHERE browser_id = '$browserId' ");
            }

        }

        public function getCompanywMostResult($keyword){

            $this->validateKeyword($keyword);

            $query = "SELECT job_title, COUNT(company) c, company FROM `jobs` 
                        WHERE job_title LIKE '%$keyword%' 
                        OR job_description LIKE '%$keyword%' OR job_location 
                        LIKE '%$keyword%' OR company LIKE '%$keyword%' OR
                        job_industry LIKE '%$keyword%' OR job_specialization 
                        LIKE '%$keyword%' GROUP BY company ORDER BY c DESC LIMIT 3";

            $didQuery = $this->execQuery($query);

            if($didQuery->num_rows > 0):
                if($didQuery){

                    $browserId = $this->checkCookieId();
                    $searchId = $this->getSearchId($keyword);

                    while($resultsRows = $didQuery->fetch_assoc()):
                        $response['searches'][] = $resultsRows;
                    endwhile;

                    return $response;
                }else{
                    return $didQuery;
                }
            else:
                return false;
            endif;

        }

        public function getLocationwMostResult($keyword){

            $this->validateKeyword($keyword);

            $query = "SELECT job_title, COUNT(job_location) c, job_location FROM `jobs` 
                        WHERE job_title LIKE '%$keyword%' 
                        OR job_description LIKE '%$keyword%' OR company 
                        LIKE '%$keyword%' OR company LIKE '%$keyword%' OR
                        job_industry LIKE '%$keyword%' OR job_specialization 
                        LIKE '%$keyword%' GROUP BY job_location ORDER BY c DESC LIMIT 2";

            $didQuery = $this->execQuery($query);

            if($didQuery->num_rows > 0):
                if($didQuery){

                    $browserId = $this->checkCookieId();
                    $searchId = $this->getSearchId($keyword);

                    while($resultsRows = $didQuery->fetch_assoc()):
                        $response['searches'][] = $resultsRows;
                    endwhile;

                    return $response;
                }else{
                    return $didQuery;
                }
            else:
                return false;
            endif;

        }

        public function getApproximateLocation($ipAddress){
            
        }

        public function returnJobInfo($info){
            if(empty($info) || $info == "") {
                return "Not specified";
            }else{
                return $info;
            }
        }

        public function getRecentSearches($cookieId, $limit){
            $query = "SELECT DISTINCT search_term FROM searches 
                        WHERE browser_id = '$cookieId' AND search_term != '' LIMIT $limit";

            $didQuery = $this->execQuery($query);

            if($didQuery){
                while($results = $didQuery->fetch_assoc()):
                    $response[] = $results;
                endwhile;

                if( ($didQuery->num_rows) > 0):
                    return $response;
                else: 
                    return "Search for a keyword above to see your recent searches";
                endif;
            }
        }

        public function setCookieId(){
            /**
             * We need to set a cookie to identify our unique users
             */
            srand();
            $random = time()."(____)---(____)".rand(1001, 99999); 
            $hash = md5($random);

            $didCookie = setcookie("_genie", $hash, strtotime("+10 years") );

            if(!$didCookie){
                $_SESSION['error']['cookieError'] = "Failed to save cookie. -> ".$this->getCurrTime();
                // -only for test purpose return $_SESSION['error']['cookieError'];
            }
        }

        public function checkCookieId(){
            if(isset($_COOKIE['_genie'])){
                return $_COOKIE['_genie'];
            }else{
                return false;
            }
        }

        public function getSearchId($keyword){
            return hash('ripemd160', $keyword.time());
        }

        public function getCurrTime(){
            return date("Y-m-d h:ia");
        }

        public function fetchIndustries($all = false){
            /**
             * If all is false, it will fetch only industries with
             * more jobs only
             */

             if(!isset($all) || $all === false){
                 $query = "SELECT COUNT(job_industry) c, 
                            job_industry FROM `jobs` 
                            WHERE job_industry != 'Others'
                            AND job_industry != 'Not Specified' 
                            GROUP BY job_industry ORDER BY c DESC LIMIT 10";
            }else{
                $query = "SELECT job_industry FROM jobs 
                            WHERE job_industry != 'Others' AND
                            job_industry != 'Not Specified'";
            }
            
            $didQuery = $this->execQuery($query);

            if($didQuery){
                while($bestIndustry = $didQuery->fetch_assoc()):
                    $bestIndustries['result'][] = $bestIndustry['job_industry'];
                endwhile;
            }else{
                $bestIndustries['error'] = $didQuery;
            }
             
            return $bestIndustries;
        }

        public function getRelatedPreviousSearch(){
            //get browser id
            $browserId = $this->checkCookieId();

            $query = "SELECT * FROM searches WHERE browser_id = '$browserId', (SELECT )";
        }
    
    }