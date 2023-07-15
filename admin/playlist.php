<?php
include '../components/connection.php';
if($admin_id == ''){
  header('location: login.php');
}
if(isset($_GET['id'])){
  $playlist_id = $_GET['id'];
}else{
  $warning_msg[] = "Something went wrong!";
  $playlist_id = '';
  header('location:playlists.php');
}

//edit playlist
  if(isset($_POST['update_playlist'])){
    $select_playlist_to_update = $conn->prepare("SELECT * FROM playlist WHERE id = ? AND teacher_id = ? LIMIT 1");
    $select_playlist_to_update->execute([$playlist_id, $admin_id]);
    if($select_playlist_to_update->rowCount() > 0){
      $fetch_playlist_to_update = $select_playlist_to_update->fetch(PDO::FETCH_OBJ);
      $prev_title = $fetch_playlist_to_update->title;
      $prev_description = $fetch_playlist_to_update->description;
      $prev_thumb = $fetch_playlist_to_update->thumb;
      $prev_status = $fetch_playlist_to_update->status;
  
      //update playlist status
      $playlist_status_to_update = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
      if(!empty($playlist_status_to_update) && $playlist_status_to_update != $prev_description){
        $update_playlist_status = $conn->prepare("UPDATE playlist SET status = ? WHERE id = ? AND teacher_id = ?");
        $update_playlist_status->execute([$playlist_status_to_update, $playlist_id, $admin_id]);
        $success_msg[] = 'Status updated successfully!';
      }
      //update playlist title
      $playlist_title_to_update = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
      if(!empty($playlist_title_to_update) && $playlist_title_to_update != $prev_title){
        $update_playlist_title = $conn->prepare("UPDATE playlist SET title = ? WHERE id = ? AND teacher_id = ?");
        $update_playlist_title->execute([$playlist_title_to_update, $playlist_id, $admin_id]);
        $success_msg[] = 'Title updated successfully!';
      }
      //update playlist description
      $playlist_description_to_update = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
      if(!empty($playlist_description_to_update) && $playlist_description_to_update != $prev_description){
        $update_playlist_description = $conn->prepare("UPDATE playlist SET description = ? WHERE id = ? AND teacher_id = ?");
        $update_playlist_description->execute([$playlist_description_to_update, $playlist_id, $admin_id]);
        $success_msg[] = 'Description updated successfully!';
      }
      //update playlist thumb
      $playlist_thumb_to_update = filter_var($_FILES['thumb']['name'], FILTER_SANITIZE_STRING);
      $playlist_thumb_size_to_update = $_FILES['thumb']['size'];
      $playlist_thumb_tmp_name_to_update = $_FILES['thumb']['tmp_name'];
      $playlist_thumb_ext_to_update = pathinfo($playlist_thumb_to_update, PATHINFO_EXTENSION);
      $playlist_thumb_rename = create_unique_id().'.'.$playlist_thumb_ext_to_update;
      $playlist_thumb_folder = '../images/uploaded_images/courses/thumbs/'.$playlist_thumb_rename;
      if(!empty($playlist_thumb_to_update)){
        if($playlist_thumb_size_to_update > 2000000){
          $warning_msg[] = 'Thumb is too large!';
        }else{
          $update_playlist_thumb = $conn->prepare("UPDATE playlist SET thumb = ? WHERE id = ? AND teacher_id = ?");
          $update_playlist_thumb->execute([$playlist_thumb_rename, $playlist_id, $admin_id]);
          move_uploaded_file($playlist_thumb_tmp_name_to_update, $playlist_thumb_folder);
          if($prev_thumb != '' AND $prev_thumb != $playlist_thumb_rename){
            unlink('../images/uploaded_images/courses/thumbs/'.$prev_thumb);
          }
          $success_msg[] = "Thumb updated successfully!";
        }
      }
    }else{
      $warning_msg[] = 'Something went wrong!';
    }
  }
