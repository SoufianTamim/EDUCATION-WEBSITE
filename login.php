<?php
include 'components/connection.php';

if(isset($_POST['login'])){
  $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  $select_user = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?  LIMIT 1");
  $select_user->execute([$email, $password]);
  $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
  if($select_user->rowCount()>0){
    setcookie('user_id', $fetch_user->id, time() + 60*60*24*30, '/');
    header('location:home.php');
  }else{
    $warning_msg[] = 'Incorrect email or password!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>login</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- login form section start -->
  <section class="form-container">
    <form method="post" class="form">
      <h1>login now</h1>
      <label for="email">Your email<span>*</span></label>
      <input type="email" placeholder="enter your email" name="email" id="email" required class="input-box" />
      <label for="password">Your password<span>*</span></label>
      <div class="password-container">
        <i class="fas fa-eye-slash password-toggle"></i>
        <input type="password" placeholder="enter your password" name="password" id="password" required
          class="input-box" maxlength="6" minlength="6" />
      </div>
      <p class="redirect">Don't have an account? <a href="register.php">register
          now</a></p>
      <button type="submit" name="login" class="btn">login now</button>
    </form>
  </section>
  <!-- login form section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>