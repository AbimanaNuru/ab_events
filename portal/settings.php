<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";

if (isset($_POST['change_account_info'])) {
	//start edit the account basic information
	$a = $_POST['fullname'];
	$b = $_POST['phone'];
	$c = $_POST['email'];

	$sql = "UPDATE ab_users SET  ab_user_fullname='$a',ab_user_phonenumber='$b',ab_user_email='$c' WHERE ab_user_id ='$ab_user_id'";
	if ($connection->query($sql) === TRUE) {
		$success = "Done successful.";
		header("Refresh: 3; url= settings.php");
	} else {
		$fail = "Fail";
		header("Refresh: 3; url= settings.php");
	}
}

if (isset($_POST['Update_Security'])) {
	$currentPassword = mysqli_real_escape_string($connection, md5($_POST['cpassword']));
	$newPassword = mysqli_real_escape_string($connection, md5($_POST['npassword']));
	$conformPassword = mysqli_real_escape_string($connection, md5($_POST['confirmpassword']));

	$sql = "SELECT * FROM ab_users WHERE ab_user_id = '$ab_user_id'";
	$query = $connection->query($sql);
	$result = $query->fetch_assoc();

	if ($currentPassword == $result['ab_user_password']) {
		if ($newPassword == $conformPassword) {

			$updateSql = "UPDATE ab_users SET ab_user_password = '$newPassword' WHERE ab_user_id= '$ab_user_id'";
			if ($connection->query($updateSql) === TRUE) {
				$success = " Password Updated Successfully";
				header("Refresh: 3; url= settings.php");
			} else {
				$fail = "Error while updating the password";
			}
		} else {

			$fail = "Password Does Not Match ";
		}
	} else {

		$fail = 'Current Password Is Incorrect';
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>Settings | AB Events | an exceptional experience</title>
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
                <h1 class="page-title">User Settings</h1>

            </div>
            <div class="page-content fade-in-up">
                <div class="row">
                    <div class="col-lg-3 col-md-4">

                        <?php

                        $query = "SELECT * from ab_users  WHERE ab_user_id ='$ab_user_id' ";
                        $rs_result = mysqli_query($connection, $query);
                        while ($ab_users = mysqli_fetch_array($rs_result)) {
                            $ab_usersid = $ab_users['ab_user_id'];

                        ?>
                            <div class="ibox">
                                <div class="ibox-body text-center">
                                    <div class="m-t-20">
                                        <img class="img-circle" src="./assets/img/users.PNG" style="width: 100px;" />
                                    </div>
                                    <h5 class="font-strong m-b-10 m-t-10"><?php echo $ab_users['ab_user_fullname'] ?></h5>
                                    <h6 class="font-strong m-b-10 m-t-10"><?php echo $ab_users['ab_user_email'] ?></h6>
                                    <h6 class="font-strong m-b-10 m-t-10"><?php echo $ab_users['ab_user_phonenumber'] ?></h6>
                                    <div class="m-b-20 text-muted"><?php echo $ab_users['ab_user_usertype'] ?></div>
                                
                                    <div>
                                        <button class="btn btn-success btn-rounded m-b-5 btn-block"> Verified User</button>
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="col-lg-9 col-md-8">



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

                                <div class="row">
                                    <div class="col-lg-6" style="padding: 20px;">
                                        <div class="ibox">
                                            <div class="ibox-title text-left"><b>Change Account Info</b></div>

                                        </div>
                                        <form action="" method="POST">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label>Full Name:</label>
                                                    <input class="form-control" type="text" name="fullname" value="<?php echo $ab_users['ab_user_fullname']  ?>" required placeholder="Provide Full Name">
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <label>Phone Number:</label>
                                                    <input class="form-control" type="text" name="phone"  minlength="10" maxlength="10" pattern="[0-9]+"  value="<?php echo $ab_users['ab_user_phonenumber']  ?>"  required placeholder="Provide Phone Number">
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <label>Email:</label>
                                                    <input class="form-control" type="text" name="email" value="<?php echo $ab_users['ab_user_email']  ?>"  required placeholder="Provide Email">
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <button class="btn btn-dark btn-block" name="change_account_info" type="submit">Save Changes</button>
                                                </div>
                                            </div>



                                        </form>

                                    </div>
                                    <div class="col-lg-6" style="padding: 20px;">
                                        <div class="ibox">
                                            <div class="ibox-title text-left"><b>Change Account Security</b></div>

                                        </div>
                                        <form action="" method="POST">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label>Current Password</label>
                                                    <input class="form-control" type="password" name="cpassword" required placeholder="Provide Current Password">
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <label>New Password</label>
                                                    <input class="form-control" type="password" name="npassword" required placeholder="Provide New Password">
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <label>Confirm Password</label>
                                                    <input class="form-control" type="password" name="confirmpassword" required placeholder="Provide Confirm Password">
                                                </div>

                                                <div class="col-sm-12 form-group">
                                                    <button class="btn btn-dark btn-block" type="submit" name="Update_Security">Save Changes</button>
                                                </div>
                                            </div>



                                        </form>

                                    </div>

                                </div>



                            </div>


                        </div>
                    <?php

                        }
                    ?>
                 
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
    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="./assets/js/scripts/profile-demo.js" type="text/javascript"></script>
</body>

</html>