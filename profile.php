<?php
include 'components/connection.php';
if($user_id == ''){
  header('location:login.php');
}else{
  $select_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
  $select_user->execute([$user_id]);
  $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
}
if(isset($_GET['logout'])){
  setcookie('user_id', '', time() - 1, '/');
  header('location:home.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>profile</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- profile section start -->
  <section class="profile">
    <h1 class="heading">your profile</h1>
    <div class="row">
      <div class="profile-info">
        <?php if($fetch_user->image != '' ){?>
        <img src="images/uploaded_images/users/<?=$fetch_user->image?>" class="image" alt="user-image" />
        <?php }else{?>
        <p class="alt-img"><?= $fetch_user->name[0]?></p>
        <?php }?>
        <h3><?=$fetch_user->name?></h3>
        <p>student</p>
        <div class="flex-btn">
          <a href="update.php" class="btn inline-btn">update profile</a>
          <a href="profile.php?logout" class="btn inline-btn delete-btn"
            onclick="return confirm('Sure you want to logout!')">logout</a>
        </div>
      </div>
      <div class="box-container">
        <div class="box">
          <div class="flex">
            <i class="fas fa-bookmark"></i>
            <div>
              <h2>4</h2>
              <p>saved playlist</p>
            </div>
          </div>
          <a href="#" class="btn inline-btn">view playlist</a>
        </div>
        <div class="box">
          <div class="flex">
            <i class="fas fa-heart"></i>
            <div>
              <h2>33</h2>
              <p>videos liked</p>
            </div>
          </div>
          <a href="#" class="btn inline-btn">view liked</a>
        </div>
        <div class="box">
          <div class="flex">
            <i class="fas fa-comment"></i>
            <div>
              <h2>12</h2>
              <p>videos comments</p>
            </div>
          </div>
          <a href="#" class="btn inline-btn">view comments </a>
        </div>
      </div>
    </div>
  </section>
  <!-- profile section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>