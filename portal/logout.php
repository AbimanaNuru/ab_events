

    <?php
include "config.php"; 
    // Initialize the session
    
    session_start();   
    // Unset all of the session variables
    unset($_SESSION['ab_user_email']);
   
    // Redirect to login page
    header("location: index.php");

    exit;

    ?>

