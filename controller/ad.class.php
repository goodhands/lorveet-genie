<?php 
    require_once __DIR__."/connection.php";

    class Ads extends DBConnection{
        private $promoName;
        private $promoStart;
        private $promoEnd;
        private $promoText;
        private $audience;
        private $promoImage;
        private $promoId;

        public function setPromoName($promo_name){
            /**
             * @var $promo_name;
             * @description sets promo name
             */
            $this->promoName = $this->sanitize($promo_name);
        }

        public function setPromoStart($promo_start){
            $this->promoStart = $this->sanitize($promo_start);
        }

        public function setPromoEnd($promo_end){
            $this->promoEnd = $this->sanitize($promo_end);
        }

        public function setPromoText($promo_text){
            $this->promoText = $this->sanitize($promo_text);
        }

        public function setPromoImage($promo_img){
            $this->promoImage = $promo_img;
        }

        public function setAudience($promo_audience){
            $this->promoAudience = $this->sanitize($promo_audience);
        }

        public function setAdID($promo_id){
            $this->promoId = $this->sanitize($promo_id);
        }

        public function getPromoName(){
            return $this->promoName;
        }

        public function getPromoStart(){
            return $this->promoStart;
        }

        public function getPromoEnd(){
            return $this->promoEnd;
        }

        public function getPromoText(){
            return $this->promoText;
        }

        public function getPromoImage(){
            return $this->promoImage;
        }

        public function getAudience(){
            return $this->promoAudience;
        }

        public function getAdId(){
            return $this->promoId;
        }

        public function createAd($promoName, $promoStart, $promoEnd, $audience, $promoText, $promoImage, $userId){

            //1. Image miscellaneous
            $checkedImage = $this->uploadPromoImage($promoImage);
                if($checkedImage['status'] !== true){
                    $response['status'] = "error";
                    $response['message'] = $checkedImage['message'];
                    return $response;
                }else{
                    $this->setPromoImage($checkedImage['name']);
                    //we are saving the transformed image to the db
                    $trans_image = $checkedImage['name'];
                }

            //2. Check for empty values
            if(empty($promoName) || empty($promoStart) || empty($promoEnd) || empty($audience) || empty($promoText) || empty($promoImage)){
                $response['status'] = "error";
                $response['message'] = "All fields are required.";
            }

            if(strlen($promoText) > 140){
                $response['status'] = "error";
                $response['message'] = "Maximum characters allowed for description is 140.";
            }
            //3. Insert into db
            $promoId = hash('md5', uniqid(rand(2222,5000)));
            // $userId = $this->getUserId();
            $query = "INSERT INTO ads(ad_id, ad_name, ad_start, ad_end, ad_audience, ad_text, ad_image, user_id)
            VALUES('$promoId', '$promoName', '$promoStart', '$promoEnd', '$audience', '$promoText', '$trans_image', '$userId')";

            $didQuery = $this->execQuery($query);

            if($didQuery){
                $response['status'] = "success";
                $response['message'] = "Promotion submitted successfully. Your promotion will start appearing in search results once it has been approved.";
                return $response; 
            }else{
                $response['status'] = "error";
                $response['message'] = $didQuery;
                return $response;
            }

        }

        private function checkImageDimension($givenDimension, $max, $min){
            if ($givenDimension < $min) {
                $response['type'] = false;
                $response['message'] = "Your image is too small. Image must be 150 x 50 or more.";
            }elseif($givenDimension > $max){
                $response['type'] = false;
                $response['message'] = "Your image is too large. Image must be less than or equal to 450 x 250.";
            }elseif($givenDimension <= $max && $givenDimension >= $min){
                $response['type'] = true;
                $response['message'] = "Success";
            }

            return $response;
        }

        public function uploadPromoImage($imageArray, $destination = "assets/img/ads_image/"){
            //1. check dimensions
            $width = getimagesize($imageArray['tmp_name'])[0];
            $height = getimagesize($imageArray['tmp_name'])[1];

            $widCheck = $this->checkImageDimension($width, 450, 150);
            $heightCheck = $this->checkImageDimension($height, 250, 50);

            if( ($widCheck['type'] !== true) || ($heightCheck['type'] !== true) ){
                $response['status'] = false;
                $response['message'] = $widCheck['message'];
                return $response;
            }

            //2. Rename file
            $file_name =  $imageArray['name'];
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $image_name = uniqid(time()."_").'.'.$file_extension;
            $this->setPromoImage($image_name);

            //3. Check file type
            if(!in_array($imageArray['type'], ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"])){
                $response['status'] = false;
                $response['message'] = "File format is not supported. Please make sure your file is of the following formats (.jpeg, .jpg, .gif, .png, .bmp)";
                return $response;
            }

            //4. Upload file
            $didUpload = move_uploaded_file($imageArray['tmp_name'], $destination."/".$image_name);

            if($didUpload){
                $response['status'] = true;
                $response['name'] = $image_name;
            }else{
                $response['status'] = false;
                $response['message'] = "An error occured and your image could not be uploaded.";
            }
                return $response;
        }

        public function fetchRunningAds($user){
            $query = "SELECT * FROM ads WHERE user_id = '$user' ORDER BY ad_sn DESC LIMIT 6";

            $didQuery = $this->execQuery($query);

            if($didQuery){
                if($didQuery->num_rows > 0){
                    while($ad = $didQuery->fetch_assoc()):
                        $thisAd[] = $ad;
                        // $thisAd[] = $this->dateAgo($ad['created_date']);
                    endwhile;
            
                    return $thisAd;
                }else{
                    return "Start creating promotions today and see them appear here.";
                }
            }else{
                return $didQuery;
            }
        }

        public function getAdSet($keyword){
            /***
             * @desc this method gets ads based on the search 
             * result. This will work for those that do not have an account with us.
             */

            //get industry with most results in the search
            $query = "SELECT job_industry, COUNT(job_industry) jc FROM `jobs` 
                        WHERE job_title LIKE '%$keyword%' OR job_description 
                        LIKE '%$keyword%' OR company LIKE '%$keyword%' 
                        OR company LIKE '%$keyword%' 
                        OR job_industry LIKE '%$keyword%' OR 
                        job_specialization LIKE '%$keyword%' GROUP BY job_industry 
                        ORDER BY jc DESC LIMIT 3";

            $didQuery = $this->execQuery($query);

            if($didQuery->num_rows > 0):
                if($didQuery){

                    //fetching industries from search results
                    while($resultsRows = $didQuery->fetch_assoc()):
                        $response[] = $resultsRows;
                    endwhile;

                    //removing slashes from job industries
                    foreach($response as $eachRes){
                        $industries[] = str_replace([" / "], " ", $eachRes['job_industry']);
                    }

                    //removing spaces from job industries and 
                    //saving to array
                    foreach($industries as $industry):
                        $one[] = explode(" ", $industry);
                    endforeach;

                    $query = "SELECT * FROM `ads` WHERE ad_audience LIKE";

                    //building query string
                    foreach($one as $key => $value):
                        foreach($value as $val):
                            $val = strtolower($val);
                            $query .= " '%$val%' OR ";
                        endforeach;
                    endforeach;

                    //cutting off the last `OR`
                    $query = substr($query, 0, strlen($query) - 3);

                    $didAdQuery = $this->execQuery($query);

                    if($didAdQuery){
                        if($didAdQuery->num_rows > 0){
                            while($adset = $didAdQuery->fetch_assoc()):
                                $adresponse[] = $adset;
                            endwhile;

                            return $adresponse;
                        }else{
                            return false;
                        }
                    }else{
                        return false;
                    }

                }else{
                    return false;
                }
            else:
                return false;
            endif;
        }

    }