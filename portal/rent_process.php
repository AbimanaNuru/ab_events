<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";


if (isset($_POST['make_change'])) {
    // $m_id = $_POST['m_id'];
    // $m_quantity = $_POST['m_quantity'];
    $m_comment = $_POST['m_comment'];
    $code = $_POST['rent_id'];
    @$notify = mysqli_real_escape_string($connection, $_POST['nofity_client']);
    $c_id = mysqli_real_escape_string($connection, $_POST['clients_id']);

    $get_client = mysqli_query($connection, "SELECT * from ab_events_clients WHERE client_id  = '$c_id' ");
    $clients_info = mysqli_fetch_assoc($get_client);
    $client_phonenumber = $clients_info['client_phonenumber'];

    // Assuming you have a database connection established, you can use a foreach loop to update rent processes for each $m_id

    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    $query = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process WHERE rent_process_rent_id = '$code'");
    $materials = mysqli_fetch_all($query, MYSQLI_ASSOC);


    foreach ($materials as $material) {
        $material_id = $material['rent_process_material_id'];
        #add rent_process_qty
        $material_quantity = $material['rent_process_qty'];
        #select from ab_events_material where ab_events_material_id = $material_id and update ab_events_material_quantities = $material_quantity
        $query = mysqli_query($connection, "SELECT * FROM ab_events_material WHERE ab_events_material_id = $material_id");
        $material = mysqli_fetch_assoc($query);
        $product_quantity = $material['ab_events_material_available_qty'];
        $pquantity = $product_quantity + $material_quantity;
        $query = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty = '$pquantity' WHERE ab_events_material_id = '$material_id'");
    }
    // Perform an UPDATE query based on $id
    $query = "UPDATE ab_events_rent_transaction SET rent_transaction_status ='Returned',
    rent_process_return_comments = '$m_comment'
         WHERE rent_transaction_code = '$code'";

    // Example: Assuming you are using mysqli extension
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result) {
        $success = "Material Returned Successfully";
        header("Refresh: 2; url= rent_process.php");
        if ($notify == 'on') {
            $data = array(
                "sender" => 'AB EVENTS',
                "recipients" => "$client_phonenumber",
                "message" => "Hello! Thank Your For Returning Materials : R_ID: $code, Book Us: 0785752797,0783236256 | www.abeventsgroup.com",
                "dlrurl" => ""
            );
            $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
            $data = http_build_query($data);
            $username = "abelia.ltd";
            $password = "abelia.ltd";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
        }
        // Handle the case when the update is successful

    } else {
        $fail = "Something Wrong";
        header("Refresh: 2; url= rent_process.php");
    }
}

