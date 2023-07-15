<?php
include '../components/connection.php';
if($admin_id == ''){
  header('location:login.php');
}else{
  // select teacher info
  $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
  $select_teacher->execute([$admin_id]);
  $fetch_teacher =  $select_teacher->fetch(PDO::FETCH_OBJ);
  $prev_name = $fetch_teacher->name;
  $prev_profession = $fetch_teacher->profession;
  $prev_email = $fetch_teacher->email;
  $prev_password = $fetch_teacher->password;
  $prev_image = $fetch_teacher->image;
}

//update button clicked
if(isset($_POST['update'])){
  //update name
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  if(!empty($name) && $name != $prev_name){
    $update_name = $conn->prepare("UPDATE teachers SET name = ? WHERE id = ?");
    $update_name->execute([$name, $admin_id]);
    $success_msg[] = "Username updated successfully!";
  }

  //update email
  $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
  if(!empty($email) && $email != $prev_email){
    $select_email = $conn->prepare("SELECT email FROM teachers WHERE email = ?");
    $select_email->execute([$email]);
    if($select_email->rowCount()>0){
      $warning_msg[] = 'Email is already taken!';
    }else{
      $update_email = $conn->prepare("UPDATE teachers SET email = ? WHERE id = ?");
      $update_email->execute([$email, $admin_id]);
      $success_msg[] = "Email updated successfully!";
    }
  }

  //update phone
  $profession = filter_var($_POST['profession'], FILTER_SANITIZE_STRING); 
  if(!empty($profession) && $profession != $prev_profession){
    $update_profession = $conn->prepare("UPDATE teachers SET profession = ? WHERE id = ?");
    $update_profession->execute([$profession, $admin_id]);
    $success_msg[] = "Profession updated successfully!";
  }

  //update password
  $opassword = sha1($_POST['opassword']);
  $opassword = filter_var($opassword, FILTER_SANITIZE_STRING);
  $password = sha1($_POST['password']);
  $password = filter_var($password, FILTER_SANITIZE_STRING);
  $cpassword = sha1($_POST['cpassword']);
  $cpassword = filter_var($cpassword, FILTER_SANITIZE_STRING);
  $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
  if($opassword != $empty_pass){
    if($opassword != $prev_password){
      $warning_msg[] = 'Old password not matched!';
    }else if($cpassword != $password){
      $warning_msg[] = "Confirm password not matched!";
    }else{
      $update_password = $conn->prepare("UPDATE teacher SET password = ? WHERE id = ?");
      $update_password->execute([$cpassword, $admin_id]);
      $success_msg[]= "Password updated successfully!";
    }
  }

  //update image
  $image = $_FILES['image']['name'];
  $image = filter_var($image, FILTER_SANITIZE_STRING);
  $image_size = $_FILES['image']['size'];
  $image_temp_name = $_FILES['image']['tmp_name'];
  $ext = pathinfo($image, PATHINFO_EXTENSION);
  $rename = create_unique_id().'.'.$ext;
  $image_folder = '../images/uploaded_images/teachers/'.$rename;
  if(!empty($image) && $image != $prev_image){
    if($image_size > 2000000){
      $warning_msg[] = 'Image is too large!';
    }else{
      $update_image = $conn->prepare("UPDATE teachers SET image = ? WHERE id = ?");
      $update_image->execute([$rename, $admin_id]);
      move_uploaded_file($image_temp_name, $image_folder);
      if($prev_image != '' AND $prev_image != $rename){
        unlink('../images/uploaded_images/teachers/'.$prev_image);
      }
      $success_msg[] = "Image updated successfully!";
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
  <title>admin | update</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include '../components/admin_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- update form section start -->
  <section class="form-container group-form">
    <form method="post" class="form" enctype="multipart/form-data">
      <h1>edit profile</h1>
      <div class="form-flex">
        <div class="input-group">
          <label for="name">Your name<span>*</span></label>
          <input type="text" name="name" id="name" class="input-box" placeholder="enter your name"
            value="<?=$fetch_teacher->name?>" />
        </div>
        <div class="input-group">
          <label for="email">Your email<span>*</span></label>
          <input type="email" name="email" id="email" class="input-box" placeholder="enter your email"
            value="<?=$fetch_teacher->email?>" />
        </div>
        <div class="input-group">
          <label for="profession">Your profession<span>*</span></label>
          <input type="text" name="profession" id="profession" class="input-box" placeholder="enter your profession"
            value="<?=$fetch_teacher->profession?>" />
        </div>
        <div class="input-group">
          <label for="image">Your image<span>*</span></label>
          <input type="file" name="image" id="image" class="input-box" placeholder="<?=$fetch_teacher->image?>" />
        </div>
        <div class="input-group">
          <label for="password">Your new password<span>*</span></label>
          <div class="password-container">
            <i class="fas fa-eye-slash password-toggle"></i>
            <input type="password" placeholder="enter your new password" name="password" id="password" class="input-box"
              maxlength="6" minlength="6" />
          </div>
        </div>
        <div class="input-group">
          <label for="cpassword">Confirm new password<span>*</span></label>
          <div class="password-container">
            <i class="fas fa-eye-slash password-toggle"></i>
            <input type="password" placeholder="confirm your new password" name="cpassword" id="cpassword"
              class="input-box" maxlength="6" minlength="6" />
          </div>
        </div>
        <div class="input-group">
          <label for="opassword">Your old password<span>*</span></label>
          <div class="password-container">
            <i class="fas fa-eye-slash password-toggle"></i>
            <input type="password" placeholder="enter your old password" name="opassword" id="opassword"
              class="input-box" maxlength="6" minlength="6" />
          </div>
        </div>
      </div>
      <button type="submit" name="update" class="btn">update profile</button>
    </form>
  </section>
  <!-- update form section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="../js/script.js"></script>
  <?php include '../components/alert.php';?>
</body>

</html>