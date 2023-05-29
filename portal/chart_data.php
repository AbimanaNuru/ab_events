



<?php
header('Content-Type: application/json');
include_once('config.php');
session_start();
include "sessionexpired.php";


    $sqlQuery = "SELECT rent_process_rent_date,sum(rent_process_total_price) as price FROM  ab_events_material_rent_process group by rent_process_rent_date ";
    $result = mysqli_query($connection, $sqlQuery);
    $data = array();
    foreach ($result as $row) {
        $data[] = $row;
    }
    echo json_encode($data);
?>


