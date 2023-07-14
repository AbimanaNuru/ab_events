<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";

$today = date("Y-m-d");
$sort = $_GET['report_by'];

if (!isset($sort) || empty($sort)) {
    header("location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Report [ <?php echo $sort; ?> ] | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <!-- Add this line to call the DataTables CSS file -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Add this line to call the Buttons extension CSS file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">


    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <style>
        .table td,
        .table th {
            padding: 1px;
            font-size: 11px;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #6E0017;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: inherit;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
            color: white;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: black;
            color: white;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: black;
            color: white;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 2px solid #6F0118;
            border-top: none;
        }

        .ab_event_color {
            background-color: #6F0118;
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
                <h1 class="page-title">AB Events Report By Period <b>[ <?php echo $sort; ?> ]</b></h1>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <form action="">
                        <div class="form-group">
                            <label>Selct Period</label>
                            <select class="form-control" required name="report_by" required>

                                <option value="<?php echo $sort; ?>"><?php echo $sort; ?></option>
                                <option value="1 DAY">1 DAY</option>
                                <option value="3 DAY">3 DAY</option>
                                <option value="5 DAY">5 DAY</option>
                                <option value="1 WEEK">1 WEEK</option>
                                <option value="2 WEEK">2 WEEK</option>
                                <option value="3 WEEK">3 WEEK</option>
                                <option value="1 MONTH">1 MONTH</option>
                                <option value="3 MONTH">3 MONTH</option>
                                <option value="6 MONTH">6 MONTH</option>
                                <option value="1 YEAR">1 YEAR</option>
                                <option value="2 YEAR">2 YEAR</option>


                            </select>
                        </div>

                        <div>
                            <button class="btn btn-block btn-dark">Apply</button>
                        </div>
                    </form>

                </div>
                <div class="col-lg-6">
                    <form action="date_reporting" method="GET">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Start Date:</label>
                                <input type="date" class="form-control" name="start_date" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>End Date:</label>
                                <input type="date" class="form-control" name="end_date" required>
                            </div>

                            <div class="col-md-12">
                                <button class="btn btn-block btn-dark" type="submit"> Apply</button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>



            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="ibox ab_event_color color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong">
                                    <?php
                                    $query = "SELECT SUM(rent_transaction_total_price) AS total_price FROM ab_events_rent_transaction WHERE rent_transaction_rent_date >= DATE_SUB('$today', INTERVAL $sort)";
                                    $result = mysqli_query($connection, $query);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $row = mysqli_fetch_assoc($result);
                                        $sum = number_format($row['total_price']);
                                        echo "<b>$sum Rwf</b>";
                                    } else {
                                        echo "<b >0</b>"; // If no rows are found, display 0 as the sum
                                    }
                                    ?>
                                </h2>
                                <div class="m-b-5"> RENTS AMOUNTS</div><i class="fa fa-credit-card-alt widget-stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="expense">
                            <div class="ibox ab_event_color color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong"> <?php
                                                                    $query = "SELECT SUM(expense_cost) AS total_price FROM ab_events_expense WHERE ab_events_expense_done_data >= DATE_SUB('$today', INTERVAL $sort)";
                                                                    $result = mysqli_query($connection, $query);
                                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                                        $row = mysqli_fetch_assoc($result);
                                                                        $sum = number_format($row['total_price']);
                                                                        echo "<b >$sum Rwf</b>";
                                                                    } else {
                                                                        echo "<b >0</b>"; // If no rows are found, display 0 as the sum
                                                                    }
                                                                    ?>

                                    </h2>
                                    <div class="m-b-5"> EXPENSES AMOUNTS</div><i class="fa fa-credit-card-alt widget-stat-icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>



                    <div class="col-lg-4 col-md-6">
                        <a href="clients">
                            <div class="ibox ab_event_color color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">

                                        <?php
                                        $query = "SELECT * FROM ab_events_clients WHERE client_joining_date >= DATE_SUB('$today', INTERVAL $sort)";
                                        $result = mysqli_query($connection, $query);
                                        $clients = mysqli_num_rows($result);
                                        echo "<b >$clients</b>";
                                        ?>
                                    </h2>
                                    <div class="m-b-5">ALL CLIENTS</div><i class="fa fa-users widget-stat-icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>


                </div>
                <div class="row">

                    <div class="col-md-12">
                        <div class="ibox">

                            <div class="ibox-head">
                                <!-- <div class="ibox-title">Add New Clients</div> -->
                                <div class="ibox-tools">
                                </div>
                            </div>
                            <div class="ibox-body">

                                <div class="tab">
                                    <center>
                                        <button class="tablinks" onclick="openCity(event, 'rent')" id="defaultOpen">AB Events Sales</button>
                                        <button class="tablinks" onclick="openCity(event, 'expense')">AB Events Expense</button>
                                        <button class="tablinks" onclick="openCity(event, 'client')">AB Events Clients</button>
                                    </center>
                                </div>
                                <!-- start ab events report  report -->
                                <div id="rent" class="tabcontent">
                                    <table id="example" class="table is-striped responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Rent ID</th>
                                                <th>C_Name</th>
                                                <th>C_Phone</th>
                                                <th>C_Gender</th>
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
                                                <th>C_Name</th>
                                                <th>C_Phone</th>
                                                <th>C_Gender</th>
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
                                            <tr style="background-color: #6E0017; color:#F1F1F1; font-weight: bold;">
                                                <td>1Total</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <?php
                                                    $query = "SELECT SUM(rent_transaction_total_price) AS total_price FROM ab_events_rent_transaction WHERE rent_transaction_rent_date >= DATE_SUB('$today', INTERVAL $sort)";
                                                    $result = mysqli_query($connection, $query);
                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $sum = number_format($row['total_price']);
                                                        echo "<b>$sum Rwf</b>";
                                                    } else {
                                                        echo "<b >0</b>"; // If no rows are found, display 0 as the sum
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $query = "SELECT SUM(rent_transaction_credit_money) AS credited_money FROM ab_events_rent_transaction WHERE rent_transaction_rent_date >= DATE_SUB('$today', INTERVAL $sort)";
                                                    $result = mysqli_query($connection, $query);
                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $credited_money = number_format($row['credited_money']);
                                                        echo "<b>$credited_money Rwf</b>";
                                                    } else {
                                                        echo "<b >0</b>"; // If no rows are found, display 0 as the sum
                                                    }
                                                    ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                            </tr>

                                            <?php
                                            $result = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction,ab_events_clients
