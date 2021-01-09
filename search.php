<?php include "includes/head.php"; ?>
<body>
    <!-- <div class="yellow-stroke"></div> -->
    <?php include "includes/page-nav.php"; ?>
    <div class="top-sect">
        <div class="brand display-large">
            <a href="/">
                <img src="assets/img/lorveet_logo.png" height="50" alt="">
            </a>
        </div>
        <div class="display-small text-logo">
            <center>
                <img src="assets/img/lorveet_logo.png" height="50" alt="">
            </center>
        </div>
        <div class="search-box-result">
            <?php include "controller/searchController.php"; ?>
            <?php include "controller/adsController.php"; ?>
            <form action="" method="POST">
                <button class="btn-search" type="submit" id="submit-search-btn">
                    <img src="assets/img/lorveetgenie.png" alt="" srcset="">
                </button>
                <input type="text" placeholder='Search "Dry Cleaner"' name="job-search" id="" class="search-form" value="<?php echo isset($_GET['q']) ? urldecode($_GET['q']) : ""; ?>">
                <span id="cancel-search">&times;</span>
            </form>
        </div>
    </div>
    <!-- ad display banner -->
    <?php include "includes/adDisplay.php"; ?>
    <?php include "includes/search-suggestions.php"; ?>

    <?php include "includes/search-main.php"; ?>
    
    <style>
        .brand-area.display-large{
            display: none !important;
        }
    </style>
</body>
<?php include "includes/footer.php"; ?>

</html>
