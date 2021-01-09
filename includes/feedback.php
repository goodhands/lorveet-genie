<div class="overlay" data-modal-for="feedback">
    <div class="modal modal-feedback">
        <div class="modal-body">
            <span class="close pull-right" title="Close" data-modal-close="feedback">&times;</span>
            <h4 class="title">FEEDBACK</h4>
            <p class="sub-title">Kindly tell us about your experience using our genie job search.</p>
            <hr>
            <div class="form-container">
                <p id="feedbackResponse"></p>
                <?php if($sessionid == ""): ?>
                <div class="form-group">
                    <label for="email" class="form_half">Email</label>
                    <small>*We will not publicly publish your email</small>
                    <input type="text" required name="email" id="email" placeholder="you@email.com" class="form_c form_full">
                </div>  
                <?php else: ?>  
                <div class="form-group">
                    <label for="email" class="form_half">Email</label>
                    <input type="hidden" name="email" value="<?php echo $sessionid ?>" id="email">
                    <i>Your registered email address will be sent along with this feedback</i>
                </div>  
                <?php endif; ?>
                <div class="form-group">
                    <label for="password">Your experience</label>
                    <textarea name="feedbackText" required id="feedbackText" cols="40" class="form_full form_c" rows="40"></textarea>
                </div>

                <div class="form-group">
                    <button class="btn-neutral feedback-mood" onclick="setFeedbackMood('poor', this.id)">Poor</button>
                    <button class="btn-neutral feedback-mood" onclick="setFeedbackMood('very poor', this.id)">Very Poor</button>
                    <button class="btn-neutral feedback-mood" onclick="setFeedbackMood('bad', this.id)">Bad</button>
                    <button class="btn-neutral feedback-mood" onclick="setFeedbackMood('good', this.id)">Good</button>
                    <button class="btn-neutral feedback-mood" onclick="setFeedbackMood('execellent', this.id)">Execellent</button>                    
                </div>

                <input type="hidden" name="mood" id="mood">

                <div class="form-group">
                    <button class="btn pull-right" id="submitFeedback">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>