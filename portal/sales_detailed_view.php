<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";
if ($user_type != 'Administrator') {
    header("location: dashboard.php");
}

if (isset($_POST['qty_make_change'])) {
    // $m_id = $_POST['m_id'];
    // $m_quantity = $_POST['m_quantity'];
    $rent_process_id = mysqli_real_escape_string($connection, $_POST['rent_process_id']);
    $rent_process_qty = mysqli_real_escape_string($connection, $_POST['rent_process_qty']);
    $change_qty_comment = mysqli_real_escape_string($connection, $_POST['m_comment']);
    // Assuming you have a database connection established, you can use a foreach loop to update rent processes for each $m_id

    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    $query = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process WHERE rent_process_id = '$rent_process_id'");
    $rent_process = mysqli_fetch_assoc($query);
    $price = $rent_process['rent_process_price'];
    $tprice = $rent_process['rent_process_total_price'];
    $t_code = $rent_process['rent_process_rent_id'];
    $rent_process_quantity = $rent_process['rent_process_qty'];
    $total_price = $price * $rent_process_qty;

    $queryv2 = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction WHERE rent_transaction_code = '$t_code'");
    $t_rent_process = mysqli_fetch_assoc($queryv2);
    $price_v2 = $t_rent_process['rent_transaction_total_per_day'];
    $rent_transaction_day = $t_rent_process['rent_transaction_day'];
    $total_price_v2 = $price_v2 - $tprice;
    $confirm_total_price_v2 = $total_price_v2 + $total_price;
    $rent_transaction_total_price = $rent_transaction_day *  $confirm_total_price_v2;

    $query_quantity = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
     ab_events_material.ab_events_material_id =  ab_events_material_rent_process.rent_process_material_id AND ab_events_material_rent_process.rent_process_id = '$rent_process_id'");
    $transaction_quantity = mysqli_fetch_assoc($query_quantity);
    $material_id = $transaction_quantity['ab_events_material_id'];
    $material_quantity = $transaction_quantity['ab_events_material_available_qty'];

    $total_quantity = $material_quantity + $rent_process_quantity;

    $updateRentProcess = ""; // Initialize the variable
    $updateRentTransaction = ""; // Initialize the variable

    switch (true) {
        case ($rent_process_qty > $total_quantity):
            $fail = "No Available Quantity In Stock";
            header("Refresh: 2; url=sales_detailed_view.php");
            break;

        case ($rent_process_qty > $rent_process_quantity):
            $new_qty = $total_quantity - $rent_process_qty;
            $updatematerialquantity = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty ='$new_qty' WHERE ab_events_material_id  = '$material_id'");
            $updateRentProcess = "UPDATE ab_events_material_rent_process SET rent_process_qty ='$rent_process_qty',
            rent_process_total_price = '$total_price' WHERE rent_process_id  = '$rent_process_id'";
            $updateRentTransaction = "UPDATE ab_events_rent_transaction SET rent_transaction_total_per_day ='$confirm_total_price_v2',rent_transaction_total_price ='$rent_transaction_total_price'
                     WHERE rent_transaction_code  = '$t_code'";
            $updatematerialquantity = mysqli_query($connection, "INSERT INTO abe_events_rent_qty_tracking(qty_tracking_material_id,qty_tracking_transaction_code,qty_tracking_user_id,qty_tracking_current_qty,qty_tracking_new_qty,qty_tracking_material_stock_before,qty_tracking_left_material_stock,qty_tracking_comment,qty_tracking_date_time) 
 VALUES('$material_id','$t_code','$ab_user_id','$rent_process_quantity','$rent_process_qty','$material_quantity','$new_qty','$change_qty_comment',NOW())");


            break;

        case ($rent_process_qty < $rent_process_quantity):
            $new_qty_v2 = $rent_process_quantity - $rent_process_qty;
            $updated_qty = $new_qty_v2 + $material_quantity;
            $updatematerialquantity = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty ='$updated_qty' WHERE ab_events_material_id  = '$material_id'");
            $updateRentProcess = "UPDATE ab_events_material_rent_process SET rent_process_qty ='$rent_process_qty',
            rent_process_total_price = '$total_price' WHERE rent_process_id  = '$rent_process_id'";
            $updateRentTransaction = "UPDATE ab_events_rent_transaction SET rent_transaction_total_per_day ='$confirm_total_price_v2',rent_transaction_total_price ='$rent_transaction_total_price'
                     WHERE rent_transaction_code  = '$t_code'";
            $updatematerialquantity = mysqli_query($connection, "INSERT INTO abe_events_rent_qty_tracking(qty_tracking_material_id,qty_tracking_transaction_code,qty_tracking_user_id,qty_tracking_current_qty,qty_tracking_new_qty,qty_tracking_material_stock_before,qty_tracking_left_material_stock,qty_tracking_comment,qty_tracking_date_time) 
                                 VALUES('$material_id','$t_code','$ab_user_id','$rent_process_quantity','$rent_process_qty','$material_quantity','$updated_qty','$change_qty_comment',NOW())");
            break;

        case ($rent_process_qty == $rent_process_quantity):

            break;
    }
}

if (isset($_POST['booked_qty_make_change'])) {
    // $m_id = $_POST['m_id'];
    // $m_quantity = $_POST['m_quantity'];
    $rent_process_id = mysqli_real_escape_string($connection, $_POST['rent_process_id']);
    $rent_process_qty = mysqli_real_escape_string($connection, $_POST['rent_process_qty']);
    $change_qty_comment = mysqli_real_escape_string($connection, $_POST['m_comment']);
    // Assuming you have a database connection established, you can use a foreach loop to update rent processes for each $m_id

    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    $query = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process WHERE rent_process_id = '$rent_process_id'");
    $rent_process = mysqli_fetch_assoc($query);
    $price = $rent_process['rent_process_price'];
    $tprice = $rent_process['rent_process_total_price'];
    $t_code = $rent_process['rent_process_rent_id'];
    $rent_process_quantity = $rent_process['rent_process_qty'];
    $total_price = $price * $rent_process_qty;

    $queryv2 = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction WHERE rent_transaction_code = '$t_code'");
    $t_rent_process = mysqli_fetch_assoc($queryv2);
    $price_v2 = $t_rent_process['rent_transaction_total_per_day'];
    $rent_transaction_day = $t_rent_process['rent_transaction_day'];
    $transaction_total_price = $t_rent_process['rent_transaction_total_price'];
    $transaction_total_paid_money = $t_rent_process['rent_transaction_paid_money'];
    $total_price_v2 = $price_v2 - $tprice;
    $confirm_total_price_v2 = $total_price_v2 + $total_price;
    $rent_transaction_total_price = $rent_transaction_day *  $confirm_total_price_v2;

    $query_quantity = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
     ab_events_material.ab_events_material_id =  ab_events_material_rent_process.rent_process_material_id AND ab_events_material_rent_process.rent_process_id = '$rent_process_id'");
    $transaction_quantity = mysqli_fetch_assoc($query_quantity);
    $material_id = $transaction_quantity['ab_events_material_id'];
    $material_quantity = $transaction_quantity['ab_events_material_available_qty'];

    $total_quantity = $material_quantity + $rent_process_quantity;

    $pre_total = $transaction_total_price - $tprice;
    $new_total_price = $rent_process_qty * $price;
    $final_price = $new_total_price - $transaction_total_paid_money;

    $updateRentProcess = ""; // Initialize the variable
    $updateRentTransaction = ""; // Initialize the variable

    switch (true) {
        case ($rent_process_qty > $total_quantity):
            $fail = "No Available Quantity In Stock";
            header("Refresh: 2; url=sales_detailed_view.php");
            break;

        case ($rent_process_qty > $rent_process_quantity):
            $new_qty = $total_quantity - $rent_process_qty;



            $updatematerialquantity = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty ='$new_qty' WHERE ab_events_material_id  = '$material_id'");
            $updateRentProcess = "UPDATE ab_events_material_rent_process SET rent_process_qty ='$rent_process_qty',
            rent_process_total_price = '$total_price' WHERE rent_process_id  = '$rent_process_id'";
            $updateRentTransaction = "UPDATE ab_events_rent_transaction SET rent_transaction_credit_money='$final_price',rent_transaction_total_per_day ='$confirm_total_price_v2',rent_transaction_total_price ='$rent_transaction_total_price'
                     WHERE rent_transaction_code  = '$t_code'";
            $updatematerialquantity = mysqli_query($connection, "INSERT INTO abe_events_rent_qty_tracking(qty_tracking_material_id,qty_tracking_transaction_code,qty_tracking_user_id,qty_tracking_current_qty,qty_tracking_new_qty,qty_tracking_material_stock_before,qty_tracking_left_material_stock,qty_tracking_comment,qty_tracking_date_time) 
 VALUES('$material_id','$t_code','$ab_user_id','$rent_process_quantity','$rent_process_qty','$material_quantity','$new_qty','$change_qty_comment',NOW())");


            break;

        case ($rent_process_qty < $rent_process_quantity):
            $new_qty_v2 = $rent_process_quantity - $rent_process_qty;
            $updated_qty = $new_qty_v2 + $material_quantity;
            $updatematerialquantity = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty ='$updated_qty' WHERE ab_events_material_id  = '$material_id'");
            $updateRentProcess = "UPDATE ab_events_material_rent_process SET rent_process_qty ='$rent_process_qty',
            rent_process_total_price = '$total_price' WHERE rent_process_id  = '$rent_process_id'";
            $updateRentTransaction = "UPDATE ab_events_rent_transaction SET rent_transaction_credit_money='$final_price',rent_transaction_total_per_day ='$confirm_total_price_v2',rent_transaction_total_price ='$rent_transaction_total_price'
                     WHERE rent_transaction_code  = '$t_code'";
            $updatematerialquantity = mysqli_query($connection, "INSERT INTO abe_events_rent_qty_tracking(qty_tracking_material_id,qty_tracking_transaction_code,qty_tracking_user_id,qty_tracking_current_qty,qty_tracking_new_qty,qty_tracking_material_stock_before,qty_tracking_left_material_stock,qty_tracking_comment,qty_tracking_date_time) 
                                 VALUES('$material_id','$t_code','$ab_user_id','$rent_process_quantity','$rent_process_qty','$material_quantity','$updated_qty','$change_qty_comment',NOW())");
            break;
        case ($rent_process_qty == $rent_process_quantity):

            break;
    }

    // Check if $updateRentProcess is not empty before executing the query
    if (!empty($updateRentProcess)) {
        // Example: Assuming you are using mysqli extension
        $result1 = mysqli_query($connection, $updateRentProcess);
        $result2 = mysqli_query($connection, $updateRentTransaction);

        // Check if both queries were successful
        if ($result1 && $result2 && $updatematerialquantity) {
            // Handle the case when both updates are successful
            $success = "Material Quantity Edited Successfully";
            header("Refresh: 2; url=sales_detailed_view.php");
        } else {
            $fail = "Something Went Wrong";
            header("Refresh: 2; url=sales_detailed_view.php");
        }
    }
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Sales Detailed View | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">




    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <style>
        .required {
            color: red;
        }

        .table td,
        .table th {
            padding: 2px;
            font-size: 11px;
        }
    </style>
</head>

<body class="fixed-navbar has-animation fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php include "portal_slider_header.php" ?>

        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title">Sales Detailed View</h1>

            </div>
            <div class="page-content fade-in-up">
                <div class="row">

                    <div class="col-md-12">

                        <?php
                        if (isset($success) & !empty($success)) {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
    <strong>$success!</strong>
</div>";
                        }
                        if (isset($fail) & !empty($fail)) {
                            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
    <strong>$fail!</strong>
</div>";
                        }

                        ?>
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Rents</div>
                                <a href="rent_process"><button class='btn btn-primary'>Transaction View</button></a>

                            </div>
                            <div class="ibox-body">
                                <table id="example" class="table is-striped responsive nowrap" style="width:100%">

                                    <thead>
                                        <tr>
                                            <th>Rent ID</th>
                                            <th>Sale Mode</th>
                                            <th>C_Name</th>
                                            <th>P_Name</th>
                                            <th>Qty</th>
                                            <th></th>
                                            <th>Price</th>
                                            <th>Total/ F</th>
                                            <th>Balance</th>
                                            <th>Rent Date</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>



                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Rent ID</th>
                                            <th>Sale Mode</th>
                                            <th>C_Name</th>
                                            <th>P_Name</th>
                                            <th>Qty</th>
                                            <th></th>
                                            <th>Price</th>
                                            <th>Total/ F</th>
                                            <th>Balance</th>
                                            <th>Rent Date</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>


                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $result = mysqli_query($connection, "SELECT * FROM 
                                        ab_events_rent_transaction,ab_events_clients,ab_events_material_rent_process,ab_events_material
                                        WHERE ab_events_rent_transaction.rent_transaction_clients_id =  ab_events_clients.client_id
                                         AND ab_events_material_rent_process.rent_process_rent_id = ab_events_rent_transaction.rent_transaction_code AND 
                                         ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id");


                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $rents_id = $row['rent_transaction_code'];
                                                $rents_process_id = $row['rent_process_id'];
                                                $rents_material_id = $row['rent_process_material_id'];
                                                $abevent_material_id = $row['ab_events_material_id'];
                                                $s_mode = $row['rent_transaction_sale_mode'];
                                                $credited_money = $row['rent_transaction_credit_money'];



                                                $change_material = mysqli_query($connection, "SELECT * from  abe_events_rent_qty_tracking,ab_events_material WHERE
                                                 abe_events_rent_qty_tracking.qty_tracking_material_id=ab_events_material.ab_events_material_id AND ab_events_material.ab_events_material_id = '$rents_material_id'");
                                        ?>

                                                <tr>
                                                    <td><b><?php echo $rents_id; ?> </b></td>
                                                    <td> <?php if ($s_mode == 'full_paid') {
                                                                echo "<b style='color:#6F0118;'>FULL PAID</b>";
                                                            }
                                                            if ($s_mode == 'booked_sale') {
                                                                echo "<b>BOOKED</b>";
                                                            } ?> </td>
                                                    <td> <?php echo $row['client_fullname']; ?> </td>
                                                    <td> <b><?php echo $row['ab_events_material_name']; ?> </b></td>
                                                    <td> <?php echo $row['rent_process_qty']; ?></td>
                                                    <td>
                                                        <button class="btn btn-success badge-circle  btn-xs m-r-5" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Track Who Rents Other Quantity" data-target="#material<?php echo $rents_material_id; ?>">Qty Changes</button>
                                                    </td>
                                                    <td> <?php echo $row['rent_process_price']; ?></td>
                                                    <td> <?php echo $row['rent_process_total_price']; ?></td>
                                                    <td> <?php echo " <b>$credited_money</b>"; ?></td>

                                                    <td> <?php echo $row['rent_transaction_rent_date']; ?></td>
                                                    <td> <?php echo $row['rent_transaction_return_date']; ?></td>
                                                    <td> <?php if ($row['rent_transaction_status'] == 'Not Returned') {
                                                                echo "  <span class='badge badge-danger badge-pill' style='color:white;'>Not Returned </span>";
                                                            } else {
                                                                echo "  <span class='badge badge-success badge-pill'style='color:white;'>Returned </span>";
                                                            }
                                                            ?>
                                                        <?php if ($row['rent_transaction_status'] == 'Not Returned') {
                                                            if ($s_mode == 'booked_sale') {
                                                                echo " <a class='badge badge-danger badge-pill' data-toggle='modal' data-target='#booked$rents_process_id' style='color:white;'><i class='fa fa-pencil-square-o' aria-hidden='true'></i> Edit</a>";
                                                            } else {
                                                                echo " <a class='badge badge-danger badge-pill' data-toggle='modal' data-target='#$rents_process_id' style='color:white;'><i class='fa fa-pencil' aria-hidden='true'></i> Edit</a>";
                                                            }
                                                        } else {
                                                        }
                                                        ?>



                                                    </td>
                                                </tr>


                                                <!-- Start  track how  quantity changed -->
                                                <div class="modal fade bd-example-modal-lg" id="material<?php echo $rents_material_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">How Material Data Changed</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <?php
                                                                if (mysqli_num_rows($change_material) > 0) {
                                                                    while ($pro_quantity = mysqli_fetch_assoc($change_material)) {
                                                                ?>
                                                                        <center>
                                                                            <p> <b><?php echo $pro_quantity['ab_events_material_name']; ?></b> </p>
                                                                        </center>
                                                                        <div style="padding:10px; background-color: #E3E7EB; border-radius: 10px;">
                                                                            <h6><b>Material Transaction Code: </b><?php echo $pro_quantity['qty_tracking_transaction_code']; ?></h6>
                                                                            <h6><b>Sales Current Quantity: </b><?php echo $pro_quantity['qty_tracking_current_qty']; ?></h6>
                                                                            <h6><b>Sales Changes Quantity: </b><?php echo $pro_quantity['qty_tracking_new_qty']; ?></h6>
                                                                            <h6><b>Material In Stock Before: </b><?php echo $pro_quantity['qty_tracking_material_stock_before']; ?></h6>
                                                                            <h6><b>Material In Stock After: </b><?php echo $pro_quantity['qty_tracking_left_material_stock']; ?></h6>
                                                                            <h6><b>Comments: </b><?php echo $pro_quantity['qty_tracking_comment']; ?></h6>
                                                                            <h6><b>Changes Date And Time: </b><?php echo $pro_quantity['qty_tracking_date_time']; ?></h6>




                                                                        </div>
                                                                        <hr>
                                                                <?php
                                                                    }
                                                                } else {
                                                                    echo "<center><h5 style='color:red; '>No Data Founds</h5></center>";
                                                                }
                                                                ?>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- End  track how  quantity changed -->



                                                <!-- start of model -->
                                                <div class="modal fade" id="<?php echo  $rents_process_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Material Edit Quantity</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="POST">
                                                                <div class="modal-body">

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p style="padding: 5px; background-color: #700018;color:white; width:100;text-align: center;"><?php echo $rents_id; ?></p>
                                                                        </div><br>
                                                                        <br>

                                                                        <input type="hidden" name="rent_process_id" value="<?php echo $rents_process_id; ?>">

                                                                        <div class="col-md-12">
                                                                            <label for="">Product Quantity</label>
                                                                            <input type="text" name="rent_process_qty" pattern="[0-9]+" value="<?php echo $row['rent_process_qty']; ?>" class="form-control" placeholder="Provide Qty" required></input>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label for="">Why You Change This Quantity</label>
                                                                            <textarea name="m_comment" id="" class="form-control" placeholder="Provide Comments" cols="3" rows="2" required></textarea>
                                                                        </div>

                                                                        <div class="col-md-12"> <br>
                                                                            <button class="btn btn-dark btn-block" type="submit" name="qty_make_change">Save Changes</button>
                                                                        </div>

                                                                    </div>




                                                                </div>
                                                            </form>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ends of model -->



                                                <!-- start of model -->
                                                <div class="modal fade" id="booked<?php echo  $rents_process_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Material Edit Quantity</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="" method="POST">
                                                                <div class="modal-body">

                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <p style="padding: 5px; background-color: #700018;color:white; width:100;text-align: center;"><?php echo $rents_id; ?></p>
                                                                        </div><br>
                                                                        <br>

                                                                        <input type="hidden" name="rent_process_id" value="<?php echo $rents_process_id; ?>">

                                                                        <div class="col-md-12">
                                                                            <label for="">Product Quantity</label>
                                                                            <input type="text" name="rent_process_qty" pattern="[0-9]+" value="<?php echo $row['rent_process_qty']; ?>" class="form-control" placeholder="Provide Qty" required></input>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <label for="">Why You Change This Quantity</label>
                                                                            <textarea name="m_comment" id="" class="form-control" placeholder="Provide Comments" cols="3" rows="2" required></textarea>
                                                                        </div>

                                                                        <div class="col-md-12"> <br>
                                                                            <button class="btn btn-dark btn-block" type="submit" name="booked_qty_make_change">Save Changes</button>
                                                                        </div>

                                                                    </div>




                                                                </div>
                                                            </form>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- ends of model -->

                                        <?php
                                            }
                                        } else {
                                            echo "<h5 style='color:red; '>No Data Founds</h5>";
                                        }
                                        ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- Button trigger modal -->



            <!-- END PAGE CONTENT-->
            <!-- <footer class="page-footer">
                <div class="font-13">2023 © <b>AB EVENTS GROUP</b> - All rights reserved.</div>
                <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
            </footer> -->
        </div>
    </div>

    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">AB Events Group</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS-->
    <script src="assets/vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="assets/vendors/popper.js/dist/umd/popper.min.js" type="text/javascript"></script>
    <script src="assets/vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="assets/vendors/metisMenu/dist/metisMenu.min.js" type="text/javascript"></script>
    <script src="assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="assets/vendors/select2/dist/js/select2.full.min.js" type="text/javascript"></script>

    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="assets/js/scripts/form-plugins.js" type="text/javascript"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>




    <script>
        $(document).ready(function() {
            $('#example').DataTable({

                lengthMenu: [
                    [20, 25, 50, -1],
                    [20, 25, 50, 'All'],
                ]
            });

        });
    </script>


    <script type="text/javascript">
        $(function() {
            $('#example-table').DataTable({
                pageLength: 10,
                //"ajax": './assets/demo/data/table_data.json',
                /*"columns": [
                    { "data": "name" },
                    { "data": "office" },
                    { "data": "extn" },
                    { "data": "start_date" },
                    { "data": "salary" }
                ]*/
            });
        })
    </script>
</body>

</html>