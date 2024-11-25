<!DOCTYPE html>
<html lang="zxx">

<head>
  <meta charset="UTF-8" />
  <meta name="description" content="Staging Template" />
  <meta name="keywords" content="Staging, unica, creative, html" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <title>Home | AB Events | an exceptional experience</title>
  <link rel="icon" href="img/ab_favicon.png">


  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Aldrich&display=swap" rel="stylesheet" />

  <!-- Css Styles -->
  <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/elegant-icons.css" type="text/css" />
  <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css" />
  <link rel="stylesheet" href="css/slicknav.min.css" type="text/css" />
  <link rel="stylesheet" href="css/slick.css" type="text/css" />
  <link rel="stylesheet" href="css/style.css" type="text/css" />


  <!-- Bootstrap JavaScript and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    .project-details {
      background-color: #f8f9fa;
    }

    .card {
      transition: transform 0.2s ease-in-out;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .text-primary {
      color: #8b4513 !important;
      /* Wedding-themed brown color */
    }

    .callto {
      padding: 120px 0;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      color: #fff;
    }

    .overlay {
      background: rgba(0, 0, 0, 0.7);
      z-index: 1;
    }

    .container {
      z-index: 2;
    }

    .subtitle {
      color: #d4a762;
      font-weight: 600;
      letter-spacing: 2px;
      position: relative;
      padding-bottom: 15px;
    }

    .subtitle:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 2px;
      background-color: #d4a762;
    }

    .feature-card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(5px);
      border-radius: 10px;
      transition: transform 0.3s ease;
      height: 100%;
    }

    .feature-card:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.15);
    }

    .icon-wrapper {
      color: #d4a762;
    }

    .h5 {
      font-weight: 600;
    }



    .lead {
      font-size: 1.1rem;
      opacity: 0.9;
    }
  </style>

</head>