//cancle editing
if(isset($_POST['cancle_editing'])){
  header("location:playlist.php?id=".$playlist_id);
}
//delete playlist
if(isset($_GET['delete'])){
  $select_playlist_to_delete = $conn->prepare("SELECT * FROM playlist WHERE id = ? AND teacher_id = ? LIMIT 1");
  $select_playlist_to_delete->execute([$playlist_id, $admin_id]);
  $fetch_playlist_to_delete = $select_playlist_to_delete->fetch(PDO::FETCH_OBJ);
  if($select_playlist_to_delete->rowCount() > 0){
    //select courses related to the playlist
    $select_courses = $conn->prepare("SELECT * FROM content WHERE playlist_id = ? AND teacher_id = ?");
    $select_courses->execute([$playlist_id, $admin_id]);
    if($select_courses->rowCount()>0){
      while($fetch_courses = $select_courses->fetch(PDO::FETCH_OBJ)){
        //select comments realated to playlist
        $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
        $select_comments->execute([$fetch_courses->id]);
        if($select_comments->rowCount()>0){
          //delete comments realated to playlist
          $delete_comments = $conn->prepare("DELETE FROM comments WHERE content_id = ?");
          $delete_comments->execute([$fetch_courses->id]);
        }
        //select likes realated to playlist
        $select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
        $select_likes->execute([$fetch_courses->id]);
        if($select_likes->rowCount()>0){
          //delete likes realated to playlist
          $delete_likes = $conn->prepare("DELETE FROM likes WHERE content_id = ?");
          $delete_likes->execute([$fetch_courses->id]);
        }
      }
      //delete courses realated to playlist
      $delete_courses = $conn->prepare("DELETE FROM content WHERE playlist_id = ? AND teacher_id = ?");
      $delete_courses->execute([$playlist_id, $admin_id]);
    }
    //select bookmarks realated to playlist
    $select_bookmark = $conn->prepare("SELECT * FROM bookmark WHERE playlist_id = ?");
    $select_bookmark->execute([$playlist_id]);
    if($select_bookmark->rowCount()>0){
      //delete bookmarks realated to playlist
      $delete_bookmark = $conn->prepare("DELETE FROM bookmark WHERE playlist_id = ?");
      $delete_bookmark->execute([$playlist_id]);
    }
    //delete playlist
    $delete_playlist = $conn->prepare("DELETE FROM playlist WHERE id = ? AND teacher_id = ?");
    $delete_playlist->execute([$playlist_id, $admin_id]);
    unlink('../images/uploaded_images/courses/thumbs/'.$fetch_playlist_to_delete->thumb);
    $success_msg[] = 'Playlist deleted successfully!';
  }else{
    $warning_msg[] = 'Playlist not exist!';
  }
  header('location:playlists.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin | playlist</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
  <!-- custom css file link  -->
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/admin_style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include '../components/admin_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- update playlist section start -->
  <?php 
  if(isset($_GET['edit'])){ 
    $select_playlist_to_edit = $conn->prepare("SELECT * FROM playlist WHERE id = ? AND teacher_id = ? LIMIT 1");
    $select_playlist_to_edit->execute([$playlist_id, $admin_id]);
    if($select_playlist_to_edit->rowCount() > 0){
      $fetch_playlist_to_edit = $select_playlist_to_edit->fetch(PDO::FETCH_OBJ);
    ?>
  <section class="add-item group-form">
    <h1 class="heading">edit playlist</h1>
    <form method="post" class="form" enctype="multipart/form-data">
      <div class="form-flex">
        <div class="input-group">
          <label for="title">Playlist title<span>*</span></label>
          <input type="text" name="title" id="title" class="input-box" placeholder="enter playlist title"
            value="<?=$fetch_playlist_to_edit->title?>" />
          <label for="status">Playlist status<span>*</span></label>
          <select name="status" id="status" class="input-box">
            <option value='<?=$fetch_playlist_to_edit->status?>'><?=$fetch_playlist_to_edit->status?></option>
            <option value="active">active</option>
            <option value="deactive">deactive</option>
          </select> <label for="thumb">Playlist thumb<span>*</span></label>
          <input type="file" name="thumb" id="thumb" class="input-box" placeholder="enter playlist thumb"
            value="<?=$fetch_playlist_to_edit->thumb;?>" />
        </div>
        <div class="input-group">
          <label for="description">Playlist description<span>*</span></label>
          <textarea name="description" id="description" rows="10" class="input-box"
            placeholder="enter playlist description"><?=$fetch_playlist_to_edit->description;?></textarea>
        </div>
      </div>
      <button type="submit" name="update_playlist" class="btn inline-btn">update playlist</button>
      <button type="submit" name="cancle_editing" class="btn inline-btn delete-btn" style="margin-left: 1rem;">cancle
        edit</button>
    </form>
  </section>
  <?php     }}?>
  <!-- update playlist section end -->

  <!-- playlist-details section start -->
  <section class="playlist-details">
    <h1 class="heading">playlist details</h1>
    <?php 
  $select_playlist = $conn->prepare("SELECT * FROM playlist WHERE id = ? AND teacher_id = ? LIMIT 1");
  $select_playlist->execute([$playlist_id, $admin_id]);
    if($select_playlist->rowCount()>0){
      $fetch_playlist = $select_playlist->fetch(PDO::FETCH_OBJ);
      //select no of courses of the playlist
      $select_courses = $conn->prepare("SELECT * FROM content WHERE playlist_id = ? AND teacher_id = ?");
      $select_courses->execute([$playlist_id, $admin_id]);
      $no_of_courses = $select_courses->rowCount();
      ?>
    <div class="row">
      <div class="columns">
        <div class="image column">
          <span><?=$no_of_courses?> videos</span>
          <img src="../images/uploaded_images/courses/thumbs/<?=$fetch_playlist->thumb?>" alt="playlist-image" />
        </div>
        <div class="info column">
          <div class="flex-between">
            <span class="status"
              style="<?php if($fetch_playlist->status == "active"){echo 'color:green !important;';}else{echo 'color:red !important;';}?>"><i
                style="<?php if($fetch_playlist->status == "active"){echo 'background:green !important';}else{echo 'background: red !important';}?>"
                class="fas fa-star"></i> <?=$fetch_playlist->status?></span>
            <span class="date"><i class="fas fa-calendar"></i> <?=$fetch_playlist->date?></span>
          </div>
          <h2><?=$fetch_playlist->title?></h2>
          <p><?=$fetch_playlist->description?></p>
          <form action="" method="post">
            <a href="playlist.php?id=<?=$playlist_id?>&edit" name="edit_playlist"
              class="btn inline-btn option-btn">update playlist</a>
            <a href="playlist.php?id=<?=$playlist_id?>&delete" class="btn inline-btn delete-btn"
              onclick="return confirm('Sure you want to delete this playlist and its courses?')">delete playlist</a><br>
            <button type="submit" name="add_course" class="btn inline-btn">add courses</button>
          </form>
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
      <a href="course.php?id=<?=$fetch_course->id?>" class="box">
        <div class="image-container">
          <i class="fas fa-play video-layout"></i>
          <img src="../images/uploaded_images/courses/posts/<?=$fetch_course->thumb?>" alt="video-image" />
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
  <script src="../js/script.js"></script>
  <?php include '../components/alert.php';?>
</body>

</html>