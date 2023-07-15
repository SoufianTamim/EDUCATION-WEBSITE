<?php
include 'components/connection.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>teachers</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- teachers section start -->
  <section class="teachers">
    <h1 class="heading">expert teachers</h1>
    <form method="post" class="search-form">
      <input type="text" name="search_value" placeholder="search for teachers" />
      <button type="submit" name="search_btn" class="fas fa-search"></button>
    </form>
    <div class="box-container">
      <?php
      if(isset($_POST['search_btn']) or isset($_POST['search_value'])){
        $search_value = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);
        $select_teachers = $conn->prepare("SELECT * FROM teachers WHERE name LIKE '%{$search_value}%'");
        $select_teachers->execute([]);
        $fetch_teachers = $select_teachers->fetchAll(PDO::FETCH_OBJ);
      }else{
        $select_teachers = $conn->prepare("SELECT * FROM teachers");
        $select_teachers->execute([]);
        $fetch_teachers = $select_teachers->fetchAll(PDO::FETCH_OBJ);
      }
      if($select_teachers->rowCount()>0){
        foreach($fetch_teachers as $fetch_teacher){
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
          while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){
            $select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
            $select_likes->execute([$fetch_course->id]);
            $no_of_likes += $select_likes->rowCount();
          }
          ?>
      <div class="box">
        <div class="box-info">
          <?php if($fetch_teacher->image != '' ){?>
          <img src="images/uploaded_images/teachers/<?= $fetch_teacher->image?>" alt="teacher-image" />
          <?php }else{?>
          <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
          <?php }?>
          <div>
            <h5><?= $fetch_teacher->name?></h5>
            <p><?= $fetch_teacher->profession?></p>
          </div>
        </div>
        <p>total playlists: <span><?=$no_of_playlists?></span></p>
        <p>total videos: <span><?=$no_of_courses?></span></p>
        <p>total likes: <span><?=$no_of_likes?></span></p>
        <a href="teacher_profile.php?id=<?=$fetch_teacher->id?>" class="btn inline-btn">view profile</a>
      </div>
      <?php }}else{ ?>
      <p class="error back-error">There is no teachers!</p>
      <?php } ?>
    </div>
  </section>
  <!-- teachers section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>