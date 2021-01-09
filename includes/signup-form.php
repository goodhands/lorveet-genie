<h4 class="title">Create a new account</h4>
            <div class="signup-sect">
                <form action="" id="signup" method="post">
                    <div class="form-container">
                        <div class="form-group-half pull-left">
                            <label for="firstname" class="form_half">First name</label>
                            <input type="text" required name="firstname" id="firstname" placeholder="Your firstname" class="form_c form_half">
                        </div>    
                        <div class="form-group-half pull-right">    
                            <label for="lastname" class="form_half">Last name</label>
                            <input type="text" required name="lastname" id="lastname" placeholder="Your lastname" class="form_c form_half">
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" required name="email" id="email" placeholder="you@email.com" class="form_full form_c">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="tel" required name="phone" id="phone" placeholder="080xxxxxxx" class="form_full form_c">
                        </div>

                        <div class="form-group">
                            <label for="industry">What industry do you hope to get a job in?</label>
                            <small>Check as many as you wish</small>
                            <div class="group-checkbox">
                            <?php 
                                $industries = $job->fetchIndustries(); 
                                    if(!isset($industries['error']) && is_array($industries['result'])):  
                                        foreach($industries['result'] as $industry):  
                            ?>
                                <div class="form-group-half pull-left">
                                    <input type="checkbox" onclick="setIndustry('<?php echo strtolower($industry); ?>')"> 
                                    <p class="indu_check">
                                        <?php echo $industry; ?>
                                    </p>
                                </div>
                            <?php 
                                        endforeach;
                                    else:
                                        echo $industries['error'];
                                    endif;
                            ?>
                            </div>
                            <small><i>Not seeing what you want? You can update this settings anytime by visiting your profile.</i></small>
                            <input type="hidden" name="industry" id="industry">
                        </div>

                        <div class="form-group">
                            <button id="submitForm" name="submit" class="pull-right btn">Sign up</button>
                        </div>
                    </div>
                </form>
            </div>