<?php
$activePage = basename($_SERVER['PHP_SELF'], ".php");
?>
<style>
    .side-menu li a.active {
        color: #fff;
        background-color: #6F0118;
    }
</style>


<header class="header">
    <div class="page-brand">
        <a class="link" href="index.html">
            <span class="brand">AB EVENTS GROUP
            </span>
            <span class="brand-mini">AB</span>
        </a>
    </div>
    <div class="flexbox flex-1">
        <!-- START TOP-LEFT TOOLBAR-->
        <ul class="nav navbar-toolbar">
            <li>
                <a class="nav-link sidebar-toggler js-sidebar-toggler"><i class="ti-menu"></i></a>
            </li>

            <li id="clock" style="color: #6F0118; font-weight: bold;"> </li>
        </ul>
        <!-- END TOP-LEFT TOOLBAR-->
        <!-- START TOP-RIGHT TOOLBAR-->
        <ul class="nav navbar-toolbar">

            <li class="dropdown dropdown-user">
                <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                    <img src="./assets/img//users.PNG" />
                    <span></span><?php echo $_SESSION['ab_user_fullname'];  ?><i class="fa fa-angle-down m-l-5"></i></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="settings.php"><i class="fa fa-user"></i>Settings</a>

                    <li class="dropdown-divider"></li>
                    <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
                </ul>
            </li>
        </ul>
        <!-- END TOP-RIGHT TOOLBAR-->
    </div>
</header>
<!-- END HEADER-->
<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <div class="admin-block d-flex">
            <div>
                <img src="./assets/img/users.PNG" width="45px" />
            </div>
            <div class="admin-info">
                <div class="font-strong"><?php echo $_SESSION['ab_user_fullname'];  ?></div><small><?php echo $_SESSION['ab_user_usertype'];  ?></small>
            </div>
        </div>
        <ul class="side-menu metismenu">
            <li>
                <a href="dashboard.php" class="<?= ($activePage == 'dashboard') ? 'active' : ''; ?>">
                    <i class="sidebar-item-icon fa fa-th-large"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>
            <li class="heading"> <b> AB EVENTS FEATURES</b></li>
            <li>


                <a href="clients.php" class="<?= ($activePage == 'clients') ? 'active' : ''; ?>">
                    <i class="sidebar-item-icon fa fa-users"></i>
                    <span class="nav-label">AB Events Clients</span>
                </a>
            </li>
            <li>
                <a href="materials.php" class="<?= ($activePage == 'materials') ? 'active' : ''; ?>">
                    <i class="sidebar-item-icon fa fa-product-hunt"></i>
                    <span class="nav-label">Materials</span>
                </a>
            </li>
            <li>
                <a href="rent_process.php" class="<?= ($activePage == 'rent_process') ? 'active' : ''; ?>">
                    <i class="sidebar-item-icon fa fa-tasks"></i>
                    <span class="nav-label">AB Events Rents</span>
                </a>
            </li>

            <li>
                <a href="expense.php" class="<?= ($activePage == 'expense') ? 'active' : ''; ?>">
                    <i class="sidebar-item-icon fa fa-users"></i>
                    <span class="nav-label">AB Events Expense</span>
                </a>
            </li>


            <?php
            // Financial
          
            if ($user_type == 'Administrator') {

            ?>
                <li>
                    <a href="ab_events_users.php" class="<?= ($activePage == 'ab_events_users') ? 'active' : ''; ?>">
                        <i class="sidebar-item-icon fa fa-users"></i>
                        <span class="nav-label">AB Events Users</span>
                    </a>
                </li>
            <?php
            } ?>
            <li>
                <a href="reporting.php?report_by=1 MONTH" class="<?= ($activePage == 'reporting') ? 'active' : ''; ?>">

                    <i class="sidebar-item-icon fa fa-bar-chart"></i>
                    <span class="nav-label">AB Events Report</span>
                </a>
            </li>

            <li class="heading"><b>ADDITIONAL FEATURES</b></li>


            <li>
                <a href="settings.php" class="<?= ($activePage == 'settings') ? 'active' : ''; ?>">
                    <i class="sidebar-item-icon fa fa-cog"></i>
                    <span class="nav-label">Settings</span>
                </a>
            </li>
            <li>
                <a href="logout.php"><i class="sidebar-item-icon fa fa-sign-in"></i>
                    <span class="nav-label">Logout</span>
                </a>
            </li>


        </ul>
    </div>
</nav>

<script>
    function updateClock() {
        var now = new Date();
        var year = now.getFullYear();
        var month = (now.getMonth() + 1).toString().padStart(2, "0");
        var day = now.getDate().toString().padStart(2, "0");
        var hours = now.getHours().toString().padStart(2, "0");
        var minutes = now.getMinutes().toString().padStart(2, "0");
        var seconds = now.getSeconds().toString().padStart(2, "0");

        var dateTimeString =
            year +
            "-" +
            month +
            "-" +
            day +
            " " +
            hours +
            ":" +
            minutes +
            ":" +
            seconds;

        document.getElementById("clock").textContent = dateTimeString;

        // Update the clock every second
        setTimeout(updateClock, 1000);
    }

    // Start the clock
    updateClock();
</script>