<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Dashboard | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="./assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="./assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <!-- For chart -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/Chart.min.js"></script>
    <style>
        .ab_event_color{
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
            <div class="page-content fade-in-up">
                <div class="row">

                    <div class="col-lg-3 col-md-6">
                        <a href="ab_events_users.php">
                            <div class="ibox ab_event_color color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">
                                        <?php
                                        $query = "SELECT * FROM ab_users";
                                        $result = mysqli_query($connection, $query);
                                        $ab_user = mysqli_num_rows($result);
                                        echo "<b>$ab_user</b>";
                                        ?>
                                    </h2>
                                    <div class="m-b-5">AB EVENTS USERS</div><i class="fa fa-users widget-stat-icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="clients.php">

                            <div class="ibox ab_event_color color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">

                                        <?php
                                        $query = "SELECT * FROM ab_events_clients";
                                        $result = mysqli_query($connection, $query);
                                        $clients = mysqli_num_rows($result);
                                        echo "<b>$clients</b>";
                                        ?>
                                    </h2>
                                    <div class="m-b-5">ALL CLIENTS</div><i class="fa fa-users widget-stat-icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="materials.php">

                            <div class="ibox ab_event_color color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong">
                                        <?php
                                        $query = "SELECT * FROM ab_events_material";
                                        $result = mysqli_query($connection, $query);
                                        $material = mysqli_num_rows($result);
                                        echo "<b>$material</b>";
                                        ?>
                                    </h2>
                                    <div class="m-b-5">MATERIAL CATEGORIES</div><i class="fa fa-product-hunt widget-stat-icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <a href="materials.php">

                            <div class="ibox ab_event_color color-white widget-stat">
                                <div class="ibox-body">
                                    <h2 class="m-b-5 font-strong"> <?php
                                                                    $query = "SELECT * FROM ab_material_category";
                                                                    $result = mysqli_query($connection, $query);
                                                                    $category = mysqli_num_rows($result);
                                                                    echo "<b>$category</b>";
                                                                    ?>
                                    </h2>
                                    <div class="m-b-5"> ALL MATERIAL</div><i class="fa fa-product-hunt widget-stat-icon"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
                // Financial

                if ($user_type == 'Administrator') {

                ?>
                    <div id="chart-container" style="background-color: #FFFFFF; padding: 5px; border-radius: 20px;">

                        <canvas id="ab_chart"></canvas>
                    </div>
                <?php
                } ?>
            </div>
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
    <script src="./assets/vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/popper.js/dist/umd/popper.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/metisMenu/dist/metisMenu.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="./assets/vendors/chart.js/dist/Chart.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap-us-aea-en.js" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="./assets/js/scripts/dashboard_1_demo.js" type="text/javascript"></script>
    <script>
        // Start Of product monitoring

        $(document).ready(function() {
            $("#ab_chart").ready();
            ab_chart_v1()
        });

        function ab_chart_v1() {
            {
                $.get("chart_data.php", {
                        // function: "ab_chart"
                    },
                    function(l_date) {
                        var m_date = [];
                        var m_price = [];

                        for (var l in l_date) {
                            m_date.push(l_date[l]. rent_transaction_rent_date);
                            m_price.push(l_date[l].price);
                        }
                        var chartdata_loan = {
                            labels: m_date,
                            datasets: [{
                                label: 'Rents Amounts In Rwf ',
                                backgroundColor: '#6F0118',
                                borderColor: 'black',
                                hoverBackgroundColor: 'black',
                                hoverBorderColor: '#EB8314',
                                data: m_price,
                                // fill:false
                            }]
                        };

                        var graphTarget_loan = $("#ab_chart");

                        var barGraph_loan = new Chart(graphTarget_loan, {
                            type: 'bar',
                            data: chartdata_loan
                        });
                    }
                );
            }
        }

        // end Of product monitoring
    </script>
</body>

</html>