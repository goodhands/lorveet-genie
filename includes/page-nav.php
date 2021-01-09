<?php require_once "controller/userController.php"; ?>
<div class="nav-area">
    <div class="brand-area display-large">
        <!-- Logo here -->
        <a href="/">
            <img src="assets/img/lorveet_logo.png" alt="" srcset="">
        </a>
    </div>
    <div class="nav-sub">
        <div class="nav-pages display-large pull-left">
            <ul class="pull-left">
                <li><a href="https://www.lorveet.com">Home</a></li>
                <li><a href="http://blog.lorveet.com">Blog</a></li>
                <li><a href="https://www.lorveet.com/resources">Resources</a></li>
                <li><a href="advertise">Advertise</a></li>
            </ul>
        </div>
        <div class="nav-pages display-large pull-right">
            <ul class="pull-right">
                <li><a href="http://blog.lorveet.com/about-lorveet">About Us</a></li>
                <?php if($sessionid === ""): ?>
                <li><a href="login">Sign in</a></li>
                <li><a href="signup">Sign Up</a></li>
                <?php 
                    else: 
                        $name = explode(" ", $user->getUsername($sessionid))[0];
                        echo "<li><a>$name</a></li>"; 
                    endif;  
                ?>

            </ul>
        </div>

        <div class="pull-left hide-on-search" style="margin:10px;">
            <a href="/">
                <img src="assets/img/lorveet_logo.png" height="50" alt="" srcset="">
            </a>
        </div>

        <div class="mobile-nav pull-right">
            <a href="#" data-open="menu" class="mobile-menu display-small">
                <i class="fas fa-align-left"></i>
            </a>
        </div>
    </div>
</div>

<div class="mobile-menu-pop">
    <span class="close" data-close-modal="menu">&times;</span>
    <ul class="pull-right">
        <li><a href="https://www.lorveet.com">Home</a></li>
        <li><a href="advertise">Advertise</a></li>
        <li><a href="login">Sign in</a></li>
        <li><a href="signup">Sign Up</a></li>
        <li><a href="http://blog.lorveet.com/resources">Resources</a></li>
        <li><a href="http://blog.lorveet.com/about-lorveet">About Us</a></li>
        <li><a href="http://blog.lorveet.com">Blog</a></li>        
    </ul>
</div>