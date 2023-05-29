<?php
include_once('config.php');
session_start();
include "sessionexpired.php";
$ab_user_id = $_SESSION['ab_user_id'];

$today = date("Y-m-d");
$sort = $_GET['report_by'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Report | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <!-- Add this line to call the DataTables CSS file -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <!-- Add this line to call the Buttons extension CSS file -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bulma.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bulma.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.3/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bulma.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bulma.min.css">



    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <style>
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
                <h1 class="page-title">AB Events Report</h1>

            </div>
            <span class='badge badge-info ab_report' id='day1'>1 DAY</span>
            <span class='badge badge-info ab_report' id='day3'>3 DAY</span>
            <span class='badge badge-info' id='day5'>5 DAY</span>
            <span class='badge badge-primary' id='week1'>1 WEEK</span>
            <span class='badge badge-primary' id='week2'>2 WEEK</span>
            <span class='badge badge-primary' id='week3'>3 WEEK</span>
            <span class='badge badge-success' id='month1'>1 MONTH</span>
            <span class='badge badge-success' id='month3'>3 MONTH</span>
            <span class='badge badge-success' id='month6'>6 MONTH</span>
            <span class='badge badge-danger' id='year1'>1 YEAR</span>
            <span class='badge badge-danger' id='year2'>2 YEAR</span>


            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="ibox bg-info color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong"> <?php
                                                                $query = "SELECT SUM(rent_process_total_price) AS total_price FROM ab_events_material_rent_process WHERE rent_process_rent_date >= DATE_SUB('$today', INTERVAL $sort)";
                                                                $result = mysqli_query($connection, $query);

                                                                if ($result && mysqli_num_rows($result) > 0) {
                                                                    $row = mysqli_fetch_assoc($result);
                                                                    $sum = number_format($row['total_price']);
                                                                    echo "<b style='color:black;'>$sum Rwf</b>";
                                                                } else {
                                                                    echo "<b style='color:black;'>0</b>"; // If no rows are found, display 0 as the sum
                                                                }
                                                                ?>


                                </h2>
                                <div class="m-b-5"> RENTS AMOUNTS</div><i class="fa fa-credit-card-alt widget-stat-icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="ibox bg-info color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong"> <?php
                                                                $query = "SELECT SUM(expense_cost) AS total_price FROM ab_events_expense WHERE expense_date >= DATE_SUB('$today', INTERVAL $sort)";
                                                                $result = mysqli_query($connection, $query);

                                                                if ($result && mysqli_num_rows($result) > 0) {
                                                                    $row = mysqli_fetch_assoc($result);
                                                                    $sum = number_format($row['total_price']);
                                                                    echo "<b style='color:black;'>$sum Rwf</b>";
                                                                } else {
                                                                    echo "<b style='color:black;'>0</b>"; // If no rows are found, display 0 as the sum
                                                                }
                                                                ?>


                                </h2>
                                <div class="m-b-5"> EXPENSES AMOUNTS</div><i class="fa fa-credit-card-alt widget-stat-icon"></i>
                            </div>
                        </div>
                    </div>

             

                    <div class="col-lg-4 col-md-6">
                        <div class="ibox bg-info color-white widget-stat">
                            <div class="ibox-body">
                                <h2 class="m-b-5 font-strong">

                                    <?php
                                    $query = "SELECT * FROM ab_events_clients";
                                    $result = mysqli_query($connection, $query);
                                    $clients = mysqli_num_rows($result);
                                    echo "<b style='color:black;'>$clients</b>";
                                    ?>
                                </h2>
                                <div class="m-b-5">ALL CLIENTS</div><i class="fa fa-users widget-stat-icon"></i>
                            </div>
                        </div>
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
                                <table id="example" class="table is-striped responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Rent ID</th>
                                            <th>C_Name</th>
                                            <th>C_Phone</th>
                                            <th>Detail</th>
                                            <th>Total</th>
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
                                            <th>Detail</th>
                                            <th>Total</th>
                                            <th>Rent Date</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>


                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $result = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_clients WHERE ab_events_clients.client_id  = ab_events_material_rent_process.rent_process_client_id 
AND ab_events_material_rent_process.rent_process_rent_date>= DATE_SUB('$today', INTERVAL $sort)  GROUP BY ab_events_material_rent_process.rent_process_rent_id");

                                        if (mysqli_num_rows($result) > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $rents_id = $row['rent_process_rent_id'];
                                                $submenu =  mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material WHERE
                    ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id  AND ab_events_material_rent_process.rent_process_rent_id = '$rents_id'");
                                                $sump = mysqli_query($connection, "SELECT  SUM(rent_process_total_price) as total_trans_money FROM ab_events_material_rent_process WHERE rent_process_rent_id = '$rents_id'");

                                        ?>

                                                <tr>

                                                    <td><b><?php echo $rents_id; ?> </b></td>
                                                    <td> <?php echo $row['client_fullname']; ?> </td>
                                                    <td> <?php echo $row['client_phonenumber']; ?> </td>

                                                    <td><?php
                                                        while ($sub = mysqli_fetch_assoc($submenu)) {
                                                            $m_name = $sub['ab_events_material_name'];
                                                            $m_quantity = $sub['rent_process_qty'];
                                                            $m_price = number_format($sub['rent_process_total_price']);

                                                            echo "<p><b>M:</b>$m_name<b> , Q:</b>$m_quantity<b> , T.P:</b>$m_price F</p>";
                                                        }
                                                        ?></td>

                                                    <td> <?php while ($Totalprice = mysqli_fetch_assoc($sump)) {
                                                                $total_p = number_format($Totalprice['total_trans_money']);
                                                                echo "<b>$total_p Rwf</b>";
                                                            } ?></td>
                                                    <td> <?php echo $row['rent_process_rent_date']; ?></td>
                                                    <td> <?php echo $row['rent_process_return_date']; ?></td>
                                                    <td>
                                                        <a href="invoice.php?invoice_code=<?php echo  $rents_id;  ?>"><span class="badge badge-info badge-circle m-r-5 m-b-5"><i class="fa fa-print" aria-hidden="true"></i>
                                                            </span></a>
                                                    </td>




                                                </tr>



                                        <?php
                                            }
                                        } else {
                                            echo "<h5 style='color:red; '>No Ordered Product</h5>";
                                        }
                                        ?>

                                    </tbody>


                                </table>



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
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bulma.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bulma.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bulma.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bulma.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#example').DataTable({
                lengthChange: false,
                buttons: ['excel', 'pdf', 'colvis']
            });

            // Insert at the top left of the table
            table.buttons().container()
                .appendTo($('div.column.is-half', table.table().container()).eq(0));
        });
    </script>

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
    </script>

</body>

</html>