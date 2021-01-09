<?php 
    class DBConnection{
        private $host;
        private $dbname;
        private $user;
        private $pass;
        private $connection;

        public function __construct($host = "localhost", $dbname = "lorveet_search", $user = "root", $pass = "hicui4cu"){
            $connect = new mysqli($host, $user, $pass, $dbname);

            if(mysqli_connect_error()){
                die("Failed to connect to database ". mysqli_connect_errno());
            }

            $this->connection = $connect;
        }

        public function execQuery($query){
            $didQuery = $this->connection->query($query);
            if($didQuery){
                return $didQuery;
            }else{
                return $this->connection->error;
            }
        }

        public function sanitize($string){
            return trim(strip_tags($this->connection->escape_string($string)));
        }

        public function dateAgo($date){
            
            $cdate = mktime(0, 0, 0, date('m', strtotime($date)), date('d', strtotime($date)), date('Y', strtotime($date)));
            
            $today = time();
            
            $difference = $cdate - $today;
            
            if ($difference < 0) { $difference = 0; }
            
            $daysRemaining = floor($difference/60/60/24);
            
            return $daysRemaining;
        }
    }