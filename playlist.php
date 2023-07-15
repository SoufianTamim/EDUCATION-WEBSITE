<?php
include 'components/connection.php';
if(isset($_GET['id'])){
  $playlist_id = $_GET['id'];
}else{
  $warning_msg[] = "Something went wrong!";
  $playlist_id = '';
  header('location:home.php');
}

//bookmark and save the playlist
if(isset($_POST['save_playlist'])){
  if($user_id != ''){
    $select_bookmark = $conn->prepare("SELECT * FROM bookmark WHERE user_id = ? AND playlist_id = ? LIMIT 1");
    $select_bookmark->execute([$user_id, $playlist_id]);
    if($select_bookmark->rowCount() > 0){
      $delete_bookmark = $conn->prepare("DELETE FROM bookmark WHERE user_id = ? AND playlist_id = ?");
      $delete_bookmark->execute([$user_id, $playlist_id]);
    }else{
      $insert_bookmark = $conn->prepare("INSERT INTO bookmark (user_id, playlist_id) VALUES (?, ?)");
      $insert_bookmark->execute([$user_id, $playlist_id]);
    }
  }else{
    $warning_msg[] = 'Please login first!';
  }
}
//select if user bookmark playlist or not
if($user_id != ''){
  $select_user_bookmark = $conn->prepare("SELECT * FROM bookmark WHERE user_id = ? AND playlist_id = ? LIMIT 1");
  $select_user_bookmark->execute([$user_id, $playlist_id]);
  $user_bookmark = $select_user_bookmark->rowCount();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>playlist</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- playlist-details section start -->
  <section class="playlist-details">
    <h1 class="heading">playlist details</h1>
    <?php 
  $select_playlist = $conn->prepare("SELECT * FROM playlist WHERE id = ? LIMIT 1");
  $select_playlist->execute([$playlist_id]);
    if($select_playlist->rowCount()>0){
      $fetch_playlist = $select_playlist->fetch(PDO::FETCH_OBJ);
      //select no of courses of the playlist
      $select_courses = $conn->prepare("SELECT * FROM content WHERE playlist_id = ?");
      $select_courses->execute([$playlist_id]);
      $no_of_courses = $select_courses->rowCount();
      //select teacher of the playlist
      $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
      $select_teacher->execute([$fetch_playlist->teacher_id]);
      $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
      ?>
    <div class="row">
      <form method="post" class="save">
        <button type="submit" name="save_playlist"
          class="<?php if($user_id != '' && $user_bookmark > 0){echo 'active';}else{echo '';}?>">
          <i
            class="far fa-bookmark"></i><span><?php if($user_id != '' && $user_bookmark > 0){echo 'playlist saved';}else{echo 'save playlist';}?></span>
        </button>
      </form>
      <div class="columns">
        <div class="image column">
          <span><?=$no_of_courses?> videos</span>
          <img src="images/uploaded_images/courses/thumbs/<?=$fetch_playlist->thumb?>" alt="playlist-image" />
        </div>
        <div class="info column">
          <a class="flex" href="teacher_profile.php?id=<?=$fetch_playlist->teacher_id?>">
            <?php if($fetch_teacher->image != '' ){?>
            <img src="images/uploaded_images/teachers/<?=$fetch_teacher->image?>" alt="playlist-owner" />
            <?php }else{?>
            <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
            <?php }?>
            <div>
              <h3><?=$fetch_teacher->name?></h3>
              <p><?=$fetch_teacher->profession?></p>
            </div>
          </a>
          <h2><?=$fetch_playlist->title?></h2>
          <p><?=$fetch_playlist->description?></p>
          <p><i class="fas fa-calendar"></i> <?=$fetch_playlist->date?></p>

        </div>
      </div>
    </div>
    <?php
    }else{
      echo "<p class='error'>There is no playlist found!</p>";
    }
  ?>
  </section>
  <!-- playlist-details section end -->

  <!-- playlist-videos section start -->
  <section class="playlist-videos">
    <h1 class="heading">playlist videos</h1>
    <div class="box-container">
      <?php
    if($select_courses->rowCount()>0){
      while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){?>
      <a href="watch_video.php?id=<?=$fetch_course->id?>" class="box">
        <div class="image-container">
          <i class="fas fa-play video-layout"></i>
          <img src="images/uploaded_images/courses/posts/<?=$fetch_course->thumb?>" alt="video-image" />
          <!-- <h1>#01</h1> -->
        </div>
        <h3><?=$fetch_course->title?></h3>
      </a>
      <?php
      }
    }else{
      echo "<p class='error'>There is no courses added yet!</p>";
    }?>
    </div>
  </section>
  <!-- playlist-videos section end -->


  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>