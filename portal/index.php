<?php

include_once('config.php');
session_start();

if (isset($_POST['login'])) {
    $user = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, md5($_POST['password']));

    if ($user) {
        if ($password) {

            // make sure login info correct
            $query = mysqli_query($connection, "SELECT * FROM ab_users WHERE ab_user_email='$user'");
            $numrows = mysqli_num_rows($query);
            if ($numrows == 1) {
                $row = mysqli_fetch_assoc($query);
                $userid = $row['ab_user_id'];
                $username = $row['ab_user_email'];
                $dbpass = $row['ab_user_password'];
                $dbactive = $row['ab_user_status'];

                if ($password == $dbpass) {
                    if ($dbactive == 'Active') {
                        $userid = $row['ab_user_id'];
                        $name = $row['ab_user_fullname'];
                        $user_type = $row['ab_user_usertype'];
                        @$_SESSION['ab_user_email'] = $username;
                        $_SESSION['ab_user_id'] = $userid;
                        $_SESSION['ab_user_fullname'] = $name;
                        $_SESSION['ab_user_usertype'] = $user_type;
                        $_SESSION['ab_user_password'] = $password;
                        $_SESSION['start'] = time(); // Taking now logged in time.
                        // Ending a session in 30 minutes from the starting time.
                        @$_SESSION['expire'] = $_SESSION['start'] + (30 * 60);

                        $success = "Welcome To AB Events Group.";
                        header("Refresh: 3; url= dashboard.php");
                    } else {
                        $fail = "Your Account Is Deactive.";
                        header("Refresh: 1; url= index.php");
                    }
                } else {
                    $fail = "Entered Incorrect Password. ";
                }
            } else {
                $fail = "Email Was Not Found In System.";
            }
        } else {
            $fail = "You must enter your password.";
        }
    } else {
        $fail = "You must enter your Email. ";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Login | AB Events | an exceptional experience</title>
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

<body style="background:url(../img/breadcrumb_new.PNG)">
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
            <h2 class="login-title">Log in</h2>
            <div class="form-group">
                <div class="input-group-icon right">
                    <input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off">

                </div>
            </div>
            <div class="form-group">
                <div class="input-group-icon right">
                    <input class="form-control" type="password" name="password" id="myInput2" placeholder="Password">
                </div>
            </div>
            <div class="form-group d-flex justify-content-between">
                <label class="ui-checkbox ui-checkbox-info">
                    <input type="checkbox" id="customCheck1" onclick="myFunction2()">
                    <span class="input-span"></span>Show Password</label>
                    <a href="forgot_password.php">Forgot password?</a>

            </div>
            <div class="form-group">
                <button class="btn btn-dark btn-block" name="login" type="submit">LOG IN</button>
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
    <script type="text/javascript">
        $(function() {
            $('#login-form').validate({
                errorClass: "help-block",
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true
                    }
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group").removeClass("has-error")
                },
            });
        });


        function myFunction2() {
            var x = document.getElementById("myInput2");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>

</html>