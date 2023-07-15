<?php
include 'components/connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>playlists</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- courses section start -->
  <section class="courses">
    <h1 class="heading">our playlists</h1>
    <div class="box-container">
      <?php
      if(isset($_POST['search_btn']) or isset($_POST['search_value'])){
        $search_value = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);
        $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE status = ?  AND title LIKE '%{$search_value}%' ORDER BY date DESC");
        $select_playlists->execute(["active"]);
      }else{
        $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE status = ? ORDER BY date DESC");
        $select_playlists->execute(["active"]);
      }
      if($select_playlists->rowCount()>0){
        while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_OBJ)){
          //select teacher of the playlist
          $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ?");
          $select_teacher->execute([$fetch_playlist->teacher_id]);
          $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
          //select courses of the playlist
          $select_courses = $conn->prepare("SELECT * FROM content WHERE playlist_id = ?");
          $select_courses->execute([$fetch_playlist->id]);
          $no_of_courses = $select_courses->rowCount(); 
          ?>
      <div class="box">
        <a href="teacher_profile.php?id=<?=$fetch_teacher->id?>" class="box-info">
          <?php if($fetch_teacher->image != '' ){?>
          <img src="images/uploaded_images/teachers/<?=$fetch_teacher->image?>" alt="teacher-image" />
          <?php }else{?>
          <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
          <?php }?>
          <div>
            <h5><?=$fetch_teacher->name?></h5>
            <p><?=$fetch_playlist->date?></p>
          </div>
        </a>
        <div class="box-thumb">
          <img src="images/uploaded_images/courses/thumbs/<?=$fetch_playlist->thumb?>" alt="course-thumb" />
          <span><?=$no_of_courses?> videos</span>
        </div>
        <h3><?=$fetch_playlist->title?></h3>
        <a href="playlist.php?id=<?=$fetch_playlist->id?>" class="btn inline-btn">view playlist</a>
      </div>
      <?php }
      }else{
        echo "<p class='error'>No courses added yet!</p>";
      }
      ?>
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