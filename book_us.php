<?php
include "portal/config.php";
if (isset($_POST['book_us'])) {

    $name = mysqli_real_escape_string($connection, $_POST['fname']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $service = mysqli_real_escape_string($connection, $_POST['service']);
    $comment = mysqli_real_escape_string($connection, $_POST['comment']);

    // Query for insertion data into database  
    $query = mysqli_query($connection, "insert into ab_events_booking
  (ab_events_booking_fullname,ab_events_booking_phone,ab_events_booking_email,ab_events_booking_service,ab_events_booking_comment,ab_events_booking_date)
  values('$name','$phone','$email','$service','$comment',NOW())");
    if ($query) {
        // echo "<script> alert('Success') </script>";
        $success = "Thank you for book us";
        header("Refresh: 3; url= book_us.php");
    } else {
        $fail = "Something Wrong";
        header("Refresh: 2; url= book_us.php");
    }
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Staging Template">
    <meta name="keywords" content="Staging, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Book Us | AB Events | an exceptional experience</title>
    <link rel="icon" href="img/ab_favicon.png">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">

    <style>
        .book_div {
            padding: 20px;
        }

        .form-control {
            border: 2px solid #6E0118;
            /* Additional custom CSS styles */
        }
    </style>
</head>

<body>
    <!-- Page Preloder -->
    <!-- <div id="preloder">
        <div class="loader"></div>
    </div> -->

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__logo">
            <a href="#"><img src="img/hero/logo.png" style="width: 172; height: 43px" alt="" /></a>
        </div>
        <div id="mobile-menu-wrap"></div>
        <div class="offcanvas__widget">
            <a href="index.php" class="primary-btn normal-btn"> <b style="color:white;">Book Us</b></a>

        </div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    <header class="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="#"><img src="img/hero/logo.png" style="width: 172; height: 43px" alt="" /></a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li>
                                <a href="#">Our Services</a>
                                <ul class="dropdown">
                                    <li>
                                        <a href="elegance-decor.php">Elegance Decor</a>
                                    </li>
                                    <li><a href="fotoland.php">Fotoland</a></li>
                                    <li><a href="magic-sound.php">Magic Sound</a></li>
                                </ul>
                            </li>
                            <li><a href="about_us.php">About Us</a></li>
                            <li><a href="contact_us.php">Contact Us</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__widget">
                        <a href="book_us.php" class="primary-btn normal-btn"> <b style="color:white;">Book Us</b></a>

                    </div>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>
    <!-- Header Section End -->

    <!-- Breadcrumb Section Begin -->
    <div class="breadcrumb-option spad set-bg" data-setbg="img/breadcrumb_new.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Book Us</h2>
                        <div class="breadcrumb__links">
                            <a href="index.php">Home</a>
                            <span>Book Us</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section End -->


    <section class="contact spad">
        <div class="container">

            <div class="row">

                <div class="col-lg-2">
                </div>
                <div class="col-lg-8">
                    <div class="section-title text-center">
                        <h2>AB EVENTS GROUP</h2>
                    </div>
                    <div class="">
                        <?php
                        if (isset($success) & !empty($success)) {
                            echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
		<strong><i class='fa fa-spinner fa-spin' aria-hidden='true' ></i> $success!</strong>
	</div>";
                        }
                        if (isset($fail) & !empty($fail)) {
                            echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
		<strong><i class='fa fa-spinner fa-spin' aria-hidden='true' ></i>  $fail!</strong>
	</div>";
                        }
                        ?>
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 book_div">
                                    <label for="">Full Name:</label>
                                    <input type="text" class="form-control" name="fname" required placeholder="Provide Your Full Name">
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 book_div">
                                    <label for="">Phone Number:</label>
                                    <input type="text" class="form-control" name="phone" required placeholder="Provide Your Phone Number">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 book_div">
                                    <label for="">Email Address:</label>
                                    <input type="text" class="form-control" name="email" required placeholder="Provide your Email Address">
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 book_div">
                                    <label for="">Service:</label>
                                    <select name="service" id="" required class="form-control">
                                        <option value="">Select Service</option>
                                        <option value="Wedding Planning">Wedding Planning</option>
                                        <option value="Wedding Decoration">Wedding Decoration</option>
                                        <option value="Event Rentals">Event Rentals</option>
                                        <option value="Transport Services">Transport Services</option>
                                        <option value="Destination Management Services">Destination Management Services</option>
                                    </select>
                                </div>


                                <div class="col-lg-12 book_div">
                                    <textarea placeholder="Provide Additional Information" name="comment" rows="7" required class="form-control"></textarea>
                                </div>
                                <div class="col-lg-12 book_div">
                                    <button type="submit" name="book_us" class="site-btn btn-block">Book Us</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer Section Begin -->
    <?php include "footer.php" ?>
    <!-- Footer Section End -->

    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>