WHERE ab_events_rent_transaction.rent_transaction_clients_id =  ab_events_clients.client_id AND ab_events_rent_transaction.rent_transaction_rent_date	>= DATE_SUB('$today', INTERVAL $sort) ");
                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $rents_id = $row['rent_transaction_code'];
                                                    $day = $row['rent_transaction_day'];
                                                    $price_day = $row['rent_transaction_total_per_day'];
                                                    $toatl_day_price = $row['rent_transaction_total_price'];
                                                    $submenu =  mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id  AND ab_events_material_rent_process.rent_process_rent_id = '$rents_id'");


                                            ?>

                                                    <tr>

                                                        <td><b><?php echo $rents_id; ?> </b></td>
                                                        <td> <?php echo $row['client_fullname']; ?> </td>
                                                        <td> <?php echo $row['client_phonenumber']; ?> </td>
                                                        <td> <?php echo $row['client_gender']; ?> </td>
                                                        <td> <a class='badge badge-info badge-pill' data-toggle='modal' data-target='#view<?php echo $rents_id; ?>' style='color:white;'>View Details </a></td>
                                                        <td> <?php echo $row['rent_transaction_total_per_day']; ?></td>
                                                        <td> <?php echo $row['rent_transaction_day']; ?></td>
                                                        <td> <?php echo " <b>$toatl_day_price</b>"; ?></td>
                                                        <td> <b><?php echo $row['rent_transaction_credit_money']; ?></b></td>

                                                        <td> <?php echo $row['rent_transaction_rent_date']; ?></td>
                                                        <td> <?php echo $row['rent_transaction_return_date']; ?></td>
                                                        <td> <?php if ($row['rent_transaction_status'] == 'Not Returned') {
                                                                    echo "  <a class='badge badge-danger badge-pill' data-toggle='modal' data-target='#$rents_id' style='color:white;'>Return </a>";
                                                                } else {
                                                                    echo "  <span class='badge badge-success badge-pill'style='color:white;'>Returned </span>";
                                                                }
                                                                ?>

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

                                                    <!-- start of model -->
                                                    <div class="modal fade" id="<?php echo  $rents_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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

                                                                            <div class="col-md-12">
                                                                                <label for="">Add Comments</label>

                                                                                <textarea name="m_comment" id="" class="form-control" placeholder="Provide Comments" cols="3" rows="2" required></textarea>
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
                                                echo "<center><h3 style='color:red; '> <b> No Data Found</b></h3></center>";
                                            }
                                            ?>

                                        </tbody>

                                    </table>
                                </div>
                                <!-- Ends ab events report  report -->

                                <div id="expense" class="tabcontent">
                                    <table id="example2" class="table is-striped responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Expense Name</th>
                                                <th>Expense Class</th>
                                                <th>Expense Cost</th>
                                                <th>Date</th>
                                                <th>Added By</th>
                                                <th>Added On</th>


                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Expense Name</th>
                                                <th>Expense Class</th>
                                                <th>Expense Cost</th>
                                                <th>Date</th>
                                                <th>Added By</th>
                                                <th>Added On</th>

                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr style="background-color: #6E0017; color:#F1F1F1; font-weight: bold;">
                                                <td>1Total</td>
                                                <td></td>

                                                <td>


                                                    <?php
                                                    $query = "SELECT SUM(expense_cost) AS total_price FROM ab_events_expense WHERE ab_events_expense_done_data >= DATE_SUB('$today', INTERVAL $sort)";
                                                    $result = mysqli_query($connection, $query);
                                                    if ($result && mysqli_num_rows($result) > 0) {
                                                        $row = mysqli_fetch_assoc($result);
                                                        $sum = number_format($row['total_price']);
                                                        echo "<b >$sum Rwf</b>";
                                                    } else {
                                                        echo "<b >0</b>"; // If no rows are found, display 0 as the sum
                                                    }
                                                    ?>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>


                                            </tr>

                                            <?php
                                            $result = mysqli_query($connection, "SELECT * FROM ab_events_expense,ab_users WHERE ab_users.ab_user_id = ab_events_expense.expense_added_by
                                             AND ab_events_expense.ab_events_expense_done_data>= DATE_SUB('$today', INTERVAL $sort) ");

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <tr>
                                                        <td> <?php echo $row['expense_name']; ?> </td>
                                                        <td> <?php echo $row['expense_class']; ?> </td>
                                                        <td> <?php echo $row['expense_cost']; ?> </td>
                                                        <td> <?php echo $row['ab_events_expense_done_data']; ?></td>
                                                        <td> <b><?php echo $row['ab_user_fullname']; ?></b></td>
                                                        <td> <?php echo $row['expense_date']; ?></td>

                                                    </tr>


                                            <?php
                                                }
                                            } else {
                                                echo "<center><h3 style='color:red; '> <b> No Data Found</b></h3></center>";
                                            }
                                            ?>

                                        </tbody>

                                    </table>
                                </div>

                                <div id="client" class="tabcontent">
                                    <table id="example3" class="table is-striped responsive nowrap" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Phonenumber</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Added By</th>


                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Full Name</th>
                                                <th>Phonenumber</th>
                                                <th>Email</th>
                                                <th>Gender</th>
                                                <th>Address</th>
                                                <th>Added By</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>

                                            <?php
                                            $result = mysqli_query($connection, "SELECT * FROM ab_events_clients,ab_users WHERE ab_users.ab_user_id = ab_events_clients.ab_events_added_by
                                             AND ab_events_clients.client_joining_date>= DATE_SUB('$today', INTERVAL $sort) ");

                                            if (mysqli_num_rows($result) > 0) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <tr>
                                                        <td> <?php echo $row['client_fullname']; ?> </td>
                                                        <td> <?php echo $row['client_phonenumber']; ?> </td>
                                                        <td> <?php echo $row['client_gender']; ?> </td>
                                                        <td> <?php echo $row['client_address']; ?></td>
                                                        <td> <?php echo $row['client_joining_date']; ?></td>
                                                        <td> <b><?php echo $row['ab_user_fullname']; ?></b></td>

                                                    </tr>


                                            <?php
                                                }
                                            } else {
                                                echo "<center><h3 style='color:red; '> <b> No Data Found</b></h3></center>";
                                            }
                                            ?>

                                        </tbody>

                                    </table>
                                </div>









                            </div>
                        </div>
                    </div>

                </div>

            </div>



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

    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            var table1 = $('#example').DataTable({
                lengthChange: false,
                responsive: true,
                buttons: [{
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                }]
            });

            table1.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');

            var table2 = $('#example2').DataTable({
                lengthChange: false,
                responsive: true,
                buttons: [{
                    extend: 'pdfHtml5',
                    orientation: 'portrait',
                    pageSize: 'LEGAL'
                }]
            });

            table2.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');


            var table3 = $('#example3').DataTable({
                lengthChange: false,
                responsive: true,
                buttons: [{
                    extend: 'pdfHtml5',
                    orientation: 'portrait',
                    pageSize: 'LEGAL'
                }]
            });

            table3.buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');
        });
    </script>
    <!-- 
    <script>
        var day1 = document.getElementById('day1');
        var day3 = document.getElementById('day3');
        var day5 = document.getElementById('day5');
        var week1 = document.getElementById('week1');
        var week2 = document.getElementById('week2');
        var week3 = document.getElementById('week3');
        var month1 = document.getElementById('month1');
        var month3 = document.getElementById('month3');
        var month6 = document.getElementById('month6');
        var year1 = document.getElementById('year1');
        var year2 = document.getElementById('year2');
        // Add a click event listener to the element
        day1.addEventListener('click', function() {
            var url = 'reporting.php?report_by=1 DAY';
            window.location.href = url;
        });
        day3.addEventListener('click', function() {
            var url = 'reporting.php?report_by=3 DAY';
            window.location.href = url;
        });
        day5.addEventListener('click', function() {
            var url = 'reporting.php?report_by=5 DAY';
            window.location.href = url;
        });
        week1.addEventListener('click', function() {
            var url = 'reporting.php?report_by=1 WEEK';
            window.location.href = url;
        });
        week2.addEventListener('click', function() {
            var url = 'reporting.php?report_by=2 WEEK';
            window.location.href = url;
        });
        week3.addEventListener('click', function() {
            var url = 'reporting.php?report_by=3 WEEK';
            window.location.href = url;
        });
        month1.addEventListener('click', function() {
            var url = 'reporting.php?report_by=1+MONTH';
            window.location.href = url;
        });
        month3.addEventListener('click', function() {
            var url = 'reporting.php?report_by=3 MONTH';
            window.location.href = url;
        });
        month6.addEventListener('click', function() {
            var url = 'reporting.php?report_by=6 MONTH';
            window.location.href = url;
        });
        year1.addEventListener('click', function() {
            var url = 'reporting.php?report_by=1 YEAR';
            window.location.href = url;
        });
        year2.addEventListener('click', function() {
            var url = 'reporting.php?report_by=2 YEAR';
            window.location.href = url;
        });
    </script> -->

    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

</body>

</html>