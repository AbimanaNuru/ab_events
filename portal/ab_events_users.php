<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";

if (isset($_POST['invite_user'])) {

    $name = mysqli_real_escape_string($connection, $_POST['full_name']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $email = mysqli_real_escape_string($connection, $_POST['email_address']);
    $type = mysqli_real_escape_string($connection, $_POST['user_type']);

    $length = 4;
    $randomString= substr(str_shuffle('0123456789'), 0, $length);

    $password = md5($randomString);


    $slquery2 = "SELECT ab_user_phonenumber,ab_user_email  FROM  ab_users WHERE ab_user_email = '$email' OR ab_user_phonenumber = '$phone'";
    $selectresult2 = mysqli_query($connection, $slquery2);

    if (mysqli_num_rows($selectresult2) > 0) {
        $fail = 'This email or phone number is exist in system.';
    } else {
        // Query for insertion data into database  
        $query = mysqli_query($connection, "insert into ab_users
  (ab_user_fullname,ab_user_phonenumber,ab_user_email,ab_user_usertype,ab_user_password,ab_user_status,ab_user_addedon)
  values('$name','$phone','$email','$type','$password','Active',NOW())");

        if ($query) {
            // echo "<script> alert('Success') </script>";
            $data = array(
                "sender" => 'AB EVENTS',
                "recipients" => "$phone",
                "message" => "Hello $name! Email: $email, Password: $randomString | www.abevents.com",
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
            $success = "User Added Successfully";
            header("Refresh: 3; url= ab_events_users.php");
        } else {
            $fail = "Something Wrong";
            header("Refresh: 2; url= ab_events_users.php");
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
    <title>AB Events users | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
</head>

<body class="fixed-navbar has-animation fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php include "portal_slider_header.php" ?>

        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title">Users Managements</h1>

            </div>
            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="ibox">
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
                            <div class="ibox-body">
                                <div class="ibox">
                                    <div class="ibox-title text-left"><b>Add New User</b></div>

                                </div>
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="col-sm-6 form-group">
                                            <label>Full Name</label>
                                            <input class="form-control" type="text" name="full_name" required placeholder="Provide Full Name">
                                        </div>

                                        <div class="col-sm-6 form-group">
                                            <label>Phone Number</label>
                                            <input class="form-control" type="text" required name="phone_number" minlength="10" maxlength="10" pattern="[0-9]+" placeholder="Provide Phone Number">
                                        </div>

                                        <div class="col-sm-6 form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="email" name="email_address" required placeholder="Provide Email">
                                        </div>
                                        <div class="col-sm-6 form-group">
                                            <label>User Type</label>
                                            <select name="user_type" id="" class="form-control">
                                                <option value="">Select User Type</option>
                                                <option value="Administrator">Administrator</option>
                                                <option value="Financial">Financial</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-12 form-group">
                                            <button class="btn btn-dark btn-block" type="submit" name="invite_user">Invite User</button>
                                        </div>
                                    </div>



                                </form>

                                <h4 class="text-info m-b-20 m-t-20">AB EVENTS GROUP Users</h4>
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Full Name</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Joining Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        // Select data from the database
                                        $result = mysqli_query($connection, "SELECT * FROM  ab_users ");
                                        // Fetch data as an associative array
                                        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                        // Loop through data using foreach
                                        foreach ($data as $ab_events_user) {
                                            $name = $ab_events_user['ab_user_fullname'];
                                            $phone = $ab_events_user['ab_user_phonenumber'];
                                            $email = $ab_events_user['ab_user_email'];
                                            $type = $ab_events_user['ab_user_usertype'];
                                            $status = $ab_events_user['ab_user_status'];
                                            $added_on = $ab_events_user['ab_user_addedon'];


                                        ?>


                                            <tr>
                                                <td><?php echo $name;  ?></td>
                                                <td><?php echo $phone;  ?></td>
                                                <td><?php echo $email;  ?></td>
                                                <td><?php
                                                    if ($type == 'Administrator') {
                                                        echo "<span class='badge badge-primary'>$type</span>";
                                                    } else {
                                                        echo "<span class='badge badge-info'>$type</span>";
                                                    } ?></td>
                                                <td><?php echo $added_on;  ?></td>

                                                <td>
                                                    <?php

                                                    if (($status) == 'Deactive') {
                                                    ?>
                                                        <a href="change_user_status.php?status=<?php echo $ab_events_user['ab_user_id']; ?>" onclick="return confirm('Do You Want To Activate'); ">
                                                            <button class="btn btn-danger btn-sm"> Deactive</button></a>
                                                    <?php
                                                    }
                                                    if (($status) == 'Active') {
                                                    ?>
                                                        <a href="change_user_status.php?status=<?php echo $ab_events_user['ab_user_id']; ?>" onclick="return confirm(' Do You Want To Deactivate ');">
                                                            <button class="btn btn-success btn-sm">Active</button></a>
                                                    <?php
                                                    }
                                                    ?>

                                                </td>


                                            </tr>

                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <style>
                    .profile-social a {
                        font-size: 16px;
                        margin: 0 10px;
                        color: #999;
                    }

                    .profile-social a:hover {
                        color: #485b6f;
                    }

                    .profile-stat-count {
                        font-size: 22px
                    }
                </style>

            </div>
            <!-- END PAGE CONTENT-->
            <footer class="page-footer">
                <div class="font-13">2023 © <b>AB EVENTS GROUP</b> - All rights reserved.</div>
                <div class="to-top"><i class="fa fa-angle-double-up"></i></div>
            </footer>
        </div>
    </div>

    <!-- create form html for submiting username and password   -->
    
  
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
    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="./assets/js/scripts/profile-demo.js" type="text/javascript"></script>
</body>

</html>