<body>
  <!-- Page Preloder
  <div id="preloder">
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
              <li class="active"><a href="index.php">Home</a></li>

              <li>
                <a href="#">Our Services</a>
                <ul class="dropdown">
                  <li><a href="wedding_planning.php">Wedding Planning</a></li>
                  <li><a href="wedding_decoration.php">Wedding Decoration</a></li>
                  <li><a href="event_rentals.php">Event Rentals</a></li>
                  <li><a href="transport_services.php">Transport Services</a></li>
                  <li><a href="destination-management-services.php">Destination Management Services</a></li>
                </ul>
              </li>
              <li><a href="about_us.php">About Us</a></li>

              <li><a href="contact_us.php">Contact Us</a></li>
            </ul>
          </nav>
        </div>
        <div class="col-lg-3">
          <div class="header__widget">
            <a href="book_us" class="primary-btn normal-btn"> <b style="color:white;">Book Us</b></a>

          </div>
        </div>
      </div>
      <div class="canvas__open"><i class="fa fa-bars"></i></div>
    </div>
  </header>
  <!-- Header Section End -->

  <!-- Hero Section Begin -->
  <section class="hero">
    <div class="hero__slider owl-carousel">
      <div class="hero__items set-bg" data-setbg="img/hero/v4.png">
        <div class="hero__text">
          <h2>
            welcome to <br />
            ab events group.
          </h2>
          <p><b>"Where Exceptional Events Begin"</b> </button>
          </p>

          <div class="hero__social">
            <a href="https://www.instagram.com/abevents_250/"><button type="button" class="btn btn-info rounded-circle"><i class="fa fa-instagram"></i></button></a>
            <a href="https://www.youtube.com/@abeventsgroup490"><button type="button" class="btn btn-info rounded-circle"><i class="fa fa-youtube-play"></i></button></a>
          </div>
        </div>
      </div>
      <!-- <div class="hero__items set-bg" data-setbg="img/hero/elegance.png">
        <div class="hero__text">
          <h2>Elegance Decor</h2>

          <div class="hero__social">
            <a href="https://www.instagram.com/elegancedecor_250/"><button type="button" class="btn btn-info rounded-circle "><i class="fa fa-instagram"></i></button><span class="social_footer_title"> Elegance Decor</span></a>
          </div>
        </div>
      </div> -->

    </div>
    <!-- <div class="slide-num" id="snh-1"></div> -->
    <div class="slider__progress"><span></span></div>
  </section>
  <!-- Hero Section End -->

  <!-- About Section Begin -->
  <section class="about spad">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="about__text">
            <div class="section-title">
              <span>who are we</span>
              <h2>AB EVENTS GROUP</h2>
            </div>
            <div class="about__para__text">
              <p>
                With over eight years of experience in event management, AB Events Group has earned
                a reputation as a trusted leader in curating unforgettable moments.
                Based in Kigali, Rwanda, we specialize in crafting events that blend creativity, precision,
                and a deep understanding of our clients' visions. From breathtaking weddings to flawless corporate functions,
                our expert team is committed to delivering unparalleled service tailored to your unique needs.


              </p>
            </div>
            <a href="about_us.php" class="primary-btn normal-btn ">Read More</a>
            <a href="about_us.php" id="videoModalButton" data-bs-toggle="modal" data-bs-target="#videoModal" class="primary-btn normal-btn">AB EVENTS GROUP VIDEOS</a>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="about__pic">
            <!-- <div class="about__pic__inner"> -->
            <div class="">
              <img src="img/ab_about.gif" alt="" class="rounded-circle" style="margin-top: 60px;" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- About Section End -->
  <!-- Start of wedding planning service -->
  <section class="project-details py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">

          <div class="section-title text-center">
            <span>AB EVENTS GROUP</span>
            <h2>Our Services</h2>
          </div>

        </div>
      </div>
      <!-- Main Content Row -->
      <div class="row">
        <!-- Left Content -->
        <div class="col-lg-8">
          <div class="content-wrapper bg-white p-4 rounded shadow-sm">
            <h2 class="display-5 mb-4 text-primary">Wedding Planning</h2>
            <p>
              Your love story deserves a celebration as extraordinary as the bond you share. At AB Events Group, we bring your dream wedding to life by providing comprehensive planning services that reflect your personal style and story.
            </p>
            <a href="wedding_planning.php" class="primary-btn normal-btn">Read More</a>

          </div>
        </div>

        <!-- Right Content -->
        <div class="col-lg-4">
          <div class="image-wrapper">
            <img src="img/weeding_planning.jpg"
              class="img-fluid rounded shadow"
              alt="AB Events Group Wedding Planning Photo">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End of wedding planning service -->

  <!-- Start of Wedding Decorationservice -->
  <section class="project-details py-5">
    <div class="container">
      <!-- Main Content Row -->
      <div class="row">
        <!-- Left Content -->

        <div class="col-lg-4">
          <div class="image-wrapper">
            <img src="img/photos/deco_1.jpg"
              class="img-fluid rounded shadow"
              alt="AB Events Group Wedding Planning Photo">
          </div>
        </div>
        <!-- Right Content -->

        <div class="col-lg-8">
          <div class="content-wrapper bg-white p-4 rounded shadow-sm">
            <h2 class="display-5 mb-4 text-primary">Wedding Decoration</h2>
            <p>
              Transform your venue into a masterpiece with our bespoke decoration services. We take pride in creating stunning visuals that capture the essence of your special day.
            </p>
            <a href="wedding_decoration.php" class="primary-btn normal-btn">Read More</a>

          </div>
        </div>


      </div>
    </div>
  </section>
  <!-- End of Wedding Decoration service -->



  <!-- Start of Event Rentals service -->
  <section class="project-details py-5">
    <div class="container">
      <!-- Main Content Row -->
      <div class="row">
        <!-- Left Content -->
        <div class="col-lg-8">
          <div class="content-wrapper bg-white p-4 rounded shadow-sm">
            <h2 class="display-5 mb-4 text-primary">Event Rentals</h2>
            <p>
              Elevate your event with our wide range of high-quality rental options, designed to suit any occasion and style.
            </p>
            <a href="event_rentals.php" class="primary-btn normal-btn">Read More</a>

          </div>
        </div>

        <!-- Right Content -->
        <div class="col-lg-4">
          <div class="image-wrapper">
            <img src="img/event_rentals.png"
              class="img-fluid rounded shadow"
              alt="AB Events Group Wedding Planning Photo">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End of Event Rentals service -->



  <!-- Start of Transport Services service -->
  <section class="project-details py-5">
    <div class="container">
      <!-- Main Content Row -->
      <div class="row">
        <!-- Left Content -->

        <div class="col-lg-4">
          <div class="image-wrapper">
            <img src="img/transport_services.png"
              class="img-fluid rounded shadow"
              alt="AB Events Group Wedding Planning Photo">
          </div>
        </div>
        <!-- Right Content -->

        <div class="col-lg-8">
          <div class="content-wrapper bg-white p-4 rounded shadow-sm">
            <h2 class="display-5 mb-4 text-primary">Transport Services</h2>
            <p>
              Ensure smooth and hassle-free transportation for your event with our reliable and punctual services. </p>
            <a href="transport_services.php" class="primary-btn normal-btn">Read More</a>

          </div>
        </div>


      </div>
    </div>
  </section>
  <!-- End of Transport Services service -->



  <!-- Start of Destination Management Services -->
  <section class="project-details py-5">
    <div class="container">
      <!-- Main Content Row -->
      <div class="row">
        <!-- Left Content -->
        <div class="col-lg-8">
          <div class="content-wrapper bg-white p-4 rounded shadow-sm">
            <h2 class="display-5 mb-4 text-primary">Destination Management Services</h2>
            <p>
              Planning a destination event? AB Events Group offers end-to-end destination management to bring your vision to life in any location. </p>
            <a href="destination-management-services.php" class="primary-btn normal-btn">Read More</a>

          </div>
        </div>

        <!-- Right Content -->
        <div class="col-lg-4">
          <div class="image-wrapper">
            <img src="img/destination-management-services.png"
              class="img-fluid rounded shadow"
              alt="AB Events Group Destination Management Services Photo">
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- End of Destination Management Services  -->





  <!-- Call To Action Section Begin -->
  <section class="why-choose py-5" style="background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('img/footer-bg.jpg') center/cover;">
    <div class="container">
      <!-- Header -->
      <div class="row justify-content-center text-center text-white mb-5">
        <div class="col-lg-10">
          <span class="text-primary text-uppercase fw-bold mb-3 d-block">Why Choose AB Events Group?</span>
          <h2 class="display-5 mb-4">Transform Your Ideas Into Extraordinary Memories</h2>
        </div>
      </div>

      <!-- Features -->
      <div class="row g-4 mb-5">
        <!-- Expertise -->
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 bg-transparent text-white border-0">
            <div class="card-body text-center">
              <i class="fas fa-award fa-2x text-primary mb-3"></i>
              <h3 class="h5 fw-bold mb-3">Expertise You Can Trust</h3>
              <p class="text-white-50">With over eight years in the industry, we bring unparalleled experience and professionalism to every event.</p>
            </div>
          </div>
        </div>

        <!-- Attention -->
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 bg-transparent text-white border-0">
            <div class="card-body text-center">
              <i class="fa fa-search-plus fa-2x text-primary mb-3"></i>
              <h3 class="h5 fw-bold mb-3">Attention to Detail</h3>
              <p class="text-white-50">Every aspect of your event is meticulously planned and executed to perfection.</p>
            </div>
          </div>
        </div>

        <!-- Personal -->
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 bg-transparent text-white border-0">
            <div class="card-body text-center">
              <i class="fas fa-user-check fa-2x text-primary mb-3"></i>
              <h3 class="h5 fw-bold mb-3">Personalized Approach</h3>
              <p class="text-white-50">We take the time to understand your vision and make it a reality.</p>
            </div>
          </div>
        </div>

        <!-- Excellence -->
        <div class="col-md-6 col-lg-3">
          <div class="card h-100 bg-transparent text-white border-0">
            <div class="card-body text-center">
              <i class="fa fa-star fa-2x text-primary mb-3"></i>
              <h3 class="h5 fw-bold mb-3">Commitment to Excellence</h3>
              <p class="text-white-50">Our team is dedicated to delivering exceptional service and unforgettable experiences.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- CTA -->
      <div class="row justify-content-center text-center">
        <div class="col-lg-8">
          <p class="lead text-white mb-4">Let AB Events Group transform your ideas into extraordinary memories.</p>
          <a href="contact_us" class="primary-btn normal-btn" style="color:white">Contact Us Today</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Call To Action Section End -->

  <br>
  <!-- Testimonial Section Begin -->
  <section class="testimonial spad set-bg" data-setbg="img/clients_bg.png">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <div class="section-title">
            <!-- <span>Testimonials</span> -->
            <h2>Our Clients</h2>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="col-lg-12">
          <div class="logo__carousel owl-carousel">
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/1.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/2.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/3.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/4.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/5.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/6.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/7.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/8.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/9.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/10.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/11.png" alt="" /></a>
            </div>
            <div class="logo__carousel__item">
              <a href="#"><img src="img/clients/12.png" alt="" /></a>
            </div>

          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Testimonial Section End -->





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

  <!-- Start Of Modal -->
  <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="videoModalLabel">AB EVENTS SERVICES</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <iframe width="100%" height="500px;" src="https://www.youtube.com/embed/UYhcSquc18Y" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
      </div>
    </div>
  </div>
  <!-- End Of Modal -->
  <script>
    $(document).ready(function() {
      $('#videoModal').on('shown.bs.modal', function() {
        var video = document.getElementById('modalVideo');
        video.play();
      });

      $('#videoModal').on('hidden.bs.modal', function() {
        var video = document.getElementById('modalVideo');
        video.pause();
      });
    });
  </script>
</body>

</html>