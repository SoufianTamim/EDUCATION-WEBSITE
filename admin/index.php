<?php
include '../components/connection.php';
if($admin_id == ''){
  header('location:login.php');
}else{
  // select teacher info
  $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
  $select_teacher->execute([$admin_id]);
  $fetch_teacher =  $select_teacher->fetch(PDO::FETCH_OBJ);
  //select courses count
  $select_courses = $conn->prepare("SELECT * FROM content WHERE teacher_id = ?");
  $select_courses->execute([$admin_id]);
  $no_of_courses = $select_courses->rowCount();
  //select playlists count
  $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE teacher_id = ?");
  $select_playlists->execute([$admin_id]);
  $no_of_playlists = $select_playlists->rowCount();
  //select number of comments & likes 
  $no_of_comments = 0; $no_of_likes = 0;
  if($no_of_courses > 0){
    while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){
      $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
      $select_comments->execute([$fetch_course->id]);
      if($select_comments->rowCount() > 0){
        $no_of_comments += $select_comments->rowCount();
      }
      $select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
      $select_likes->execute([$fetch_course->id]);
      if($select_likes->rowCount() > 0){
        $no_of_likes += $select_likes->rowCount();
      }
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
  <title>admin | dashboard</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/admin_style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include '../components/admin_header.php'?>
  <!-- header & sidebar section end -->

  <!-- login form section start -->
  <section class="dashboard">
    <h1 class="heading">admin dashboard</h1>
    <div class="box-container">
      <div class="box">
        <h3>welcome!</h3>
        <p><?=$fetch_teacher->name?></p>
        <a href="profile.php" class="btn">view profile</a>
      </div>
      <div class="box">
        <h3><?=$no_of_playlists?></h3>
        <p>total playlists</p>
        <a href="playlists.php" class="btn">view playlists</a>
      </div>
      <div class="box">
        <h3><?=$no_of_courses?></h3>
        <p>total courses</p>
        <a href="courses.php" class="btn">view courses</a>
      </div>
      <div class="box">
        <h3><?=$no_of_likes?></h3>
        <p>total likes</p>
        <a href="contacts.php" class="btn">view messages</a>
      </div>
      <div class="box">
        <h3><?=$no_of_comments?></h3>
        <p>total comments</p>
        <a href="comments.php" class="btn">view comments</a>
      </div>
    </div>
  </section>
  <!-- login form section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="../js/script.js"></script>
  <?php include '../components/alert.php';?>
</body>

</html>