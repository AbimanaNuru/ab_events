<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";
$today = date("Y-m-d");




if (isset($_POST['send_Reminder'])) {
    $send_to = $_POST['phone_number'];
    $send_message = mysqli_real_escape_string($connection, $_POST['send_message']);

    // Loop through the $send_to array
    foreach ($send_to as $phoneNumber) {
        $data = array(
            "sender" => 'AB EVENTS',
            "recipients" => $phoneNumber,
            "message" => $send_message,
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

        // Handle the result and HTTP status code as per your requirements
    }

    $success = "SMS sent successfully.";
    header("Refresh: 2; url=ab_events_reminder.php");
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
            padding: 3px;
            font-size: 12px;
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
                <h1 class="page-title">Material Rents reminder</h1>

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
                                <div class="ibox-title">Reminder</div>

                            </div>
                            <div class="ibox-body">
                                <form action="" method="POST">

                                    <label for="">Customize Message</label>
                                    <textarea name="send_message" id="" class="form-control" maxlength="160" placeholder="Provide Comments" cols="3" rows="2" required>Hello. It's Time To Return Materials . Materials are rented per day, delays in returning materials are subject to additional charges.</textarea>
                                    <br>
                                    <button class="btn btn-dark btn-block" name="send_Reminder" type="submit">Send Message</button>

                                    <br>

                                    <table id="example" class="table is-striped responsive nowrap" style="width:100%">

                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" class="check-all"></th>
                                                <th>C_Name</th>
                                                <th>C_Phone</th>
                                                <th>Detail</th>
                                                <th>Price/Day</th>
                                                <th>Day</th>
                                                <th>Total/Rwf</th>
                                                <th>Rent Date</th>
                                                <th>Return Date</th>
                                                <th>Actions</th>



                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>C_Name</th>
                                                <th>C_Phone</th>
                                                <th>Detail</th>
                                                <th>Price/Day</th>
                                                <th>Day</th>
                                                <th>Total/Rwf</th>
                                                <th>Rent Date</th>
                                                <th>Return Date</th>
                                                <th>Actions</th>


                                            </tr>
                                        </tfoot>
                                        <tbody>

                                            <?php

                                            $result = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction,ab_events_clients
                                        WHERE ab_events_rent_transaction.rent_transaction_clients_id =  ab_events_clients.client_id 
                                        AND ab_events_rent_transaction.rent_transaction_return_date <= '$today' AND ab_events_rent_transaction.rent_transaction_status = 'Not Returned' ");


                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $rents_id = $row['rent_transaction_code'];
                                                    $day = $row['rent_transaction_day'];
                                                    $price_day = $row['rent_transaction_total_per_day'];
                                                    $toatl_day_price = $day * $price_day;
                                                    $submenu =  mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
                    ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id  AND ab_events_material_rent_process.rent_process_rent_id = '$rents_id'");


                                            ?>

                                                    <tr>

                                                        <td><input type="checkbox" name="phone_number[]" value="<?php echo $row['client_phonenumber']; ?>" checked></td>
                                                        <td> <?php echo $row['client_fullname']; ?> </td>
                                                        <td> <?php echo $row['client_phonenumber']; ?> </td>
                                                        <td> <a class='badge badge-info badge-pill' data-toggle='modal' data-target='#view<?php echo $rents_id; ?>' style='color:white;'>View Details </a></td>
                                                        <td> <?php echo $row['rent_transaction_total_per_day']; ?></td>
                                                        <td> <?php echo $row['rent_transaction_day']; ?></td>
                                                        <td> <?php echo " <b>$toatl_day_price</b>"; ?></td>
                                                        <td> <?php echo $row['rent_transaction_rent_date']; ?></td>
                                                        <td> <?php echo $row['rent_transaction_return_date']; ?></td>
                                                        <td> <?php
                                                                if ($row['rent_transaction_status'] == 'Not Returned') {

                                                                    echo "  <a class='badge badge-danger badge-pill' style='color:white;'>Not Return </a>";
                                                                }
                                                                ?>
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







                                            <?php
                                                }
                                            } else {
                                                echo "<h5 style='color:red; '>No Data Found</h5>";
                                            }
                                            ?>

                                        </tbody>

                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- Button trigger modal -->



            <!-- END PAGE CONTENT
            <footer class="page-footer">
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
            // Check or uncheck all checkboxes when the header checkbox is clicked
            $('.check-all').on('click', function() {
                var isChecked = $(this).is(':checked');
                $('tbody').find(':checkbox').prop('checked', isChecked);
            });
        });
    </script>

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


</body>

</html>