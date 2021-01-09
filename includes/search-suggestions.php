<div class="result-suggested-searches">
        <div class="result-main-content">
            <?php

                $keyword = isset($_GET['q']) ? urldecode($_GET['q']) : false;
                
                $msc = microtime(true);

                $companyQuery = isset($_GET['company']) ? urldecode($_GET['company']) : "";
                $localeQuery = isset($_GET['locale']) ? urldecode($_GET['locale']) : "";

                $result = $job->getSearchResult($keyword, $companyQuery, $localeQuery);
                
                if(count($result) && is_array($result)):
                    
                    $company_links = $job->getCompanywMostResult($keyword);
                    $locale_links = $job->getLocationwMostResult($keyword);

                    foreach($company_links['searches'] as $company):
                            $companies[] = $company['company'];
                    endforeach;

                    if(is_array($companies) && count($companies)){
                        foreach($companies as $eachCompany):
                            if(isset($_GET['company']) ){
                                if($eachCompany != urldecode($_GET['company'])){
            ?>
                            <button class="resultSearch" data-suggestions="true" data-additional-search-item="<?php echo $eachCompany; ?>" data-query="&company=<?php echo urlencode($eachCompany); ?>">
                                <?php echo $eachCompany; ?>
                            </button>
            <?php 
                                }
                            }else{
            ?>
                            <button class="resultSearch" data-suggestions="true" data-additional-search-item="<?php echo $eachCompany; ?>" data-query="&company=<?php echo urlencode($eachCompany); ?>">
                                <?php echo $eachCompany; ?>
                            </button>
            <?php 
                            }
                        endforeach;
                    }

                    foreach($locale_links['searches'] as $location):
                        $locations[] = $location['job_location'];
                    endforeach;    
                    
                    if(is_array($locations) && count($locations)){
                        foreach($locations as $eachLocation):
                            if(isset($_GET['locale']) ){
                                if($eachLocation != urldecode($_GET['locale'])){
            ?>
                            <button class="resultSearch" data-suggestions="true" data-additional-search-item="<?php echo $eachLocation; ?>" data-query="&locale=<?php echo urlencode($eachLocation); ?>">
                                <?php echo $eachLocation; ?>
                            </button>
            <?php 
                                }
                            }else{
            ?>
                            <button class="resultSearch" data-suggestions="true" data-additional-search-item="<?php echo $eachLocation; ?>" data-query="&locale=<?php echo urlencode($eachLocation); ?>">
                                <?php echo $eachLocation; ?>
                            </button>
            <?php  
                            }
                        endforeach;
                    }
                
                endif;

                    if((isset($_GET['company']) || isset($_GET['locale']))):
            ?>
                            <a href="#" title="clear filters" id="filter-clear" data-action="search?q=<?php echo $_GET['q']; ?><?php echo isset($_GET['sd']) ? "&sd=".$_GET['sd'] : "" ; ?>" class="link">
                                &times; Clear filters
                            </a>
            <?php 
                    endif;
            ?>
        </div>
    </div>