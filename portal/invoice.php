<?php
include_once('config.php');
session_start();
$ab_user_id = $_SESSION['ab_user_id'];
include "sessionexpired.php";

$invoice_i_code = $_GET['invoice_code'];


?>

<!doctype html>
<html lang="en">

<head>
    <title>Invoice_<?php echo $invoice_i_code ?> </title>
    <link rel="icon" href="../img/ab_favicon.png"> <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link href="css/site.css" rel="stylesheet">

    <style>
        .address {
            font-size: .8rem;
        }

        /* 
This will only have the address line at a smaller font for the small breakpoints such as mobile devices to fit better on screen
*/

        @media only screen and (min-width: 768px) {
            .address {
                font-size: 1rem;
            }
        }

        @media print {
            .address {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container border rounded border-1 p-2"> <!--Surrounding rounded border for the invoice-->
        <div class="d-flex justify-content-end d-print-none"> <!--d-print-none removes button from print preview-->
            <!-- onclick="window.print()" allows for us to bring up print page to print invoice template -->
            <button type="button" class="btn-dark" onclick="window.print()">Print This Invoice</button>
        </div>
        <div class="row align-items-center">
            <div class="col-sm-12 d-flex justify-content-center">
                <img src="invoice_logo_v2.png" title="Ab Events group Logo" alt="Ab Events group" style="width:200px;" class="img-fluid">
            </div>
            <!-- <div class=" col-sm-5 d-flex justify-content-sm-end justify-content-start">
                   <strong>Client:</strong>&nbsp;05839
               </div> -->
        </div>
        <hr>
        <?php
        $result = mysqli_query($connection, "SELECT * FROM ab_events_rent_transaction,ab_events_clients
WHERE ab_events_rent_transaction.rent_transaction_clients_id =  ab_events_clients.client_id AND  ab_events_rent_transaction.rent_transaction_code = '$invoice_i_code' ");

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rents_id = $row['rent_transaction_code'];
                $rent_day = $row['rent_transaction_day'];

                $submenu =  mysqli_query($connection, "SELECT * FROM ab_events_material_rent_process,ab_events_material,ab_material_category WHERE
ab_events_material_rent_process.rent_process_material_id = ab_events_material.ab_events_material_id 
AND  ab_material_category.ab_material_category_id  = ab_events_material. ab_events_material_category AND ab_events_material_rent_process.rent_process_rent_id = '$invoice_i_code'");
                $sump = mysqli_query($connection, "SELECT  SUM(rent_transaction_total_per_day) as total_trans_money FROM ab_events_rent_transaction WHERE rent_transaction_code = '$invoice_i_code'");

        ?>

                <div class="row row-cols-sm-2 row-cols-1">
                    <div class="col-6 d-flex justify-content-start">
                        <div> <!-- div wrapper around header and address to make content stack properly-->
                            <h6><b>Rent Date:</b> <?php echo $row['rent_transaction_rent_date']  ?></h6>
                            <h6><b>Return Date:</b> <?php echo $row['rent_transaction_return_date']  ?></h6>
                            <h6><b>Day:</b> <?php echo $rent_day; ?></h6>
                        </div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div>
                            <strong>Invoice Code:</strong><br>
                            <h6><?php echo $row['rent_transaction_code'] ?></h6>
                        </div>
                    </div>
                </div>
                <hr>


                <div class="row row-cols-sm-2 row-cols-1">
                    <div class="col-6 d-flex justify-content-start">
                        <div> <!-- div wrapper around header and address to make content stack properly-->
                            <strong>Invoice To:</strong><br>
                            <address class="address"><?php echo $row['client_fullname'] ?><br>
                                <?php echo $row['client_phonenumber'] ?><br>
                                <?php echo $row['client_email'] ?>
                            </address>
                        </div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <div>
                            <strong>Rented By:</strong><br>
                            <address class="address"><b>AB EVENTS GROUP</b><br>
                            +250 785 752 797<br>+250 783 236 256<br>
                            info@abeventsgroup.com
                            </address>
                        </div>
                    </div>
                </div>
                <div class="table-responsive-sm">
                    <table class="table table-striped table-bordered">
                        <!--<caption class="border p-2"><strong>NOTE: </strong>
                    This is a computer generated receipt and does not require a physical signature.
                    </caption>-->
                        <thead>
                            <tr>
                                <th>Material</th>
                                <th>Category</th>

                                <th>Quantity</th>
                                <th>Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($sub = mysqli_fetch_assoc($submenu)) {
                                $m_name = $sub['ab_events_material_name'];
                                $m_category = $sub['ab_material_category_name'];
                                $m_quantity = $sub['rent_process_qty'];
                                $m_price = number_format($sub['rent_process_price']);
                                $t_m_price = number_format($sub['rent_process_total_price']);

                            ?>


                                <tr>
                                    <td><b><?php echo $m_name; ?></b></td>
                                    <td><?php echo $m_category; ?></td>
                                    <td><?php echo $m_quantity; ?></td>
                                    <td><?php echo $m_price; ?> Rwf</td>

                                    <td class="text-end"><?php echo $t_m_price; ?> Rwf</td>
                                </tr>

                            <?php
                            }
                            ?>

                        </tbody>
                        <tfoot>

                            <tr>
                                <td colspan="4"> <b> Price Per Day:</b> </td>
                                <td class="text-end"><?php while ($price_per_day = mysqli_fetch_assoc($sump)) {
                                                            $total_p = $price_per_day['total_trans_money'];
                                                            echo "<b>$total_p Rwf</b>";
                                                            $invoice_total =  number_format($total_p * $rent_day);
                                                        } ?></td>
                            </tr>
                            <tr>
                                <td colspan="4"> <b> Total:</b></td>
                                <td class="text-end"><?php echo "<b>$invoice_total Rwf</b>";  ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>



        <?php
            }
        } else {
            header('Location: rent_process.php');
            exit;
        }
        ?>





        <footer>
            <div class="row">
                <div class="col-12">
                    <h5><strong>NOTE: </strong>&nbsp;</h5>
                    <h6>- Materials are rented per day, delays in returning materials are subject to additional charges.</h6>
                    <h6>- Ibikoresho bikodeshwa ku munsi, iyo utinze gutirura, habarwa iminsi ibikoresgo bimaze nabyo bikishurwa</h6>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>
</body>

</html>