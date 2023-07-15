<?php
include 'components/connection.php';


if(isset($_POST['send'])){
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
  $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
  if(!preg_match('/^[0-9]{11}+$/', $phone)){
    $warning_msg[] = 'Phone number is not correct!';
  }else{
    $select_message = $conn->prepare("SELECT * FROM contact WHERE name = ? AND email = ? AND number = ? AND message = ?");
    $select_message->execute([$name, $email, $phone, $message]);
    if($select_message->rowCount()>0){
      $warning_msg[] = 'Message already sent!';
    }else{
      $send_message = $conn->prepare("INSERT INTO contact (name, email, number, message) VALUES (?, ?, ?, ?)");
      $send_message->execute([$name, $email, $phone, $message]);
      $success_msg[] = 'Your message sent successfully!';
    }
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>contact us</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- contact section start -->
  <section class="contact">
    <div class="row">
      <div class="image">
        <img src="images/contact-img.svg" alt="contact-image" />
      </div>
      <form method="POST" class="form">
        <h1>Get In Touch</h1>
        <input type="text" placeholder="enter your name" name="name" required class="input-box" />
        <input type="email" placeholder="enter your email" name="email" required class="input-box" />
        <input type="tel" placeholder="enter your phone number" name="phone" required class="input-box" minlength="11"
          maxlength="11" />
        <textarea name="message" rows="5" class="input-box" placeholder="enter your message"></textarea>
        <button type="submit" name="send" class="btn inline-btn">
          send message
        </button>
      </form>
    </div>
    <div class="box-container">
      <div class="box">
        <i class="fas fa-phone"></i>
        <h3>phone number</h3>
        <a href="#">1246-45459-65</a>
        <a href="#">1246-45459-65</a>
      </div>
      <div class="box">
        <i class="fas fa-envelope"></i>
        <h3>email address</h3>
        <a href="#">user1@gmail.com</a>
        <a href="#">user2@gmail.com</a>
      </div>
      <div class="box">
        <i class="fas fa-map-marker-alt"></i>
        <h3>office address</h3>
        <a href="#">flat no 10, a-5 bulding, nasr city, cairo, egypt</a>
      </div>
    </div>
  </section>
  <!-- contact section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>

  <?php include 'components/alert.php';?>
</body>

</html>