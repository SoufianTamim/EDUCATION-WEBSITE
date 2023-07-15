<?php
include 'components/connection.php';
if(isset($_GET['id'])){
  $teacher_id = $_GET['id'];
  $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
  $select_teacher->execute([$teacher_id]);
  if($select_teacher->rowCount()>0){
    $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
    //select number of playlists that teacher has
    $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE teacher_id = ? AND status = ?");
    $select_playlists->execute([$fetch_teacher->id, "active"]);
    $no_of_playlists = $select_playlists->rowCount();
    //select number of videos that teacher has
    $select_courses = $conn->prepare("SELECT * FROM content WHERE teacher_id = ? AND status = ?");
    $select_courses->execute([$fetch_teacher->id, "active"]);
    $no_of_courses = $select_courses->rowCount();
    //select number of likes that teacher has
    $no_of_likes = 0;
    $no_of_comments = 0;
    while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){
      $select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
      $select_likes->execute([$fetch_course->id]);
      $no_of_likes += $select_likes->rowCount();
      //select number of comments that teacher has
      $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
      $select_comments->execute([$fetch_course->id]);
      $no_of_comments += $select_comments->rowCount();
    }
  }else{
    $warning_msg[] = "Something went wrong!";
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>tutor profile</title>

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
  <section class="profile teacher-profile">
    <h1 class="heading">teacher profile</h1>
    <div class="row">
      <div class="profile-info">
        <?php if($fetch_teacher->image != '' ){?>
        <img src="images/uploaded_images/teachers/<?= $fetch_teacher->image?>" alt="teacher-image" />
        <?php }else{?>
        <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
        <?php }?>
        <h3><?=$fetch_teacher->name?></h3>
        <p><?=$fetch_teacher->profession?></p>
      </div>
      <div class="flex">
        <p>total playlists: <span><?=$no_of_playlists?></span></p>
        <p>total videos: <span><?=$no_of_courses?></span></p>
        <p>total likes: <span><?=$no_of_likes?></span></p>
        <p>total comments: <span><?=$no_of_comments?></span></p>
      </div>
    </div>
  </section>
  <!-- profile section end -->

  <!-- courses section start -->
  <section class="courses">
    <h1 class="heading">teacher courses</h1>
    <div class="box-container">
      <div class="box">
        <div class="box-thumb">
          <img src="images/thumb-1.png" alt="course-thumb" />
          <span>10 videos</span>
        </div>
        <h3>complete HTML tutorial</h3>
        <a href="playlist.php" class="btn inline-btn">view playlist</a>
      </div>
      <div class="box">
        <div class="box-thumb">
          <img src="images/thumb-2.png" alt="course-thumb" />
          <span>10 videos</span>
        </div>
        <h3>complete HTML tutorial</h3>
        <a href="playlist.php" class="btn inline-btn">view playlist</a>
      </div>
      <div class="box">
        <div class="box-thumb">
          <img src="images/thumb-3.png" alt="course-thumb" />
          <span>10 videos</span>
        </div>
        <h3>complete HTML tutorial</h3>
        <a href="playlist.php" class="btn inline-btn">view playlist</a>
      </div>
      <div class="box">
        <div class="box-thumb">
          <img src="images/thumb-4.png" alt="course-thumb" />
          <span>10 videos</span>
        </div>
        <h3>complete HTML tutorial</h3>
        <a href="playlist.php" class="btn inline-btn">view playlist</a>
      </div>
    </div>
  </section>
  <!-- courses section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>