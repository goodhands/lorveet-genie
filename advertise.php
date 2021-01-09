<?php include "includes/head.php"; ?>
<body>
    <?php include "includes/page-nav.php"; ?>
    <?php include "controller/adsController.php"; ?>
    <div class="main-content">
        <h4 class="title">Create a new promotion</h4>
        <div class="size-6 pull-left">
            <div class="signup-sect no_border" style="padding: 0;">
                <?php if(isset($error['message']) && count($error['message'])): ?>
                    <div class="alert-<?php echo $error['type'] ?>">
                        <p class="alert-list"><?php echo $error['message']; ?></li>
                    </div>
                <?php endif; ?>
                <form action="" id="signup" method="post" enctype="multipart/form-data">
                    <div class="form-container">
                        <div class="form-group">
                            <label for="name" class="form_half">Promotion name</label>
                            <small><i>Example: Max employement</i></small>
                            <input type="text" required name="promotion_name" id="promotion_name" class="form_c form_full">
                        </div>    
                        <div class="form-group">  
                                <label for="audience" class="form_half">Audience</label>
                                <small><i>Select the audience for your promotion.</i></small>                       
                            <?php 
                                $industries = $job->fetchIndustries(); 
                                    if(!isset($industries['error']) && is_array($industries['result'])):  
                                        foreach($industries['result'] as $industry):  
                            ?>
                                <div class="form-group-half pull-left">
                                    <input type="checkbox" name="industries['<?php echo strtolower($industry); ?>']"> 
                                    <p class="indu_check pull-right">
                                        <?php echo $industry; ?>
                                    </p>
                                </div>
                            <?php 
                                        endforeach;
                                    else:
                                        echo $industries['error'];
                                    endif;
                            ?>
                            <input type="hidden" name="industry" id="industry">
                        </div>

                        <div class="form-group">
                            <label for="email">Description</label>
                            <small><i>Max characters (140)</i></small>
                            <textarea name="promo_text" class="form_full form_c" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="phone">Promotion Image</label>
                            <small><i>Image resolution (450 X 250)</i></small>
                            <input type="file" name="promo_image" id="" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Promo Duration</label>
                            <div class="form-group-half">
                                <input type="date" name="promo_start" required id="" class="form_c form_half">
                            </div>
                            <div class="form-group-half">
                                <input type="date" name="promo_end" required id="" class="form_c form_half">                                
                            </div>
                        </div>

                        <div class="form-group">
                            <button id="submitForm" name="submitPromo" class="pull-left btn">Sign up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="size-1"></div>
        <!-- <div class="size-3 pull-right">
            <h4 class="title">Running ads</h4>
                <ul class="list-no-pad">
                    <?php 
                        $adLists = $ad->fetchRunningAds($user->getUserId($sessionid));
                        if(is_array($adLists)): 
                            foreach($adLists as $ads):
                    ?>
                    <li>
                        <a href="?ad=<?php echo $ads['ad_id']; ?>"><?php echo $ads['ad_name']; ?></a> 
                        <p class="pull-right" title="expires in <?php echo $ad->dateAgo($ads['ad_end']); ?> days"><?php echo $ad->dateAgo($ads['ad_end']); ?> days</p></li>
                    <?php 
                            endforeach;
                        endif;
                    ?>
                </ul>
        </div> -->
    </div>
    <?php include "includes/footer.php"; ?>

    <style>
        .list-no-pad{
            padding-left: 0;
        }

        .list-no-pad li{
            list-style: none;
        }

        .list-no-pad li a{
            color: #4a4a4a;
        }
    </style>