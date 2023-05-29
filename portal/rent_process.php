<?php
include_once('config.php');
session_start();
include "sessionexpired.php";

$ab_user_id = $_SESSION['ab_user_id'];

if (isset($_POST['process_rents'])) {

    $client_phone_id = mysqli_real_escape_string($connection, $_POST['m_client']);
    $m_name = $_POST['m_name'];
    $m_quantity =  $_POST['m_quantity'];
    $m_price =  $_POST['m_price'];
    $m_total_price =  $_POST['m_total_price'];
    $rents_id = mysqli_real_escape_string($connection, $_POST['rents_id']);
    $rent_date = mysqli_real_escape_string($connection, $_POST['rent_date']);
    $return_date = mysqli_real_escape_string($connection, $_POST['return_date']);

    $proof_image = mysqli_real_escape_string($connection, $_FILES["support_documents"]["name"]);

    $extension1 = substr($proof_image, strlen($proof_image) - 4, strlen($proof_image));
    $proof_image = md5($proof_image) . $extension1;
    // Code for move image into directory
    move_uploaded_file($_FILES["support_documents"]["tmp_name"], "rent_support_documents/" . $proof_image);
    // Insert the data into the database
    // Initialize the success status variable
    $insertSuccess = true;

    // Loop through each product and insert it into the database
    if (is_array($m_name)) {
        $sum = 0;
        foreach ($m_name as $i => $m_names) {
            $query = mysqli_query($connection, "SELECT * FROM ab_events_material WHERE ab_events_material_id  = $m_names");
            $material = mysqli_fetch_assoc($query);
            $product_quantity = $material['ab_events_material_available_qty'];
            $material_name = $material['ab_events_material_name'];
            if ($m_quantity[$i] > $product_quantity) {
                $fail = 'Some Material Has No sufficient quantity in stock..';
                header("Refresh: 2; url=" . $_SERVER['PHP_SELF']);
            } else {
                $pquantity = $product_quantity - $m_quantity[$i];
                $total = $m_price[$i] * $m_quantity[$i];
                $sum += $total;
                // Query for insertion data into database  

                $sql = "INSERT INTO ab_events_material_rent_process (
                    rent_process_rent_id,
                    rent_process_client_id,
                    rent_process_added_by,
                    rent_process_material_id,
                    rent_process_qty,
                    rent_process_price,
                    rent_process_total_price,
                    rent_process_rent_date,
                    rent_process_return_date,
                    rent_process_support_documents,
                    rent_process_added_on,rent_process_status
                    ) 
                VALUES ('$rents_id',
                '$client_phone_id',
                '$ab_user_id',
                '$m_names',
                '{$m_quantity[$i]}', 
                '{$m_price[$i]}', 
                '$total',
                '$rent_date',
                '$return_date',
                '$proof_image',now(),'Not Returned')";
                $query = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty =  '" . $pquantity . "' WHERE ab_events_material_id  = '$m_names'");


                if ($connection->query($sql) !== TRUE) {
                    // If the insert fails, set the success status to false
                    $insertSuccess = false;
                    // Print the error message
                    // echo "Error: " . $sql . "<br>" . $connection->error;
                } else {

                    // $t__moneny = $total;

                    header("Refresh: 2; url= invoice.php?invoice_code=$rents_id");
                }
            }
        }
    }
}



