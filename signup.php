<?php 
    include "includes/head.php";
    include "includes/page-nav.php";
    include "controller/searchController.php";
?>

<body>
    <div class="main-content">
        <div class="center-page">
            <?php if(isset($error['message']) && count($error['message'])): ?>
                <div class="alert-<?php echo $error['type'] ?>">
                    <ul>
                <?php foreach($error['message'] as $err): ?>
                        <li class="alert-list"><?php echo $err; ?></li>
                <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['setpassword'])): 
                    include "includes/activate-acc.php";
                else: 
                    include "includes/signup-form.php";
                endif;  
            ?>
        </div>
    </div>
    <?php include "includes/footer.php"; ?>
</body>