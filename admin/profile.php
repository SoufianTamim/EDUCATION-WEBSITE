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
  <title>admin | profile</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include '../components/admin_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- profile section start -->
  <section class="profile teacher-profile">
    <h1 class="heading">teacher profile</h1>
    <div class="row">
      <div class="profile-info">
        <?php if($fetch_teacher->image != '' ){?>
        <img src="../images/uploaded_images/teachers/<?= $fetch_teacher->image?>" alt="teacher-image" />
        <?php }else{?>
        <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
        <?php }?>
        <h3><?=$fetch_teacher->name?></h3>
        <p><?=$fetch_teacher->profession?></p>
        <div class="flex-btn">
          <a href="update.php" class="btn inline-btn">edit profile</a>
          <a href="../components/admin_logout.php?logout" class="btn inline-btn delete-btn"
            onclick="return confirm('Sure you want to logout?')">logout</a>
        </div>
      </div>
      <div class="flex">
        <p><span><?=$no_of_playlists?></span>total playlists <a href="playlists.php" class="btn">view playlists</a></p>
        <p><span><?=$no_of_courses?></span>total courses <a href="courses.php" class="btn">view courses</a></p>
        <p><span><?=$no_of_likes?></span>total likes <a href="contacts.php" class="btn">view contacts</a></p>
        <p><span><?=$no_of_comments?></span>total comments <a href="comments.php" class="btn">view comments</a></p>
      </div>
    </div>
  </section>
  <!-- profile section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="../js/script.js"></script>
  <?php include '../components/alert.php';?>
</body>

</html>