if (isset($_POST['make_change'])) {
    // $m_id = $_POST['m_id'];
    // $m_quantity = $_POST['m_quantity'];
    $m_comment = $_POST['m_comment'];
    $code = $_POST['rent_id'];
    // Assuming you have a database connection established, you can use a foreach loop to update rent processes for each $m_id

    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    # here add select from ab_events_material_rent_process where rent_process_rent_id = $code
    $query = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process WHERE rent_process_rent_id = '$code'");
    $materials = mysqli_fetch_all($query, MYSQLI_ASSOC);

    foreach ($materials as $material) {
        $material_id = $material['rent_process_material_id'];
        #add rent_process_qty
        $material_quantity = $material['rent_process_qty'];
        #select from ab_events_material where ab_events_material_id = $material_id and update ab_events_material_quantities = $material_quantity
        $query = mysqli_query($connection, "SELECT * FROM ab_events_material WHERE ab_events_material_id = $material_id");
        $material = mysqli_fetch_assoc($query);
        $product_quantity = $material['ab_events_material_available_qty'];
        $pquantity = $product_quantity + $material_quantity;
        $query = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_available_qty = '$pquantity' WHERE ab_events_material_id = '$material_id'");
    }
    // Perform an UPDATE query based on $id
    $query = "UPDATE ab_events_material_rent_process SET rent_process_status='Returned'
         WHERE rent_process_rent_id = '$code'";

    // Example: Assuming you are using mysqli extension
    $result = mysqli_query($connection, $query);

    // Check if the query was successful
    if ($result) {
        // Handle the case when the update is successful
        $success = "Material Returned Successfully";
        header("Refresh: 2; url= rent_process.php");
    } else {
        $fail = "Something Wrong";
        header("Refresh: 2; url= rent_process.php");
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">

    <title>Material Rents Managemnets | AB Events | an exceptional experience</title>
    <link rel="icon" href="../img/ab_favicon.png">
    <!-- GLOBAL MAINLY STYLES-->
    <link href="assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="assets/vendors/select2/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap4.min.css">




    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
    <style>
        .required {
            color: red;
        }

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
                <h1 class="page-title">Material Rents Managements</h1>

            </div>
            <div class="page-content fade-in-up">
                <div class="row">

                    <div class="col-md-12">
                        <div class="ibox">

                            <?php
                            // Display the success or failure message
                            if (isset($_POST['process_rents'])) {
                                if (!empty($fail)) {

                                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                    <strong>$fail!</strong>
                                </div>";
                                } else if (!$insertSuccess) {

                                    echo ("<script LANGUAGE='JavaScript'>
            window.alert('Fail');
            window.location.href='rent_process.php';
            </script>");
                                } else {

                                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                    <strong>All Material Added Successfully!</strong>
                                    </div>";
                                    $data = array(
                                        "sender" => 'AB EVENTS',
                                        "recipients" => "0789424105",
                                        "message" => "Hello! Your R_ID: $rents_id, Cost: $sum Rwf, Rent Date:$rent_date, Return Date:$return_date, Book Us: 0788643162 | www.abeventsgroup.com",
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
                                }
                            }

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
                                <div class="ibox-title">Rent Material</div>
                                <div class="ibox-tools">
                                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>

                                </div>
                            </div>
                            <div class="ibox-body">
                                <form action="" method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-md-6  form-group">
                                            <label>Rents ID: <b class="required">*</b></label>
                                            <div class="form-group">
                                                <input class="form-control" minlength="20" maxlength="20" type="text" name="rents_id" style="background-color: #700018; color:white;" value="<?php
                                                                                                                                                                                                $length = 6;
                                                                                                                                                                                                $randomString = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
                                                                                                                                                                                                usleep(1000); // pause for 100 microseconds
                                                                                                                                                                                                $dateTime = date("YmdHis");
                                                                                                                                                                                                echo "R_" . $randomString . $dateTime;
                                                                                                                                                                                                ?>" required>



                                            </div>
                                        </div>

                                        <div class="col-sm-6 form-group">
                                            <label class="form-control-label">Select Client</label>
                                            <select class="form-control select2_demo_1" name="m_client" required>
                                                <option value="">Select Client:</option>
                                                <?php
                                                $query = "SELECT * FROM `ab_events_clients` ";
                                                if ($result = mysqli_query($connection, $query)) {
                                                    while ($row = mysqli_fetch_array($result)) {
                                                        echo "<option value=" . $row['client_id'] . ">" . $row['client_phonenumber'] . " [" . $row['client_fullname'] . "]</option>";
                                                    }
                                                }  ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div id="products-container">
                                        <div class="product-row">

                                            <div class="row">
                                                <div class="col-sm-3 form-group">
                                                    <label class="form-control-label">Select Material</label>
                                                    <select class="form-control select2_demo_1 get_product" name="m_name[]" required>
                                                        <option value="">Select Material:</option>
                                                        <?php
                                                        $query = "SELECT * FROM `ab_events_material` ";
                                                        if ($result = mysqli_query($connection, $query)) {
                                                            while ($row = mysqli_fetch_array($result)) { ?>
                                                                <option value="<?php echo $row['ab_events_material_id'] ?>"><?php echo $row['ab_events_material_name'] ?> [Qty:<?php echo $row['ab_events_material_available_qty'] ?>]</option>
                                                        <?php }
                                                        }  ?>

                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Quantity:</label>
                                                    <input type="text" class="form-control box2" name="m_quantity[]" pattern="[0-9]+" required onchange="code(2)" placeholder="Quantity">
                                                </div>

                                                <div class="col-sm-3 form-group">
                                                    <label>Price:</label>
                                                    <select class="form-control box1" name="m_price[]" required onchange="code(1)">
                                                        <option value="">Product Price</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Total Price:</label>
                                                    <input type="text" class="form-control cost" readonly pattern="[0-9]+" required name="m_total_price" onfocus="code()" placeholder="">

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <p id="add-product" class="btn btn-sm" style="background-color: #700018; color:white;"><i class="fa fa-plus" aria-hidden="true"></i> Add Material</p>
                                    <div class="row">
                                        <div class="col-sm-4 form-group">
                                            <label>Rent Date</label>
                                            <input class="form-control rent-date-input" type="date" name="rent_date" required>
                                        </div>
                                        <div class="col-sm-4 form-group">
                                            <label>Return Date</label>
                                            <input class="form-control rent-date-input" type="date" name="return_date" required>
                                        </div>




                                        <div class="col-sm-4 form-group">
                                            <label>Support Doocuments</label>
                                            <input class="form-control" type="file" name="support_documents" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-dark btn-block" name="process_rents" type="submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>




                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Rents</div>
                            </div>
                            <div class="ibox-body">
                                <table id="example" class="table is-striped responsive nowrap" style="width:100%">

                                    <thead>
                                        <tr>
                                            <th>Rent ID</th>
                                            <th>C_Name</th>
                                            <th>C_Phone</th>
                                            <th>Detail</th>
                                            <th>Total/Rwf</th>
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
                                            <th>Total/Rwf</th>
                                            <th>Rent Date</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>


                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php

                                        $result = mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process, ab_events_clients WHERE 
                                        ab_events_clients.client_id = ab_events_material_rent_process.rent_process_client_id GROUP BY ab_events_material_rent_process.rent_process_rent_id");


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
                                                        $sump = mysqli_query($connection, "SELECT  SUM(rent_process_total_price) as total_trans_money FROM ab_events_material_rent_process WHERE rent_process_rent_id = '$rents_id'");

                                                        while ($sub = mysqli_fetch_assoc($submenu)) {
                                                            $m_name = $sub['ab_events_material_name'];
                                                            $m_quantity = $sub['rent_process_qty'];
                                                            $m_price = number_format($sub['rent_process_total_price']);

                                                            echo "<p><b>M:</b>$m_name<b> , Q:</b>$m_quantity<b> , T.P:</b>$m_price F</p>";
                                                        }
                                                        ?></td>

                                                    <td> <?php while ($Totalprice = mysqli_fetch_assoc($sump)) {
                                                                $total_p = number_format($Totalprice['total_trans_money']);
                                                                echo "<b>$total_p</b>";
                                                            } ?></td>
                                                    <td> <?php echo $row['rent_process_rent_date']; ?></td>
                                                    <td> <?php echo $row['rent_process_return_date']; ?></td>
                                                    <td> <?php if ($row['rent_process_status'] == 'Not Returned') {
                                                                echo "  <a class='badge badge-danger badge-pill' data-toggle='modal' data-target='#$rents_id' style='color:white;'>Return </a>";
                                                            } else {
                                                                echo "  <span class='badge badge-success badge-pill'style='color:white;'>Returned </span>";
                                                            }
                                                            ?>


                                                        <a href="invoice.php?invoice_code=<?php echo  $rents_id;  ?>"><span class="badge badge-info badge-circle m-r-5 m-b-5"><i class="fa fa-print" aria-hidden="true"></i>
                                                            </span></a>
                                                    </td>
                                                </tr>


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
                                                                            <p style="padding: 5px; background-color: #700018;color:white; width:100;text-align: center;"><?php echo $trans_code; ?></p>
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
                                            echo "<h5 style='color:red; '>No Ordered Product</h5>";
                                        }
                                        ?>

                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>

            </div><!-- Button trigger modal -->



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
        var rentDateInputs = document.getElementsByClassName('rent-date-input');

        for (var i = 0; i < rentDateInputs.length; i++) {
            rentDateInputs[i].addEventListener('input', validateDates);
        }

        function validateDates() {
            var rentDateInput = document.getElementsByClassName('rent-date-input')[0];
            var returnDateInput = document.getElementsByClassName('rent-date-input')[1];

            var rentDate = new Date(rentDateInput.value);
            var returnDate = new Date(returnDateInput.value);

            if (returnDate < rentDate) {
                returnDateInput.setCustomValidity('Return date cannot be earlier than rent date');
            } else {
                returnDateInput.setCustomValidity('');
            }
        }

        $(document).ready(function() {
            $('#example').DataTable({

                lengthMenu: [
                    [20, 25, 50, -1],
                    [20, 25, 50, 'All'],
                ]
            });

        });
    </script>
    <script>
        // Get all elements with the specified class name
        var rentDateInputs = document.getElementsByClassName("rent-date-input");

        // Get the current date
        var today = new Date().toISOString().split('T')[0];

        // Set the minimum value for each input field to today's date
        for (var i = 0; i < rentDateInputs.length; i++) {
            rentDateInputs[i].setAttribute('min', today);
        }



        function code() {
            var box1 = document.getElementsByClassName('box1');
            var box2 = document.getElementsByClassName('box2');
            var costs = document.getElementsByClassName('cost');

            for (var i = 0; i < costs.length; i++) {
                var total = parseFloat(box1[i].value) * parseFloat(box2[i].value);
                costs[i].value = total;
            }
        }


        $(document).ready(function() {
            $(document).on('change', '.get_product', function() {

                var product_id = this.value;
                var $box1 = $(this).closest('.row').find('.box1');

                $.ajax({
                    url: "get_price.php",
                    type: "POST",
                    data: {
                        product_id: product_id,
                    },
                    cache: false,
                    success: function(result) {
                        $box1.html(result);
                    }
                });
            });
        });

        const productsContainer = document.getElementById('products-container');
        const addProductButton = document.getElementById('add-product');

        let productIndex = 1;

        addProductButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Create the HTML elements for the product fields
            const productFields = `
            <div class="product-row">
        <div class="row">
        <div class="col-sm-3 form-group">
                                                    <label class="form-control-label">Select Material</label>
                                                    <select class="form-control select2_demo_1 get_product" name="m_name[]" required>
                                                        <option value="">Select Material:</option>
                                                        <?php
                                                        $query = "SELECT * FROM `ab_events_material` ";
                                                        if ($result = mysqli_query($connection, $query)) {
                                                            while ($row = mysqli_fetch_array($result)) { ?>
                                                                <option value="<?php echo $row['ab_events_material_id'] ?>"><?php echo $row['ab_events_material_name'] ?> [Qty:<?php echo $row['ab_events_material_available_qty'] ?>]</option>
                                                        <?php }
                                                        }  ?>
                                                        </select>
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Quantity:</label>
                                                    <input type="text" class="form-control box2" name="m_quantity[]" pattern="[0-9]+" required onchange="code(2)" placeholder="Quantity">
                                                </div>
                                                <div class="col-sm-3 form-group">
                                                    <label>Price:</label>
                                                    <select class="form-control box1" name="m_price[]" required onchange="code(1)">
                                                        <option value="">Product Price</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2 form-group">
                                                    <label>Total Price:</label>
                                                    <input type="text" class="form-control cost" readonly pattern="[0-9]+" required name="m_total_price" onfocus="code()" placeholder="">

                                                </div> 

                                                <span class="remove-product"> <i class="fa fa-trash" style="color:red;"></i></span>
 
                                                </div>    
                                                </div>    
                                                
  `;

            // Append the product fields to the container
            productsContainer.insertAdjacentHTML('beforeend', productFields);

            // Increment the product index for the next product
            productIndex++;

            // Attach an event listener to the remove button for this product
            const removeButton = productsContainer.querySelector('.product-row:last-of-type .remove-product');
            removeButton.addEventListener('click', function(event) {
                event.preventDefault();

                // Remove the product field from the container
                const productField = removeButton.parentNode;
                productField.remove();
            });
        });
    </script>

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