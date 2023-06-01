



<?php
header('Content-Type: application/json');
include_once('config.php');
session_start();
if (!isset($_SESSION['ab_user_email']) || empty($_SESSION['ab_user_email'])) {
    header("location: index.php");
} else {
    $now = time(); // Checking the time now when home page starts.

    if ($now > @$_SESSION['expire']) {
        session_destroy();
        echo "<script>alert(' YOUR SESSION IS EXPERED , YOU NEED TO LOGIN AGAIN !!!!'); window.location='logout.php'</script>";
    } else {

?>
            <?php
        }
    }


    $sqlQuery = "SELECT rent_process_rent_date,sum(rent_process_total_price) as price FROM  ab_events_material_rent_process group by rent_process_rent_date ";
    $result = mysqli_query($connection, $sqlQuery);
    $data = array();
    foreach ($result as $row) {
        $data[] = $row;
    }
    echo json_encode($data);
?>


