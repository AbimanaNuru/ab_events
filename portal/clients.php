<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";



if (isset($_POST['save_clients'])) {

    $name = mysqli_real_escape_string($connection, $_POST['c_fullname']);
    $phone = mysqli_real_escape_string($connection, $_POST['c_phonenumber']);
    $email = mysqli_real_escape_string($connection, $_POST['c_email']);
    $gender = mysqli_real_escape_string($connection, $_POST['c_gender']);
    $address = mysqli_real_escape_string($connection, $_POST['c_address']);

    $slquery2 = "SELECT client_email,client_phonenumber  FROM  ab_events_clients WHERE client_email = '$email' OR client_phonenumber = '$phone'";
    $selectresult2 = mysqli_query($connection, $slquery2);

    if (mysqli_num_rows($selectresult2) > 0) {
        $fail = 'This email or phone number is exist in system.';
    } else {
        // Query for insertion data into database  
        $query = mysqli_query($connection, "insert into ab_events_clients
  (ab_events_added_by,client_fullname,client_phonenumber,client_email,client_gender,client_address,client_joining_date)
  values('$ab_user_id','$name','$phone','$email','$gender','$address',NOW())");

        if ($query) {
            // echo "<script> alert('Success') </script>";
            $success = "Clients Added Successfully";
            header("Refresh: 3; url= clients.php");
        } else {
            $fail = "Something Wrong";
            header("Refresh: 2; url= clients.php");
        }
    }
}


if (isset($_POST['update_clients'])) {

    $name = mysqli_real_escape_string($connection, $_POST['c_fullname']);
    $phone = mysqli_real_escape_string($connection, $_POST['c_phonenumber']);
    $email = mysqli_real_escape_string($connection, $_POST['c_email']);
    $gender = mysqli_real_escape_string($connection, $_POST['c_gender']);
    $address = mysqli_real_escape_string($connection, $_POST['c_address']);
    $c_idd = mysqli_real_escape_string($connection, $_POST['cli_id']);

    $slquery2 = "SELECT client_email,client_phonenumber  FROM  ab_events_clients WHERE (client_email = '$email' OR client_phonenumber = '$phone') AND client_id != '$c_idd'";
    $selectresult2 = mysqli_query($connection, $slquery2);

    if (mysqli_num_rows($selectresult2) > 0) {
        $fail = 'This email or phone number is exist in system.';
    } else {
        // Query for insertion data into database  
        $query = mysqli_query($connection, "UPDATE ab_events_clients SET client_fullname = '$name',client_phonenumber='$phone',client_email='$email',
        client_gender='$gender',client_address='$address' WHERE client_id = '$c_idd'");

        if ($query) {
            // echo "<script> alert('Success') </script>";
            $success = "Clients Updated Successfully";
            header("Refresh: 3; url= clients.php");
        } else {
            $fail = "Something Wrong";
            header("Refresh: 2; url= clients.php");
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

    <title>Clients | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="assets/vendors/DataTables/datatables.min.css" rel="stylesheet" />

    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <style>
        .t_required {
            color: red;
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
                <h1 class="page-title">Clients Managements</h1>

            </div>
            <div class="page-content fade-in-up">
                <div class="row">

                    <div class="col-md-12">
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
                            <div class="ibox-head">
                                <div class="ibox-title">Add New Clients</div>
                                <div class="ibox-tools">
                                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>

                                </div>
                            </div>
                            <div class="ibox-body">
                                <form method="POST" action="">
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Full Name <b class="t_required">*</b></label>
                                            <input class="form-control" type="text" name="c_fullname" required placeholder="Provide Client Fullname">
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Phone Number <b class="t_required">*</b></label>
                                            <input class="form-control" type="text" minlength="10" maxlength="10" pattern="[0-9]+" name="c_phonenumber"  required placeholder="Provide Client Phone Number">
                                        </div>

                                        <div class="col-sm-4 form-group">
                                            <label>Email</label>
                                            <input class="form-control" type="text" name="c_email" placeholder="Provide Client Email address">
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Gender <b class="t_required">*</b></label>
                                            <select name="c_gender" id="" required class="form-control">
                                                <option value="">Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Address <b class="t_required">*</b></label>
                                            <input class="form-control" type="text" name="c_address" required placeholder="Ex: Kigali-Kimisagara-KK249">
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-dark btn-block" name="save_clients" type="submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>


            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Clients List</div>
                </div>
                <div class="ibox-body">
                    <table class="table table-striped table-bordered table-hover" id="example-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Added.by</th>

                                <th>Joining Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Full Name</th>
                                <th>Phone Number</th>
                                <th>Email</th>
                                <th>Gender</th>
                                <th>Address</th>
                                <th>Added.by</th>
                                <th>Joining Date</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>

                            <?php
                            // Select data from the database
                            $result = mysqli_query($connection, "SELECT * FROM  ab_events_clients,ab_users WHERE ab_events_clients.ab_events_added_by = ab_users.ab_user_id");
                            // Fetch data as an associative array
                            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            // Loop through data using foreach
                            foreach ($data as $clients) {
                                $client_id = $clients['client_id'];
                                $name = $clients['client_fullname'];
                                $phone = $clients['client_phonenumber'];
                                $email = $clients['client_email'];
                                $gender = $clients['client_gender'];
                                $address = $clients['client_address'];
                                $ab_user = $clients['ab_user_fullname'];


                            ?>
                                <tr>
                                    <td><b> <?php echo $name;  ?> </b></td>
                                    <td><?php echo $phone;  ?> </td>
                                    <td><?php echo $email; ?> </td>
                                    <td><?php echo $gender;  ?> </td>
                                    <td><?php echo $address  ?> </td>
                                    <td><b><?php echo $ab_user  ?></b></td>
                                    <td><?php echo $clients['client_joining_date']  ?> </td>
                                    <td>
                                        <button class="btn btn-info btn-xs m-r-5" data-toggle="modal" data-target="#clients<?php echo $client_id; ?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></button>
                                    </td>

                                </tr>

                                <div class="modal fade" id="clients<?php echo $client_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Clients</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST">
                                                    <input type="hidden" name="cli_id" value="<?php echo $client_id; ?>">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input class="form-control" type="text" name="c_fullname" value="<?php echo $name; ?>" required placeholder="Provide Client Fullname">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Phone Number</label>
                                                        <input class="form-control" type="text"name="c_phonenumber" value="<?php echo $phone; ?>" minlength="10"  maxlength="10" pattern="[0-9]+" required placeholder="Provide Client Phone Number">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input class="form-control" type="text"  name="c_email" value="<?php echo $email; ?>" placeholder="Provide Client Email address">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <select name="c_gender" id="" required class="form-control">
                                                            <option value="<?php echo $gender; ?>"><?php echo $gender; ?></option>
                                                            <option value="Male">Male</option>
                                                            <option value="Female">Female</option>
                                                            <option value="Other">Other</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address</label>
                                                        <input class="form-control" type="text" name="c_address" value="<?php echo $address; ?>" required placeholder="Ex: Kigali-Kimisagara-KK249">
                                                    </div>

                                                    <div class="form-group">
                                                        <button class="btn btn-dark btn-block" name="update_clients" type="submit">Save Changes</button>
                                                    </div>

                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>

                        </tbody>
                    </table>
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
    <!-- PAGE LEVEL PLUGINS-->
    <script src="assets/vendors/DataTables/datatables.min.js" type="text/javascript"></script>

    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL SCRIPTS-->

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