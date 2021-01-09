<div class="ad-banner" data-banner="true">
    <?php 
        if(is_array($adset)){
            foreach($adset as $eachAd){
    ?>
    <div class="main-content padding_100">
        <div class="header">
            <div class="size-7 pull-left">
                <h2 class="white-text">
                    <?php echo ucfirst($eachAd['ad_name']); ?>
                </h2>                
            </div>
            <div class="size-3 pull-right">
                <a href="#" class="white-text ad_close">X</a>          
            </div>
        </div>
        <div class="body">
            <blockquote>
                &quot;<?php echo ucfirst($eachAd['ad_text']); ?>&quot;
            </blockquote>
            <div class="ad-image">
                <img src="assets/img/ads_image/<?php echo $eachAd['ad_image']; ?>" alt="">
            </div>
            <div class="footer">
                <div class="size-4 pull-right">
                    <span class="white-text medium-text">You will be redirected in <span id="countdown">5</span></span>  
                    &nbsp;&nbsp;&nbsp;   
                    <a class="white-text italics medium-text" data-continue="true" target="_blank" href="#">Continue >></a>           
                </div>
            </div>
        </div>
    </div>
    <?php                         
            }
        }else{
            echo "Something went wrong";
        }
    ?>
</div>