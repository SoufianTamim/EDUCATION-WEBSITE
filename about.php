<?php
include 'components/connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>about us</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- about section start -->
  <section class="about">
    <div class="row">
      <div class="image">
        <img src="images/about-img.svg" alt="about-image" />
      </div>
      <div class="content">
        <h3>why choose us?</h3>
        <p>
          Lorem ipsum dolor sit, amet consectetur adipisicing elit. Numquam at
          perferendis dolorem, ea voluptas maiores corrupti accusamus provident
          eaque dolore dolores inventore, quasi sed veritatis saepe labore. Optio,
          rem voluptatibus
        </p>
        <a href="courses.php" class="btn inline-btn">our courses</a>
        <a href="<?php if($user_id == ''){echo 'login.php';}else{echo 'courses.php';}?>" class="btn inline-btn"
          style="margin-left: 0.5rem">get started</a>
      </div>
    </div>
    <div class="box-container">
      <div class="box">
        <i class="fas fa-graduation-cap"></i>
        <div>
          <h3>+10k</h3>
          <p>online courses</p>
        </div>
      </div>

      <div class="box">
        <i class="fas fa-user-graduate"></i>
        <div>
          <h3>+40k</h3>
          <p>brilliant students</p>
        </div>
      </div>

      <div class="box">
        <i class="fas fa-chalkboard-user"></i>
        <div>
          <h3>+2k</h3>
          <p>expert tutors</p>
        </div>
      </div>

      <div class="box">
        <i class="fas fa-briefcase"></i>
        <div>
          <h3>100%</h3>
          <p>job placement</p>
        </div>
      </div>
    </div>
  </section>
  <!-- about section end -->

  <!-- review section start -->
  <section class="review">
    <h1 class="heading">student's reviews</h1>
    <div class="box-container">
      <div class="box">
        <p>
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Amet, qui maxime
          mollitia quod incidunt, exercitationem, itaque corrupti expedita
          praesentium asperiores consequatur odit laborum! Possimus, necessitatibus
          fuga itaque ipsa ea voluptates.
        </p>
        <div class="box-info">
          <img src="images/pic-1.jpg" alt="review-image" />
          <div>
            <h3>john deo</h3>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <p>
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Amet, qui maxime
          mollitia quod incidunt, exercitationem, itaque corrupti expedita
          praesentium asperiores consequatur odit laborum! Possimus, necessitatibus
          fuga itaque ipsa ea voluptates.
        </p>
        <div class="box-info">
          <img src="images/pic-2.jpg" alt="review-image" />
          <div>
            <h3>john deo</h3>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <p>
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Amet, qui maxime
          mollitia quod incidunt, exercitationem, itaque corrupti expedita
          praesentium asperiores consequatur odit laborum! Possimus, necessitatibus
          fuga itaque ipsa ea voluptates.
        </p>
        <div class="box-info">
          <img src="images/pic-3.jpg" alt="review-image" />
          <div>
            <h3>john deo</h3>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <p>
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Amet, qui maxime
          mollitia quod incidunt, exercitationem, itaque corrupti expedita
          praesentium asperiores consequatur odit laborum! Possimus, necessitatibus
          fuga itaque ipsa ea voluptates.
        </p>
        <div class="box-info">
          <img src="images/pic-4.jpg" alt="review-image" />
          <div>
            <h3>john deo</h3>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="box">
        <p>
          Lorem ipsum dolor, sit amet consectetur adipisicing elit. Amet, qui maxime
          mollitia quod incidunt, exercitationem, itaque corrupti expedita
          praesentium asperiores consequatur odit laborum! Possimus, necessitatibus
          fuga itaque ipsa ea voluptates.
        </p>
        <div class="box-info">
          <img src="images/pic-6.jpg" alt="review-image" />
          <div>
            <h3>john deo</h3>
            <div class="stars">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star-half-alt"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- about section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>