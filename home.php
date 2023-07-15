<?php
include 'components/connection.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home</title>
  <!-- font awesome cdn link -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->
  <!-- landing section start -->
  <section class="landing">
    <h1 class="heading">quick options</h1>
    <div class="box-container">
      <div class="box">
        <h2 class="box-heading">likes and comments</h2>
        <?php 
          if($user_id != ''){
            $select_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $select_user->execute([$user_id]);
            $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
            //select number of likes user made
            $select_likes = $conn->prepare("SELECT * FROM likes WHERE user_id = ?");
            $select_likes->execute([$user_id]);
            $no_of_likes = $select_likes->rowCount();
            //select number of comments user made
            $select_comments = $conn->prepare("SELECT * FROM comments WHERE user_id = ?");
            $select_comments->execute([$user_id]);
            $no_of_comments = $select_comments->rowCount();
            //select number of bookmarks user made
            $select_bookmarks = $conn->prepare("SELECT * FROM bookmark WHERE user_id = ?");
            $select_bookmarks->execute([$user_id]);
            $no_of_bookmarks = $select_bookmarks->rowCount();
        ?>
        <p>Total likes: <span><?=$no_of_likes?></span></p>
        <a href="likes.php" class="btn inline-btn">view likes</a>
        <p>Total comments: <span><?=$no_of_comments?></span></p>
        <a href="comments.php" class="btn inline-btn">view comments</a>
        <p>Saved playlists: <span><?=$no_of_bookmarks?></span></p>
        <a href="bookmarks.php" class="btn inline-btn">view playlists</a>
        <?php  }else{ ?>
        <p class="error">Please login first</p>
        <?php }?>
      </div>
      <div class="box">
        <h2 class="box-heading">top categories</h2>
        <div class="flex">
          <a href="#"><i class="fas fa-code"></i><span>development</span></a>
          <a href="#"><i class="fas fa-chart-simple"></i><span>business</span></a>
          <a href="#"><i class="fas fa-pen"></i><span>design</span></a>
          <a href="#"><i class="fas fa-chart-line"></i><span>marketing</span></a>
          <a href="#"><i class="fas fa-music"></i><span>music</span></a>
          <a href="#"><i class="fas fa-camera"></i><span>photography</span></a>
          <a href="#"><i class="fas fa-cog"></i><span>software</span></a>
          <a href="#"><i class="fas fa-vial"></i><span>science</span></a>
        </div>
      </div>
      <div class="box">
        <h2 class="box-heading">popular topics</h2>
        <div class="flex">
          <a href="#"><i class="fab fa-html5"></i><span>HTML</span></a>
          <a href="#"><i class="fab fa-css3"></i><span>CSS</span></a>
          <a href="#"><i class="fab fa-js"></i><span>javascript</span></a>
          <a href="#"><i class="fab fa-react"></i><span>react</span></a>
          <a href="#"><i class="fab fa-php"></i><span>PHP</span></a>
          <a href="#"><i class="fab fa-bootstrap"></i><span>bootstrap</span></a>
        </div>
      </div>
      <div class="box">
        <h2 class="box-heading">become a tour</h2>
        <p>
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint,
          perspiciatis!
        </p>
        <a href="<?php if($user_id == ''){echo 'login.php';}else{echo 'courses.php';}?>" class="btn">get started</a>
      </div>
    </div>
  </section>
  <!-- landing section end -->

  <!-- courses section start -->
  <section class="courses">
    <h1 class="heading">our courses</h1>
    <div class="box-container">
      <?php
      $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE status = ? ORDER BY date DESC LIMIT 6");
      $select_playlists->execute(["active"]);
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
    <div class="flex">
      <a href="courses.php" class="btn option-btn inline-btn">view all courses</a>
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