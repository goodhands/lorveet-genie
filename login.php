<?php 
    include "includes/head.php";
    include "includes/page-nav.php";
?>

<body>
    <div class="main-content">
        <div class="center-page">
            <?php if(isset($error) && is_array($error)): ?>
            <div class="alert-error">
                <ul>
            <?php foreach($error as $err): ?>
                    <li class="alert-list"><?php echo $err; ?></li>
            <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <h4 class="title">Sign in</h4>
        <div class="signup-sect">
            <form action="" id="signup" method="post">
                <div class="form-container">
                    <div class="form-group">
                        <label for="email" class="form_half">Email</label>
                        <input type="text" required name="email" id="email" placeholder="you@email.com" class="form_c form_full">
                    </div>    

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" required name="password" id="pass" placeholder="************" class="form_full form_c">
                    </div>
                    <p class="login-msc">
                        <a href="reset-password">Forgot password?</a>
                        Don't have an account? <a href="signup">Sign up</a>.
                    </p>
                    <div class="form-group">
                        <button id="submitForm" name="submitLogin" class="pull-right btn">Sign in</button>
                    </div>
                </div>
            </form>
        </div>
        </div>
    </div>
    <?php include "includes/footer.php"; ?>
</body>