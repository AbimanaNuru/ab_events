<?php
include_once('config.php');
    if (!isset($_SESSION['ab_user_email']) || empty($_SESSION['ab_user_email'])){
               header("location: index.php");
    }
    else {
        $now = time(); // Checking the time now when home page starts.

        if ($now > @$_SESSION['expire']) {
            session_destroy();
            echo "<script>alert(' YOUR SESSION IS EXPERED , YOU NEED TO LOGIN AGAIN !!!!'); window.location='logout.php'</script>";
        }
        else {
            
            ?>
            <?php
        }
    }
?>
