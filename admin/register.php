<?php
include '../components/connection.php';

if(isset($_POST['register'])){
  $id = create_unique_id();
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING);
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $cpassword = sha1($_POST['cpassword']);
  $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);

  $image = $_FILES['image']['name'];
  $image = filter_var($image, FILTER_SANITIZE_STRING);
  $image_size = $_FILES['image']['size'];
  $image_temp_name = $_FILES['image']['tmp_name'];
  $ext = pathinfo($image, PATHINFO_EXTENSION);
  $rename = create_unique_id().'.'.$ext;
  $image_folder = '../images/uploaded_images/teachers/'.$rename;

  $select_teachers = $conn->prepare("SELECT * FROM teachers WHERE email = ?");
  $select_teachers->execute([$email]);
  if($select_teachers->rowCount()>0){
    $warning_msg[] = "Email is already taken!";
  }else{
    if($cpassword != $password){$warning_msg[] = "Confirm password not matched!";}
    else if($image_size > 2000000){$warning_msg[] = "Image size is too large!";}
    else{
      if(empty($image)){
        $rename = "";
      }else{
        move_uploaded_file($image_temp_name, $image_folder);
      }
      $insert_teacher = $conn->prepare("INSERT INTO teachers (id, name, email, profession, password, image) VALUES (?,?,?,?,?,?)");
      $insert_teacher->execute([$id, $name, $email, $profession, $cpassword, $rename]);
      $success_msg[]= "Your account created successfully!";
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
  <title>admin | register</title>

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
  <!-- register form section start -->
  <section class="form-container group-form">
    <form method="post" class="form" enctype="multipart/form-data">
      <h1>register now</h1>
      <div class="form-flex">
        <div class="input-group">
          <label for="name">Your name<span>*</span></label>
          <input type="text" placeholder="enter your name" name="name" id="name" required class="input-box" />
        </div>
        <div class="input-group">
          <label for="email">Your email<span>*</span></label>
          <input type="email" placeholder="enter your email" name="email" id="email" required class="input-box" />
        </div>
        <div class="input-group"><label for="profession">Your profession<span>*</span></label>
          <input type="text" placeholder="enter your profession" name="profession" id="profession" required
            class="input-box" />
        </div>
        <div class="input-group"><label for="image">Your image<span>*</span></label>
          <input type="file" placeholder="enter your image" name="image" id="image" class="input-box" />
        </div>
        <div class="input-group"><label for="password">Your password<span>*</span></label>
          <div class="password-container">
            <i class="fas fa-eye-slash password-toggle"></i>
            <input type="password" placeholder="enter your password" name="password" id="password" required
              class="input-box" maxlength="6" minlength="6" />
          </div>
        </div>
        <div class="input-group"><label for="cpassword">Confirm password<span>*</span></label>
          <div class="password-container">
            <i class="fas fa-eye-slash password-toggle"></i>
            <input type="password" placeholder="confirm your password" name="cpassword" id="cpassword" required
              class="input-box" maxlength="6" minlength="6" />
          </div>
        </div>
      </div>
      <p class="redirect">Already have an account? <a href="login.php">login
          now</a></p>
      <button type="submit" name="register" class="btn">register now</button>
    </form>
  </section>
  <!-- register form section end -->

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