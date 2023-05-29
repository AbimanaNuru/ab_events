<?php

include_once('config.php');
session_start();

if (isset($_POST['recover_password'])) {
    $phone = mysqli_real_escape_string($connection, $_POST['phone_number']);
    $result = mysqli_query($connection, "SELECT * FROM ab_users where ab_user_phonenumber='$phone'");
    $row = mysqli_fetch_assoc($result);
    @$fetch_user_phonenumber = $row['ab_user_phonenumber'];
    @$fetch_user_fullname = $row['ab_user_fullname'];
    @$fetch_user_email = $row['ab_user_email'];


    if ($phone == $fetch_user_phonenumber) {
        $length = 4;
        $randomString = substr(str_shuffle('0123456789'), 0, $length);
        $password = md5($randomString);
        $query = mysqli_query($connection, "UPDATE  ab_users SET 
         ab_user_password='$password' WHERE ab_user_phonenumber ='$fetch_user_phonenumber'");

        if ($query) {
            // echo "<script> alert('Success') </script>";
            $data = array(
                "sender" => 'AB EVENTS',
                "recipients" => "$fetch_user_phonenumber",
                "message" => "Hello $fetch_user_fullname! , Email:$fetch_user_email Password: $randomString",
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
            $success = "Password Reset Successfully";
            header("Refresh: 3; url= index.php");
        } else {
            $fail = "Something Wrong";
            header("Refresh: 2; url= forgot_password.php");
        }
    }

    else {
        $fail = "Phone Number Doesn't Exist";
        header("Refresh: 2; url= forgot_password.php");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Forgot Password | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="./assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="./assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="assets/css/main.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <link href="./assets/css/pages/auth-light.css" rel="stylesheet" />
</head>

<body style="background:url(../img/breadcrumb_new.png)">
    <div class="content">

        <div class="brand">
            <a class="link" href="" style="color: white;">AB Events Group</a>
        </div>
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

        <form id="login-form" action="" method="post">
            <h2 class="login-title">Recover Password</h2>
            <div class="form-group">
                <div class="input-group-icon right">
                    <label for=""> <b> Phone Number:</b></label>
                    <input class="form-control" type="text" minlength="10" maxlength="10" pattern="[0-9]+" required name="phone_number" placeholder="Provide Your Phone Number">

                </div>
            </div>

            <a href="index.php"> <i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Go Back</a>
            <br>
            <br>

            <div class="form-group">
                <button class="btn btn-dark btn-block" name="recover_password" type="submit">RECOVER PASSWORD</button>
            </div>



        </form>
    </div>
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">AB Events Group</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS -->
    <script src="./assets/vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/popper.js/dist/umd/popper.min.js" type="text/javascript"></script>
    <script src="./assets/vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS -->
    <script src="./assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->

</body>

</html>