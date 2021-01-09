<?php
    include_once "jobsController.php";
    include_once "ad.class.php";

    $ad = new Ads();
    
    if(isset($_POST['submitPromo'])){
        if($sessionid == ""){
            $error['type'] = "error";
            $error['message'] = "You need to sign in or create an account to run a promotion.";
        }else{
            foreach($_POST['industries'] as $key => $value){
                @$industries .= ",".$key;
            }
            $industry = str_replace('/ ', ',', $industries);

            $userId = $user->getUserId($sessionid);
            if($userId == "Invalid user"){
                $userId = "Visitor";
            }
            $ad->setPromoName($_POST['promotion_name']);
            $ad->setPromoEnd($_POST['promo_end']);
            $ad->setPromoStart($_POST['promo_start']);
            $ad->setPromoText($_POST['promo_text']);
            $ad->setAudience($industry);
            $ad->setPromoImage($_FILES['promo_image']);

            $didAd = $ad->createAd($ad->getPromoName(), $ad->getpromoStart(), $ad->getpromoEnd(), $ad->getaudience(), $ad->getpromoText(), $ad->getpromoImage(), $userId);

            $error['type'] = $didAd['status'];
            $error['message'] = $didAd['message'];
        }
    }

    //get search result adverts
    if(isset($_GET['q'])){
        $adset = $ad->getAdSet($_GET['q']);
    }