if (isset($_POST['make_change_rent_transactions'])) {
    $rent_date = mysqli_real_escape_string($connection, $_POST['rent_date']);
    $return_date = mysqli_real_escape_string($connection, $_POST['return_date']);
    // Create DateTime objects from the rent and return dates
    $rentDateTime = new DateTime($rent_date);
    $returnDateTime = new DateTime($return_date);
    // Calculate the difference between the two dates
    $interval = $rentDateTime->diff($returnDateTime);
    // Get the number of days from the interval
    $rent_day = $interval->days;
    $rent_id = mysqli_real_escape_string($connection, $_POST['rent_id']);

    $rent_query = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction WHERE rent_transaction_code = '$rent_id'");
    $rent_transaction = mysqli_fetch_assoc($rent_query);
    $money_per_day = $rent_transaction['rent_transaction_total_per_day'];
    $total_money = 1000;
    $query = "UPDATE ab_events_rent_transaction SET  rent_transaction_rent_date= '$rent_date',rent_transaction_return_date = '$return_date',rent_transaction_day = '$rent_day',
    rent_transaction_total_price = '$total_money' WHERE rent_transaction_code = '$rent_id'";
    // Example: Assuming you are using mysqli extension
    $result = mysqli_query($connection, $query);
    // Check if the query was successful
    if ($result) {



        // Handle the case when the update is successful
        $success = "Rent Trsaction Edited Successfully";
        header("Refresh: 2; url= rent_process.php");
    } else {
        $fail = "Something Wrong";
        header("Refresh: 2; url= rent_process.php");
    }
}
if (isset($_POST['save_paid_money'])) {

    $rent_id = mysqli_real_escape_string($connection, $_POST['rent_id']);
    @$notify = mysqli_real_escape_string($connection, $_POST['nofity_client']);

    $paid_money = mysqli_real_escape_string($connection, $_POST['paid_amount']);

    $rent_query = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction,ab_events_clients WHERE ab_events_clients.client_id = ab_events_rent_transaction.rent_transaction_clients_id AND  ab_events_rent_transaction.rent_transaction_code = '$rent_id'");
    $rent_transaction = mysqli_fetch_assoc($rent_query);
    $current_money = $rent_transaction['rent_transaction_credit_money'];
    $client_phone = $rent_transaction['client_phonenumber'];
    $client_id = $rent_transaction['rent_transaction_clients_id'];

    if ($paid_money > $current_money) {
        $fail = " Added Payment cannot be greater than Current Balance Money";
        header("Refresh: 2; url= rent_process.php");
    } else {
        $updated_credited_money = $current_money - $paid_money;

        $query = mysqli_query($connection, "UPDATE ab_events_rent_transaction SET rent_transaction_credit_money = '$updated_credited_money'
    WHERE rent_transaction_code = '$rent_id'");

        $query = mysqli_query($connection, "INSERT INTO tracking_payment(t_code,client_id,current_credit,track_paid_money,updated_credited_money,track_date_added) 
        VALUES('$rent_id','$client_id','$current_money','$paid_money','$updated_credited_money',NOW())");
        // Check if the query was successful
        if ($query) {
            if ($notify == 'on') {
                $data = array(
                    "sender" => 'AB EVENTS',
                    "recipients" => "$client_phone",
                    "message" => "Hello! Thank Your For Payments, Paid:$paid_money Rwf; Balance: $updated_credited_money Rwf Book Us: 0785752797,0783236256 | www.abeventsgroup.com",
                    "dlrurl" => ""
                );
                $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
                $data = http_build_query($data);
                $username = "abelia.ltd";
                $password = "abelia.ltd";

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                $result = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
            }
            // Handle the case when the update is successful
            $success = "Payment Addded Successfully";
            header("Refresh: 1; url= rent_process.php");
        } else {
            $fail = "Something Wrong";
            header("Refresh: 1; url= rent_process.php");
        }
    }
}

if (isset($_POST['resend_sms'])) {

    $phonenumber = mysqli_real_escape_string($connection, $_POST['phone__number']);
    $message = mysqli_real_escape_string($connection, $_POST['resend_message']);
    $data = array(
        "sender" => 'AB EVENTS',
        "recipients" => "$phonenumber",
        "message" => "$message",
        "dlrurl" => ""
    );
    $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
    $data = http_build_query($data);
    $username = "abelia.ltd";
    $password = "abelia.ltd";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $success = "Message Resend Successfully";
    header("Refresh: 1; url= rent_process.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Material Rents Managemnets | AB Events | an exceptional experience</title>
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
                <h1 class="page-title">Material Sales Managements</h1>

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
                        } ?>
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Sales Transaction List</div>
                                <?php
                                // Financial
                                if ($user_type == 'Administrator') {
                                ?>

                                    <a href="sales_detailed_view"><button class='btn btn-dark' id='day1'>Transaction Detailed View</button></a>
                                <?php
                                }
                                ?>

                            </div>
                            <div class="ibox-body">
                                <table id="example" class="table is-striped responsive nowrap" style="width:100%">

                                    <thead>
                                        <tr>
                                            <th>Rent ID</th>
                                            <th>Sale Mode</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Detail</th>
                                            <th>Price/Day</th>
                                            <th>Day</th>
                                            <th>Total/F</th>
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
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Detail</th>
                                            <th>Price/Day</th>
                                            <th>Day</th>
                                            <th>Total/F</th>
                                            <th>Balance</th>

                                            <th>Rent Date</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>


                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $result = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction,ab_events_clients
                                        WHERE ab_events_rent_transaction.rent_transaction_clients_id =  ab_events_clients.client_id ");


                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $rents_id = $row['rent_transaction_code'];
                                                $client_id = $row['client_id'];
                                                $day = $row['rent_transaction_day'];
                                                $price_day = $row['rent_transaction_total_per_day'];
                                                $credited_money = $row['rent_transaction_credit_money'];
                                                $s_mode = $row['rent_transaction_sale_mode'];
                                                $r_date = $row['rent_transaction_rent_date'];
                                                $re_date = $row['rent_transaction_return_date'];
                                                $toatl_day_price = $day * $price_day;

                                                $payment_tracking = mysqli_query($connection, "SELECT * FROM  ab_events_rent_transaction,tracking_payment WHERE
                                                ab_events_rent_transaction.rent_transaction_code = tracking_payment.t_code AND tracking_payment.t_code = '$rents_id'");


                                                $submenu =  mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
                    ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id  AND ab_events_material_rent_process.rent_process_rent_id = '$rents_id'");


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
                                                    <td> <?php echo $row['client_phonenumber']; ?> </td>
                                                    <td> <a class='badge badge-info badge-pill' data-toggle='modal' data-target='#view<?php echo $rents_id; ?>' style='color:white;'><i class="fa fa-eye" aria-hidden="true"></i> </a></td>
                                                    <td> <?php echo $row['rent_transaction_total_per_day']; ?></td>
                                                    <td> <?php echo $row['rent_transaction_day']; ?></td>
                                                    <td> <?php echo " <b>$toatl_day_price</b>"; ?></td>
                                                    <td> <?php echo " <b style='color:red;'>$credited_money</b>"; ?></td>
                                                    <td> <?php echo $row['rent_transaction_rent_date']; ?></td>
                                                    <td> <?php echo $row['rent_transaction_return_date']; ?></td>
                                                    <td> <?php
                                                            if ($row['rent_transaction_status'] == 'Not Returned') {
                                                                // Financial
                                                                if ($user_type == 'Administrator') {
                                                                    echo "  <a class='badge badge-danger badge-pill' data-toggle='modal' data-target='#edit$rents_id' style='color:white;'><i class='fa fa-pencil'></i>
                                                                    </a>";
                                                                }

                                                                echo "  <a class='badge badge-primary badge-pill' data-toggle='modal' data-target='#send$rents_id'  style='color:white;'><i class='fa fa-paper-plane'></i>
                                                                    </a>";
                                                                if ($s_mode == 'booked_sale') {

                                                                    echo "  <a class='badge badge-primary badge-pill' data-toggle='modal' data-target='#credit$rents_id' style='color:white;'><i class='fa fa-plus-circle'></i>
                                                                        </a>";
                                                                }


                                                                echo "  <a class='badge badge-danger badge-pill' data-toggle='modal' data-target='#$rents_id' style='color:white;'>Return </a>";
                                                            } else {
                                                                echo "  <span class='badge badge-success badge-pill'style='color:white;'>Returned </span>";
                                                            }
                                                            ?>
                                                        <a href="invoice.php?invoice_code=<?php echo  $rents_id;  ?>"><span class="badge badge-info badge-circle m-r-5 m-b-5"><i class="fa fa-print" aria-hidden="true"></i>
                                                            </span></a>
                                                    </td>
                                                </tr>

                                                <!-- start view detail modal -->
                                                <div class="modal fade bd-example-modal-lg" id="view<?php echo  $rents_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Rent Transaction Details</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="row" style="padding:10px;">
                                                                <div class="col-md-4"><b>Material Name</b></div>
                                                                <div class="col-md-4"><b>Quantity</b></div>
                                                                <div class="col-md-4"><b>Totla Price</b></div>
                                                            </div>
                                                            <?php
                                                            $sump = mysqli_query($connection, "SELECT  SUM(rent_process_total_price) as total_trans_money FROM ab_events_material_rent_process WHERE rent_process_rent_id = '$rents_id'");

                                                            while ($sub = mysqli_fetch_assoc($submenu)) {
                                                                $m_name = $sub['ab_events_material_name'];
                                                                $m_quantity = $sub['rent_process_qty'];
                                                                $m_price = number_format($sub['rent_process_total_price']);

                                                            ?>
                                                                <div class="row" style="padding:10px;">
                                                                    <div class="col-md-4"><?php echo $m_name; ?></div>
                                                                    <div class="col-md-4"><?php echo $m_quantity; ?></div>
                                                                    <div class="col-md-4"><?php echo $m_price; ?></div>
                                                                </div>
                                                            <?php }
                                                            ?>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end view detail modal -->



                                                <!-- Start edit transaction -->
                                                <div class="modal fade bd-example-modal-sm" id="edit<?php echo  $rents_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered  modal-sm" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Edit Rents Transactions</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <form action="" method="POST">
                                                                <input type="hidden" name="rent_id" value="<?php echo $rents_id; ?>">
                                                                <div class="col-sm-12 form-group">
                                                                    <label>Rent Date <b class="required">*</b></label>
                                                                    <input class="form-control edit_rent-date-input" type="date" name="rent_date" value="<?php echo $row['rent_transaction_rent_date']; ?>" required>
                                                                </div>
                                                                <div class="col-sm-12 form-group">
                                                                    <label>Return Date <b class="required">*</b></label>
                                                                    <input class="form-control edit_rent-date-input" type="date" name="return_date" value="<?php echo $row['rent_transaction_return_date']; ?>" required>
                                                                </div>


                                                                <div class="col-md-12"> <br>
                                                                    <button class="btn btn-dark btn-block" type="submit" name="make_change_rent_transactions" onclick="calculateDays()">Save Changes</button>
                                                                </div>

                                                            </form>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Start add new payments for booked Sale -->
                                                <div class="modal fade bd-example-modal-lg" id="credit<?php echo  $rents_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Transaction Balance Managements</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <form action="" method="POST">
                                                                <div style="padding: 20px;">
                                                                    <div class="row">
                                                                        <input type="hidden" name="rent_id" value="<?php echo $rents_id; ?>">
                                                                        <div class="col-sm-6 form-group">
                                                                            <label>Current Balance Money <b class="required">*</b></label>
                                                                            <input class="form-control" type="text" name="rent_date" readonly value="<?php echo $credited_money; ?>" required>
                                                                        </div>
                                                                        <div class="col-sm-6 form-group">
                                                                            <label>Add Payment<b class="required">*</b></label>
                                                                            <input class="form-control" type="text" placeholder="Add Payment" name="paid_amount" required>
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            <input type="checkbox" name="nofity_client" value="on"> <b style="color: green; font-size: 12px;">Notify Client By Message</b>
                                                                            <button class="btn btn-dark btn-block" type="submit" name="save_paid_money">Save Changes</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                            <center>
                                                                <p> <b> Previous Payments History</b></p>
                                                            </center>
                                                            <?php

                                                            while ($tra = mysqli_fetch_assoc($payment_tracking)) {
                                                                $added_date = $tra['track_date_added'];
                                                                $current_credit = number_format($tra['current_credit']);
                                                                $track_paid_money = number_format($tra['track_paid_money']);
                                                                $updated_credited_money = number_format($tra['updated_credited_money']);
                                                            ?>
                                                                <!-- <div class="col-md-6"> <input type="text" value=""> </div> -->
                                                                <div class="col-md-12 ">
                                                                    <div style="background-color: #F1F1F1; border-radius: 20px; padding: 10px;">
                                                                        <h6><b>Current Balance:</b> <?php echo $current_credit; ?></h6>
                                                                        <h6><b>Paid Money:</b> <?php echo $track_paid_money; ?></h6>
                                                                        <h6><b>New Balance:</b> <?php echo $updated_credited_money; ?></h6>
                                                                        <h6><b>Date:</b> <?php echo $added_date; ?></h6>
                                                                    </div>
                                                                    <hr>
                                                                </div>



                                                            <?php
                                                            }
                                                            ?>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Ends add new payments for booked Sale -->

                                                <!-- start resend sms -->
                                                <div class="modal fade bd-example-modal-sm" id="send<?php echo  $rents_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered  modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Resend Message</b></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <?php if ($s_mode == 'full_paid') {
                                                            ?>
                                                                <form action="" method="POST">
                                                                    <input type="hidden" name="phone__number" value="<?php echo $row['client_phonenumber']; ?>">
                                                                    <div class="col-md-12">
                                                                        <textarea name="resend_message" cols="100" rows="10" maxlength="160"><?php echo "Hello! R_ID: $rents_id, Cost: $toatl_day_price Rwf, Day:$day, Rent Date:$r_date, Return Date:$re_date, Book Us: 0783236256 | www.abeventsgroup.com"; ?></textarea>
                                                                    </div>
                                                                    <div class="col-md-12"> <br>
                                                                        <button class="btn btn-dark btn-block" type="submit" name="resend_sms">Resend</button>
                                                                    </div>
                                                                </form>

                                                            <?php }
                                                            if ($s_mode == 'booked_sale') {
                                                            ?>
                                                                <form action="" method="POST">
                                                                    <input type="hidden" name="phone__number" value="<?php echo $row['client_phonenumber']; ?>">
                                                                    <div class="col-md-12">
                                                                        <textarea name="resend_message" cols="100" rows="10" maxlength="160"><?php echo "Hello! B_ID: $rents_id, Cost: $toatl_day_price Rwf,Paid: $track_paid_money F,Balance:$credited_money F,Rent:$r_date,Return:$re_date, Book Us: 0783236256 | www.abeventsgroup.com"; ?></textarea>
                                                                    </div>
                                                                    <div class="col-md-12"> <br>
                                                                        <button class="btn btn-dark btn-block" type="submit" name="resend_sms">Resend</button>
                                                                    </div>
                                                                </form>
                                                            <?php
                                                            } ?>


                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end resend sms -->




                                                <!-- start of model -->
                                                <div class="modal fade" id="<?php echo $rents_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle"><b>Material Return</b></h5>
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
                                                                        <?php
                                                                        $change = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
                                                                ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id  AND ab_events_material_rent_process.rent_process_rent_id = '$rents_id'");

                                                                        while ($sub = mysqli_fetch_assoc($change)) {
                                                                            $m_name = $sub['ab_events_material_name'];
                                                                            $m_quantity = $sub['rent_process_qty'];
                                                                            $m_id = $sub['rent_process_id'];
                                                                            $trans_code = $sub['rent_process_rent_id'];
                                                                            $m_price = number_format($sub['rent_process_total_price']);

                                                                        ?>
                                                                            <!-- <div class="col-md-6"> <input type="text" value=""> </div> -->
                                                                            <div class="col-md-12 ">
                                                                                <div style="background-color: #F1F1F1; border-radius: 20px; padding: 10px;">
                                                                                    <h6><b>Material Name:</b> <?php echo $m_name; ?></h6>
                                                                                    <h6><b>Material Quantity:</b> <?php echo $m_quantity; ?></h6>
                                                                                </div>
                                                                                <hr>
                                                                            </div>



                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <input type="hidden" name="rent_id" value="<?php echo $trans_code; ?>">
                                                                        <input type="hidden" name="clients_id" value="<?php echo $client_id; ?>">

                                                                        <div class="col-md-12">
                                                                            <label for="">Add Comments</label>

                                                                            <textarea name="m_comment" id="" class="form-control" placeholder="Provide Comments" cols="3" rows="2" required></textarea>
                                                                            <input type="checkbox" name="nofity_client" value="on"> <b style="color: green; font-size: 12px;">Notify Client By Message</b>
                                                                        </div>


                                                                        <div class="col-md-12"> <br>
                                                                            <button class="btn btn-dark btn-block" type="submit" name="make_change">Approve Return</button>
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
                                            echo "<h5 style='color:red; '>No Data Found</h5>";
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
            <footer class="page-footer">
                <div class="font-13">2023 © <b>AB EVENTS GROUP</b> - All rights reserved.</div>
                <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
            </footer>
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