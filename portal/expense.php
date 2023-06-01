<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";




if (isset($_POST['save_expense'])) {
    // Posted Values    
    $e_name = mysqli_real_escape_string($connection, $_POST['expense_name']);
    $e_class = mysqli_real_escape_string($connection, $_POST['expense_class']);
    $e_cost = mysqli_real_escape_string($connection, $_POST['expense_cost']);
    $d = mysqli_real_escape_string($connection, $_FILES["support_documents"]["name"]);


    // get the image extension
    $extension1 = substr($d, strlen($d) - 4, strlen($d));
    // allowed extensions
    $allowed_extensions = array(".JPG", ".PNG", ".JPEG", ".jpg", ".jpeg", ".png", ".gif",".pdf",".PDF");
    // Validation for allowed extensions
    if (!in_array($extension1, $allowed_extensions)) {
        $fail = "Invalid format. Only jpg / jpeg/ png /gif format allowed";
        header("Refresh: 1; url= expense.php");
    } else {
        //rename the image file
        $d = md5($d) . $extension1;
        // Code for move image into directory
        move_uploaded_file($_FILES["support_documents"]["tmp_name"], "expense_support_documents/" . $d);
        // Query for insertion data into database  
        $query = mysqli_query($connection, "insert into ab_events_expense(
            expense_added_by,expense_name,expense_class,expense_cost,expense_support_document,expense_date) 
VALUES('$ab_user_id','$e_name','$e_class','$e_cost','$d',NOW())");
        if ($query) {
            $success = "Expense Added Successfully";
            header("Refresh: 3; url= expense.php");
        } else {
            $success = "Something Wrong";
            header("Refresh: 3; url= expense.php");
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

    <title>Expense | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="assets/vendors/DataTables/datatables.min.css" rel="stylesheet" />
    <link href="assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">



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
                <h1 class="page-title">AB EVENTS Expense Managements</h1>

            </div>
            <div class="page-content fade-in-up">


                <!-- Start add expense -->
                <div class="col-lg-12">
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
                            <div class="ibox-title">Add Expense</div>
                            <div class="ibox-tools">
                                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>

                            </div>
                        </div>
                        <div class="ibox-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">

                                    <div class="col-sm-6 form-group">
                                        <label>Expense Name <b class="t_required">*</b></label>
                                        <input class="form-control" type="text" required name="expense_name" placeholder="Provide Expense Name">
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label>Expense Class <b class="t_required">*</b></label>
                                        <select class="form-control" required name="expense_class">

                                            <option value="">Select Expense Class</option>
                                            <option value="Fotoland">Fotoland</option>
                                            <option value="Elegance Decor">Elegance Decor</option>
                                            <option value="Magic Sound">Magic Sound</option>

                                        </select>
                                    </div>
                                    <div class="col-sm-6 form-group">
                                        <label>Expense Cost <b class="t_required">*</b></label>
                                        <input class="form-control" type="text" pattern="[0-9]+" required name="expense_cost" placeholder="Provide Expense Cost">
                                    </div>

                                    <div class="col-sm-6 form-group">
                                        <label>Support Document</label>
                                        <input class="form-control" type="file" name="support_documents">
                                    </div>
                                    <div class="col-lg-12">

                                        <div class="form-group">
                                            <button class="btn btn-dark btn-block" name="save_expense" type="submit">Save</button>
                                        </div>
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- Ends add expense -->

                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Expense List</div>
                    </div>
                    <div class="ibox-body">
                        <table id="example" class="table is-striped responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Support Documents</th>
                                    <th>P_Name</th>
                                    <th>P_Class</th>
                                    <th>P_Cost /Rwf</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Support Documents</th>
                                    <th>P_Name</th>
                                    <th>P_Class</th>
                                    <th>P_Cost /Rwf</th>
                                    <th>Date</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                // Select data from the database
                                $result = mysqli_query($connection, "SELECT * FROM   ab_events_expense,ab_users
                            WHERE  ab_events_expense.expense_added_by  = ab_users.ab_user_id");
                                // Fetch data as an associative array
                                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                // Loop through data using foreach
                                foreach ($data as $ab_material) {
                                    // $client_id = $clients['client_id'];
                                    $e_name = $ab_material['expense_name'];
                                    $e_class = $ab_material['expense_class'];
                                    $e_cost = $ab_material['expense_cost'];
                                    $e_documents = $ab_material['expense_support_document'];
                                    $e_date = $ab_material['expense_date'];

                                ?>
                                    <tr>
                                        <td><img src="expense_support_documents/<?php echo $e_documents ?>" alt="" style="width:70px;"></td>
                                        <td><b><?php echo $e_name; ?></b></td>
                                        <td><?php echo $e_class; ?></td>
                                        <td><b><?php echo $e_cost; ?></b></td>
                                        <td><?php echo $e_date; ?></td>


                                    </tr>
                                <?php } ?>

                            </tbody>
                        </table>
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
    <!-- PAGE LEVEL PLUGINS-->
    <script src="assets/vendors/DataTables/datatables.min.js" type="text/javascript"></script>
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