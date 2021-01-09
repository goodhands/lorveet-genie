<div class="result-main-content" style="min-height: calc(100vh - 100px);">
    <div class="result-content">
        <?php 
            if(count($result) && is_array($result)):
                $msc = microtime(true)-$msc;
                $result['time_taken'] = $msc;
                if(isset($_GET['page'])){
                    //echo "<p class='search-time'>Page ".$_GET['page'].". Showing results ".$currentResultCount." of ".$result['total_searches'];
                }else{
                    echo "<p class='search-time'>".$result['total_searches']." results (".round($result['time_taken'], 1)."s)</p>";
                }
            endif;
            if(count($result) && is_array($result)){
                foreach($result['searches'] as $eachResult):
        ?>
                <div class="single-result">
                    <h3>
                        <a <?php echo is_array($adset) ? "data-href=?clk=".$eachResult['sn']."&url=".$eachResult['job_id']." href='#' data-ad-result='true' " : "href=?clk=".$eachResult['sn']."&url=".$eachResult['job_id']." target='_blank' "; ?> class="result_link">
                            <?php 
                                echo $eachResult['job_title'];
                                if($eachResult['company'] != '') echo " at ".$eachResult['company']; 
                            ?>
                        </a>
                    </h3>
                    <p class="result-desc">
                        <?php echo $eachResult['job_description'] ?>
                        <b>Minimum Requirements:</b> <?php echo $job->returnJobInfo($eachResult['job_requirements']); ?>  
                        <b>Job Type:</b> <?php echo ucfirst($job->returnJobInfo($eachResult['job_time'])); ?>
                        <span class="result-detail">Industry: <?php echo $job->returnJobInfo($eachResult['job_industry']); ?></span>
                        <span class="result-detail">
                            Company: <a href="?q=<?php echo urlencode($eachResult['company']) ?>&src=companies"><?php echo $job->returnJobInfo($eachResult['company']); ?></a>
                        </span>
                        <span class="result-detail">
                            Location: <?php echo $job->returnJobInfo($eachResult['job_location']); ?>
                        </span>
                        <span class="result-detail">Date: <?php echo $job->returnJobInfo($eachResult['date_posted']); ?></span>                        
                    </p>
                </div>
        <?php 
                endforeach;
            }else{
                echo "<h3>$result</h3>";
            }
        ?>
        <?php if(isset($result['pagination']) && $result['pagination'] > 1):  ?>
        <div class="pagination">
            <ul>
                <li>
                    <?php
                            $prev = isset($_GET['page']) ? $_GET['page'] - 1: '';    
                            $next = isset($_GET['page']) ? $_GET['page'] + 1 : '2';
                    ?>
                        <a <?php echo isset($_GET['page']) && $_GET['page'] !== '1' && $_GET['page'] !== '0' ? "href='".$job->paginateUrl($prev)."'" : "class='disabled'"; ?>>Prev</a>
                </li>
                <?php 
                        for($page = 1; $page <= $result['pagination']; $page++):
                ?>
                <li>
                    <a <?php echo (isset($_GET['page'])) && ($_GET['page'] == $page) ? "class='active'" : "href='".$job->paginateUrl($page)."'"; ?>>
                        <?php echo $page; ?>
                    </a>
                </li>
                <?php 
                        endfor;
                ?>
                <li>
                    <a <?php echo isset($result['pagination']) && $result['pagination'] > 1 && $next <= $result['pagination'] ? "href='".$job->paginateUrl($next)."'" : "class='disabled'"; ?>>Next</a>
                </li>
            </ul>
        </div>
        <?php  endif; ?>
    </div>
    </div>

    <style>
        .hide-on-search{
            display: none;
        }
        /* .nav-area {
            max-height: 5px !important;

        } */
    </style>