<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";


if (isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($connection, $_POST['category_name']);
    // Query for insertion data into database  
    $query = mysqli_query($connection, "insert into ab_material_category
  (ab_material_category_name,ab_material_date)
  values('$name',NOW())");

    if ($query) {
        echo ("<script>  alert('Category Added Successfully');location.href='materials.php';   </script>");
    } else {
        echo ("<script>  alert('Somthing Wrong');location.href='materials.php';   </script>");
    }
}



if (isset($_POST['save_material'])) {
    // Posted Values    
    $m_category = mysqli_real_escape_string($connection, $_POST['material_category']);
    $m_name = mysqli_real_escape_string($connection, $_POST['material_name']);
    $indi_price = mysqli_real_escape_string($connection, $_POST['individually_price']);
    $corp_price = mysqli_real_escape_string($connection, $_POST['corporate_price']);
    $available_qty = mysqli_real_escape_string($connection, $_POST['available_qty']);
    $d = mysqli_real_escape_string($connection, $_FILES["material_image"]["name"]);

    // get the image extension
    $extension1 = substr($d, strlen($d) - 4, strlen($d));
    // allowed extensions
    $allowed_extensions = array(".JPG", ".PNG", ".JPEG", ".jpg", ".jpeg", ".png", ".gif");
    // Validation for allowed extensions
    if (!in_array($extension1, $allowed_extensions)) {
        echo ("<script>  alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');location.href='materials.php';   </script>");
    }
    // elseif ($available_qty > $qty_material) {
    //     echo ("<script>  alert('Something Wrong on Material Quantity');location.href='materials.php';   </script>");
    // } 
    else {
        //rename the image file
        $d = md5($d) . $extension1;
        // Code for move image into directory
        move_uploaded_file($_FILES["material_image"]["tmp_name"], "material_image/" . $d);
        // Query for insertion data into database  
        $query = mysqli_query($connection, "insert into  ab_events_material(ab_events_material_added_by,
        ab_events_material_category,ab_events_material_name,ab_events_material_individual_price,ab_events_material_corporate_price,ab_events_material_available_qty,ab_events_material_image,ab_events_material_date) 
VALUES('$ab_user_id','$m_category','$m_name','$indi_price','$corp_price','$available_qty','$d',NOW())");
        if ($query) {

            echo ("<script>  alert('Material Added Successfully');location.href='materials.php';   </script>");
        } else {
            echo ("<script>  alert('Something Wrong');location.href='materials.php';   </script>");
        }
    }
}



if (isset($_POST['update_category'])) {

    $c_id = mysqli_real_escape_string($connection, $_POST['category_id']);
    $c_name = mysqli_real_escape_string($connection, $_POST['category_name']);

    // Query for insertion data into database  
    $query = mysqli_query($connection, "UPDATE ab_material_category SET ab_material_category_name = '$c_name'  WHERE ab_material_category_id = '$c_id'");

    if ($query) {
        // echo "<script> alert('Success') </script>";
        echo ("<script>  alert('Category Edited Successfully');location.href='materials.php';   </script>");
    } else {
        echo ("<script>  alert('Something Wrong');location.href='materials.php';   </script>");
    }
}




if (isset($_POST['update_material_info'])) {
    // Posted Values    
    $m_category = mysqli_real_escape_string($connection, $_POST['material_category']);
    $m_name = mysqli_real_escape_string($connection, $_POST['material_name']);
    $indi_price = mysqli_real_escape_string($connection, $_POST['individually_price']);
    $corp_price = mysqli_real_escape_string($connection, $_POST['corporate_price']);
    $available_qty = mysqli_real_escape_string($connection, $_POST['available_qty']);
    $material_id = mysqli_real_escape_string($connection, $_POST['material_id']);
    // if ($available_qty > $qty_material) {
    //     echo ("<script>  alert('Something Wrong In Material Quantity');location.href='materials.php';   </script>");
    // }

    // Query for insertion data into database  
    $query = mysqli_query($connection, "UPDATE  ab_events_material SET 
    ab_events_material_category = '$m_category',
    ab_events_material_name = '$m_name',
        ab_events_material_individual_price = '$indi_price',
        ab_events_material_corporate_price = '$corp_price',
        ab_events_material_available_qty = '$available_qty'
         WHERE ab_events_material_id ='$material_id'");
    if ($query) {

        echo ("<script>  alert('Material Edited Successfully');location.href='materials.php'; </script>");
    } else {
        echo ("<script>  alert('Something Wrong');location.href='materials.php';   </script>");
    }
}



if (isset($_POST['update_material_image'])) {
    // Posted Values    

    $d = mysqli_real_escape_string($connection, $_FILES["material_image"]["name"]);
    $material_id = mysqli_real_escape_string($connection, $_POST['material_id']);
    // get the image extension
    $extension1 = substr($d, strlen($d) - 4, strlen($d));
    // allowed extensions
    $allowed_extensions = array(".JPG", ".PNG", ".JPEG", ".jpg", ".jpeg", ".png", ".gif");
    // Validation for allowed extensions
    if (!in_array($extension1, $allowed_extensions)) {
        echo ("<script>  alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');location.href='materials.php';   </script>");
    } else {
        //rename the image file
        $d = md5($d) . $extension1;
        // Code for move image into directory
        move_uploaded_file($_FILES["material_image"]["tmp_name"], "material_image/" . $d);
        // Query for insertion data into database  
        $query = mysqli_query($connection, "UPDATE ab_events_material SET ab_events_material_image = '$d' WHERE  ab_events_material_id ='$material_id'");
        if ($query) {

            echo ("<script>  alert('Material Image Edited Successfully');location.href='materials.php';   </script>");
        } else {
            echo ("<script>  alert('Something Wrong');location.href='materials.php';   </script>");
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

    <title>Materials Control | AB Events | an exceptional experience</title>
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
                <h1 class="page-title">Materials Managements</h1>

            </div>
            <div class="page-content fade-in-up">


                <div class="row">

                    <div class="col-md-8">

                        <div class="col-lg-12">


                            <div class="row">
                                <!-- Start add material category -->
                                <div class="col-lg-12">
                                    <div class="ibox">
                                        <div class="ibox-head">
                                            <div class="ibox-title">Add Material Category</div>
                                            <div class="ibox-tools">
                                                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>

                                            </div>
                                        </div>
                                        <div class="ibox-body">
                                            <form action="" method="POST">
                                                <div class="row">
                                                    <div class="col-sm-12 form-group">
                                                        <label>Category Name <b class="t_required">*</b></label>
                                                        <input class="form-control" type="text" name="category_name" required placeholder="Provide Category Name">
                                                    </div>
                                                    <div class="col-lg-12">

                                                        <div class="form-group">
                                                            <button class="btn btn-dark btn-block" name="add_category" type="submit">Save</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end add material category -->


                                <!-- Start add new material -->
                                <div class="col-lg-12">
                                    <div class="ibox">
                                        <div class="ibox-head">
                                            <div class="ibox-title">Add New Material</div>
                                            <div class="ibox-tools">
                                                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>

                                            </div>
                                        </div>
                                        <div class="ibox-body">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-sm-6 form-group">
                                                        <div class="form-group">
                                                            <label class="form-control-label">Select Category <b class="t_required">*</b></label>
                                                            <select class="form-control select2_demo_1" name="material_category" required>
                                                                <option value="">Select Category</option>
                                                                <?php
                                                                $query = "SELECT * FROM `ab_material_category` ";
                                                                if ($result = mysqli_query($connection, $query)) {
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        echo "<option value=" . $row['ab_material_category_id'] . ">" . $row['ab_material_category_name'] . "</option>";
                                                                    }
                                                                }  ?>

                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Material Name <b class="t_required">*</b></label>
                                                        <input class="form-control" type="text" required name="material_name" placeholder="Provide Material Name">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Price For Individual Client <b class="t_required">*</b></label>
                                                        <input class="form-control" type="text" pattern="[0-9]+" required name="individually_price" placeholder="Provide Price For Individual Client">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Price For Corporate Client <b class="t_required">*</b></label>
                                                        <input class="form-control" type="text" pattern="[0-9]+" required name="corporate_price" placeholder="Provide Price For Corporate Client">
                                                    </div>

                                                    <div class="col-sm-6 form-group">
                                                        <label>Available Quantities <b class="t_required">*</b></label>
                                                        <input class="form-control" type="text" placeholder="Available Quantities" pattern="[0-9]+" required name="available_qty">
                                                    </div>
                                                    <div class="col-sm-6 form-group">
                                                        <label>Material Image <b class="t_required">*</b></label>
                                                        <input class="form-control" type="file" accept="image/*" required name="material_image">
                                                    </div>
                                                    <div class="col-lg-12">

                                                        <div class="form-group">
                                                            <button class="btn btn-dark btn-block" name="save_material" type="submit">Save</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- end add new material -->


                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="ibox">
                            <div class="ibox-head">
                                <div class="ibox-title">Material Category List</div>
                            </div>
                            <div class="ibox-body">
                                <div style="height: 500px;  overflow-y: scroll;  scrollbar-width: thin;scrollbar-color: green #95146B;">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Category Name</th>
                                                    <th>Actions</th>
                                                    <!-- <th>Actions</th> -->

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Select data from the database
                                                $result = mysqli_query($connection, "SELECT * FROM  ab_material_category ORDER BY ab_material_category_id DESC");
                                                // Fetch data as an associative array
                                                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                                                // Loop through data using foreach
                                                foreach ($data as $material_category) {
                                                    // $client_id = $clients['client_id'];
                                                    $cat_name = $material_category['ab_material_category_name'];
                                                    $cat_date = $material_category['ab_material_date'];
                                                    $cat_id = $material_category['ab_material_category_id'];
                                                ?>
                                                    <tr>

                                                        <td><?php echo $cat_name; ?></td>
                                                        <td>
                                                            <button class="btn btn-info btn-xs m-r-5" data-toggle="modal" data-target="#category<?php echo $cat_id; ?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></button>
                                                        </td>

                                                        <!-- <td>
                                                        <button class="btn btn-info btn-xs m-r-5" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></button>
                                                        <button class="btn btn-danger btn-xs" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash font-14"></i></button>
                                                    </td> -->
                                                    </tr>


                                                    <div class="modal fade" id="category<?php echo $cat_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="" method="POST">
                                                                        <input type="hidden" name="category_id" value="<?php echo $cat_id; ?>">
                                                                        <div class="form-group">
                                                                            <label>Category Name</label>
                                                                            <input class="form-control" type="text" name="category_name" value="<?php echo $cat_name; ?>" required placeholder="Provide Client Fullname">
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <button class="btn btn-dark btn-block" name="update_category" type="submit">Save Changes</button>
                                                                        </div>

                                                                    </form>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Material List</div>
                </div>
                <div class="ibox-body">
                    <table id="example" class="table is-striped responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>M_Name</th>
                                <th>M_Category</th>
                                <th>Available Qty</th>
                                <th>Who Rents</th>
                                <th>Individual Price /Rwf</th>
                                <th>Corporate Price /Rwf</th>
                                <th>M_Added.by</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Image</th>
                                <th>M_Name</th>
                                <th>M_Category</th>
                                <th>Available Qty</th>
                                <th>Individual Price /Rwf</th>
                                <th>Corporate Price /Rwf</th>
                                <th>M_Added.by</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                            // Select data from the database
                            $result = mysqli_query($connection, "SELECT * FROM  ab_material_category,ab_events_material,ab_users
                            WHERE ab_material_category.ab_material_category_id  = ab_events_material.ab_events_material_category AND
                            ab_events_material.ab_events_material_added_by =  ab_users.ab_user_id");
                            // Fetch data as an associative array
                            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
                            // Loop through data using foreach
                            foreach ($data as $ab_material) {
                                // $client_id = $clients['client_id'];
                                $cat_name = $ab_material['ab_material_category_name'];
                                $name = $ab_material['ab_events_material_name'];
                                $image = $ab_material['ab_events_material_image'];
                                $added_by = $ab_material['ab_user_fullname'];
                                $price_1 = $ab_material['ab_events_material_individual_price'];
                                $price_2 = $ab_material['ab_events_material_corporate_price'];
                                $material_quantity = $ab_material['ab_events_material_quantities'];
                                $available_quantity = $ab_material['ab_events_material_available_qty'];
                                $material_id = $ab_material['ab_events_material_id'];


                                $sales_material = mysqli_query($connection, "SELECT * from  ab_events_material_rent_process,ab_events_material,ab_events_rent_transaction,ab_events_clients where ab_events_material_rent_process.rent_process_material_id=ab_events_material.ab_events_material_id AND
                                ab_events_rent_transaction.rent_transaction_code = ab_events_material_rent_process.rent_process_rent_id 
                                AND ab_events_clients.client_id = ab_events_rent_transaction.rent_transaction_clients_id and ab_events_material_rent_process.rent_process_material_id = '$material_id'");
                            ?>
                                <tr>
                                    <td><img src="material_image/<?php echo $image ?>" alt="" style="width:70px;"></td>
                                    <td><b><?php echo $name; ?></b></td>
                                    <td><?php echo $cat_name; ?></td>
                                    <td> <span class="badge badge-danger badge-circle m-r-5 m-b-5"><?php echo $available_quantity; ?></span></a></td>
                                    <td>
                                        <button class="btn btn-success badge-circle  btn-xs m-r-5" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Track Who Rents Other Quantity" data-target="#rents<?php echo $material_id; ?>">Sales Details</button>
                                    </td>
                                    <td><?php echo $price_1; ?></td>
                                    <td><?php echo $price_2; ?></td>
                                    <td><?php echo $added_by; ?></td>

                                    <td>
                                        <button class="btn btn-info btn-xs m-r-5" data-toggle="modal" data-target="#material<?php echo $material_id; ?>" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil font-14"></i></button>
                                    </td>

                                </tr>

                                <div class="modal fade bd-example-modal-lg" id="material<?php echo $material_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Material Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST">
                                                    <h5> <b> Material Basic Information</b></h5>
                                                    <input type="hidden" name="material_id" value="<?php echo $material_id; ?>">
                                                    <div class="row">
                                                        <div class="col-sm-6 form-group">
                                                            <label>Select Category </label>
                                                            <select class="form-control " name="material_category" required>
                                                                <option value="<?php echo $material_id; ?>"><?php echo $cat_name; ?></option>
                                                                <?php
                                                                $query = "SELECT * FROM `ab_material_category` ";
                                                                if ($result = mysqli_query($connection, $query)) {
                                                                    while ($row = mysqli_fetch_array($result)) {
                                                                        $show_category = $row['ab_material_category_name'];
                                                                        $show_category_id = $row['ab_material_category_id'];
                                                                ?>
                                                                        <option value="<?php echo $show_category_id; ?>"><?php echo $show_category; ?></option>

                                                                <?php }
                                                                }  ?>

                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6 form-group">
                                                            <label>Material Name</label>
                                                            <input class="form-control" type="text" name="material_name" value="<?php echo $name; ?>" required placeholder="Provide Client Fullname">
                                                        </div>

                                                        <div class="col-sm-4 form-group">
                                                            <label>Price For Individual Client <b class="t_required">*</b></label>
                                                            <input class="form-control" type="text" pattern="[0-9]+" value="<?php echo $price_1; ?>" required name="individually_price" placeholder="Provide Price For Individual Client">
                                                        </div>
                                                        <div class="col-sm-4 form-group">
                                                            <label>Price For Corporate Client <b class="t_required">*</b></label>
                                                            <input class="form-control" type="text" pattern="[0-9]+" required value="<?php echo $price_2; ?>" name="corporate_price" placeholder="Provide Price For Corporate Client">
                                                        </div>

                                                        <div class="col-sm-4 form-group">
                                                            <label>Available Quantities <b class="t_required">*</b></label>
                                                            <input class="form-control" type="text" placeholder="Available Quantities" value="<?php echo $available_quantity; ?>" pattern="[0-9]+" required name="available_qty">
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-dark btn-block" name="update_material_info" type="submit">Save Changes</button>
                                                    </div>

                                                </form>

                                                <form action="" method="post" enctype="multipart/form-data">
                                                    <h5> <b> Material Image</b></h5>
                                                    <input type="hidden" name="material_id" accept="image/*"  value="<?php echo $material_id; ?>">

                                                    <div class="row">
                                                        <div class="col-sm-12 form-group">
                                                            <label>Material Image <b class="t_required">*</b></label>
                                                            <input class="form-control" type="file" required name="material_image">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-dark btn-block" name="update_material_image" type="submit">Save Changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Start View Who rents material -->

                                <div class="modal fade bd-example-modal-lg" id="rents<?php echo $material_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Material Sales Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <?php

                                                while ($pro_quantity = mysqli_fetch_assoc($sales_material)) {
                                                    $agent_name = $pro_quantity['ab_events_material_name'];
                                                    $return_status = $pro_quantity['rent_transaction_status'];


                                                ?>
                                                    <div style="padding:10px; background-color: #E3E7EB; border-radius: 10px;">

                                                        <h6><b>Client Name: </b><?php echo $pro_quantity['client_fullname']; ?></h6>
                                                        <h6><b>Material Name: </b><?php echo $agent_name; ?></h6>
                                                        <h6><b>Material Quantity: </b><?php echo $pro_quantity['rent_process_qty']; ?></h6>
                                                        <h6><b>Transaction Code: </b><?php echo $pro_quantity['rent_process_rent_id']; ?></h6>
                                                        <h6><b>Startus: </b><?php if ($return_status == 'Returned') {
                                                                                echo "<b style='color:green;'>$return_status</b>";
                                                                            } else {
                                                                                echo "<b style='color:red;'>$return_status</b>";
                                                                            }  ?></h6>
                                                        <h6><b>Rent Date: </b><?php echo $pro_quantity['rent_transaction_rent_date']; ?> , <b>Return Date: </b><?php echo $pro_quantity['rent_transaction_return_date']; ?>, <b>Day: </b><?php echo $pro_quantity['rent_transaction_day']; ?></h6>



                                                    </div>
                                                    <hr>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Ends View Who rents material -->
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
                responsive: true,

                lengthMenu: [
                    [20, 25, 50, -1],
                    [20, 25, 50, 'All'],
                ]
            });

        });
    </script>
</body>

</html>