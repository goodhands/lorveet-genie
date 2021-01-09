<?php 
    require_once __DIR__."/connection.php";

    class User extends DBConnection{
        private $id;
        private $fname;
        private $lname;
        private $email;
        private $phone;
        private $address;
        private $interests;

        public function register($array){
            
            foreach($array as $key => $value){
                if($key === "email"){
                    $givenMail = $value;
                }
                $columns[] = $key;
                $values[] = $this->sanitize($value);
            }

            //email check
            if(isset($givenMail)){
                $query = "SELECT email FROM user WHERE email = '$givenMail'";
                    if($this->execQuery($query)){
                        if($this->execQuery($query)->num_rows > 0){
                            return "This email already exist";
                        }
                    }
            }

            $columns[] = "user_id";
            $values[] = $this->newUserId();

            $column = implode(", ", $columns);
            $value = '"'.implode('", "', $values).'"';

            $query = "INSERT INTO user ($column) VALUES ($value)";

            $didQuery = $this->execQuery($query);

            if(is_integer($didQuery) && $didQuery == 1){
                return true;
            }else{
                return $didQuery;
            }
        }

        public function getUserId($email){
            $query = "SELECT * FROM user WHERE email = '$email'";

            $didQuery = $this->execQuery($query);

            if(!$didQuery){
                return $didQuery;
            }
            
            if($didQuery->num_rows > 0){
                while($username = $didQuery->fetch_assoc()):
                    $user[] = $username;
                endwhile;
                
                return $username['user_id'];
            }else{
                return "Invalid user.";
            }
        }

        public function setUserId($userid){
            $this->id = $userid;
        }

        public function getUsername($email){
            $email = $this->sanitize($email);

            $query = "SELECT * FROM user WHERE email = '$email' ";

            $didQuery = $this->execQuery($query);

            if($didQuery){
                if($didQuery->num_rows > 0){
                    $username = $didQuery->fetch_assoc();
                    $this->setUserId($username['user_id']);
                    $user_name = $username['firstname']." ".$username['lastname'];
                    return $user_name;
                }else{
                    return "Invalid user.";
                }
            }else{
                return $didQuery;
            }
        }

        public function login($email, $pass){
            $query = "SELECT password FROM user WHERE email = '$email'";
            $didQuery = $this->execQuery($query);
                if($didQuery){
                    if($didQuery->num_rows > 0){
                        while($pass = $didQuery->fetch_assoc()):
                            $password = $pass['password'];
                        endwhile;

                        if(password_verify($pass, $password)){
                            $_SESSION['loggedIn'] = $pass;
                            return true;
                        }else{
                            return "Email or password is invalid.";
                        }
                    }else{
                        return "The details you have entered do not match our record.";
                    }
                }else{
                    return $didQuery;
                }
        }

        public function sendEmailValidation($email){
            $cost = ['cost' => 12];
            $emailHash = hash("haval160,4", uniqid("<{./.}>"));
            // $emailHash = password_hash(uniqid("--"), PASSWORD_BCRYPT, $cost);
            $query = "UPDATE user SET sent_email = '1', hash = '$emailHash' WHERE email = '$email' ";
            
            $didQuery = $this->execQuery($query);

            if($didQuery){
                $url = "https://www.lorveet.com/validate/".$emailHash;
                $emailMsg = "Your registration was successful. \r\r";
                $emailMsg .= "Click on this link to activate your account ".$url."\r\r";
                $emailMsg .= "If the link doesn't work, copy and paste it in your browser.\r\r";
                $emailMsg .= "This link expires after 15 minutes in order to secure your account, click on it immediately.\r\r";
                $emailMsg .= "Regards, \r\r";
                $emailMsg .= "The Lorveet Team\r\r";
                $emailMsg .= "";
                
                $headers = "";
    
                $sentEmail = mail($email, "Lorveet Account Activation", $emailMsg, $headers);
            }else{
                $_SESSION['error']['validate_email_query'] = $didQuery;
            }
        
        }

        public function validateAccount($email, $pass, $c_pass){

            /**
             * @todo: expire links after 15 mins; problem with timezone.
             */
                //validate and hash password
                if($pass != $c_pass) return "Your passwords do not match.";
                if(strlen($pass) < 8) return "Password must be 8 characters or more.";
                $cost = [
                    "cost" => 12
                ];
                $password = password_hash($pass, PASSWORD_BCRYPT, $cost);

                $query = "UPDATE user SET account_status = 'active', password = '$password' WHERE email = '$email'";
                $didUpdate = $this->execQuery($query);
                if($didUpdate == 1){
                    return true;
                }else{
                    return $didUpdate;
                }

        }

        public function newUserId(){
            return substr(md5(time()."|\/||)\/"), 0, 7);
        }

        public function feedback($email, $text, $mood, $url){
            //prepare values for db
            $email = $this->sanitize($email);
            $text = $this->sanitize($text);
            $mood = $this->sanitize($mood);
            $ip = $_SERVER['REMOTE_ADDR'];
            $browserId = $_COOKIE['_genie'];
            $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';

            $query = "INSERT INTO feedback(email, text, ip, page, browser, mood) 
                        VALUES ('$email', '$text', '$ip', '$url', '$browserId', '$mood')";

            $didQuery = $this->execQuery($query);

            if($didQuery){
                //send email
                return true;
            }else{
                return $didQuery;
            }
        }
    }