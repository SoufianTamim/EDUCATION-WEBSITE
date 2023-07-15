<?php
include '../components/connection.php';

if(isset($_POST['login'])){
  $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING);

  $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE email = ? AND password = ?  LIMIT 1");
  $select_teacher->execute([$email, $password]);
  $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
  if($select_teacher->rowCount()>0){
    setcookie('admin_id', $fetch_teacher->id, time() + 60*60*24*30, '/');
    header('location:index.php');
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
  <title>admin | login</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/style.css" />
  <style>
  body {
    padding-left: 0 !important;
  }

  .form-container {
    width: 100%;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  </style>
</head>

<body>
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
  <script>
  //password toggle
  let passwordContainer = document.querySelectorAll(".password-container");
  passwordContainer.forEach((x) => {
    let passwordToggle = x.querySelector(".password-toggle");
    passwordToggle.addEventListener("click", () => {
      let passwordInput = x.querySelector("input");
      let passInputType = passwordInput.getAttribute("type");
      if (passInputType == "password") {
        passwordInput.setAttribute("type", "text");
        passwordToggle.classList.add("fa-eye");
        passwordToggle.classList.remove("fa-eye-slash");
      }
      if (passInputType == "text") {
        passwordInput.type = "password";
        passwordToggle.classList.add("fa-eye-slash");
        passwordToggle.classList.remove("fa-eye");
      }
    });
  });
  </script>
  <?php include '../components/alert.php';?>
</body>

</html>