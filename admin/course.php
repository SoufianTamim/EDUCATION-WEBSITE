<?php
include '../components/connection.php';
if(isset($_GET['id'])){
  $course_id = $_GET['id'];
}else{
  $warning_msg[] = "Something went wrong!";
  $course_id = '';
  header('location:home.php');
}

//edit course
if(isset($_POST['update_course'])){
  $select_course_to_update = $conn->prepare("SELECT * FROM content WHERE id = ? AND teacher_id = ? LIMIT 1");
  $select_course_to_update->execute([$course_id, $admin_id]);
  if($select_course_to_update->rowCount() > 0){
    $fetch_course_to_update = $select_course_to_update->fetch(PDO::FETCH_OBJ);
    $prev_title = $fetch_course_to_update->title;
    $prev_description = $fetch_course_to_update->description;
    $prev_thumb = $fetch_course_to_update->thumb;
    $prev_status = $fetch_course_to_update->status;

    //update course status
    $course_status_to_update = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
    if(!empty($course_status_to_update) && $course_status_to_update != $prev_description){
      $update_course_status = $conn->prepare("UPDATE content SET status = ? WHERE id = ? AND teacher_id = ?");
      $update_course_status->execute([$course_status_to_update, $course_id, $admin_id]);
      $success_msg[] = 'Status updated successfully!';
    }
    //update course title
    $course_title_to_update = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    if(!empty($course_title_to_update) && $course_title_to_update != $prev_title){
      $update_course_title = $conn->prepare("UPDATE content SET title = ? WHERE id = ? AND teacher_id = ?");
      $update_course_title->execute([$course_title_to_update, $course_id, $admin_id]);
      $success_msg[] = 'Title updated successfully!';
    }
    //update course description
    $course_description_to_update = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    if(!empty($course_description_to_update) && $course_description_to_update != $prev_description){
      $update_course_description = $conn->prepare("UPDATE content SET description = ? WHERE id = ? AND teacher_id = ?");
      $update_course_description->execute([$course_description_to_update, $course_id, $admin_id]);
      $success_msg[] = 'Description updated successfully!';
    }
    //update course thumb
    $course_thumb_to_update = filter_var($_FILES['thumb']['name'], FILTER_SANITIZE_STRING);
    $course_thumb_size_to_update = $_FILES['thumb']['size'];
    $course_thumb_tmp_name_to_update = $_FILES['thumb']['tmp_name'];
    $course_thumb_ext_to_update = pathinfo($course_thumb_to_update, PATHINFO_EXTENSION);
    $course_thumb_rename = create_unique_id().'.'.$course_thumb_ext_to_update;
    $course_thumb_folder = '../images/uploaded_images/courses/posts/'.$course_thumb_rename;
    if(!empty($course_thumb_to_update)){
      if($course_thumb_size_to_update > 2000000){
        $warning_msg[] = 'Thumb is too large!';
      }else{
        $update_course_thumb = $conn->prepare("UPDATE content SET thumb = ? WHERE id = ? AND teacher_id = ?");
        $update_course_thumb->execute([$course_thumb_rename, $course_id, $admin_id]);
        move_uploaded_file($course_thumb_tmp_name_to_update, $course_thumb_folder);
        if($prev_thumb != '' AND $prev_thumb != $course_thumb_rename){
          unlink('../images/uploaded_images/courses/posts/'.$prev_thumb);
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
header("location:course.php?id=".$course_id);
}

//delete course
if(isset($_GET['delete'])){
  $select_course_to_delete = $conn->prepare("SELECT * FROM content WHERE id = ? AND teacher_id = ? LIMIT 1");
  $select_course_to_delete->execute([$course_id, $admin_id]);
  $fetch_course_to_delete = $select_course_to_delete->fetch(PDO::FETCH_OBJ);
  if($select_course_to_delete->rowCount() > 0){
    //select comments realated to course
    $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
    $select_comments->execute([$course_id]);
    if($select_comments->rowCount()>0){
      //delete comments realated to course
      $delete_comments = $conn->prepare("DELETE FROM comments WHERE content_id = ?");
      $delete_comments->execute([$course_id]);
    }
    //select likes realated to course
    $select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
    $select_likes->execute([$course_id]);
    if($select_likes->rowCount()>0){
      //delete likes realated to course
      $delete_likes = $conn->prepare("DELETE FROM likes WHERE content_id = ?");
      $delete_likes->execute([$course_id]);
    }
    //delete course
    $delete_course = $conn->prepare("DELETE FROM content WHERE id = ? AND teacher_id = ?");
    $delete_course->execute([$course_id, $admin_id]);
    unlink('../images/uploaded_images/courses/posts/'.$fetch_course_to_delete->thumb);
    $success_msg[] = 'Course deleted successfully!';
  }else{
    $warning_msg[] = 'Course not exist!';
  }
  header('location:courses.php');
}
//select no of likes for course
$select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
$select_likes->execute([$course_id]);
$no_of_likes = $select_likes->rowCount();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin | course</title>

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

  <!-- update course section start -->
  <?php 
  if(isset($_GET['edit'])){ 
    $select_course_to_edit = $conn->prepare("SELECT * FROM content WHERE id = ? AND teacher_id = ? LIMIT 1");
    $select_course_to_edit->execute([$course_id, $admin_id]);
    if($select_course_to_edit->rowCount() > 0){
      $fetch_course_to_edit = $select_course_to_edit->fetch(PDO::FETCH_OBJ);
    ?>
  <section class="add-item group-form">
    <h1 class="heading">edit course</h1>
    <form method="post" class="form" enctype="multipart/form-data">
      <div class="form-flex">
        <div class="input-group">
          <label for="title">course title<span>*</span></label>
          <input type="text" name="title" id="title" class="input-box" placeholder="enter course title"
            value="<?=$fetch_course_to_edit->title?>" />
          <label for="status">course status<span>*</span></label>
          <select name="status" id="status" class="input-box">
            <option value='<?=$fetch_course_to_edit->status?>'><?=$fetch_course_to_edit->status?></option>
            <option value="active">active</option>
            <option value="deactive">deactive</option>
          </select> <label for="thumb">course thumb<span>*</span></label>
          <input type="file" name="thumb" id="thumb" class="input-box" placeholder="enter course thumb"
            value="<?=$fetch_course_to_edit->thumb;?>" />
        </div>
        <div class="input-group">
          <label for="description">course description<span>*</span></label>
          <textarea name="description" id="description" rows="10" class="input-box"
            placeholder="enter course description"><?=$fetch_course_to_edit->description;?></textarea>
        </div>
      </div>
      <button type="submit" name="update_course" class="btn inline-btn">update course</button>
      <button type="submit" name="cancle_editing" class="btn inline-btn delete-btn" style="margin-left: 1rem;">cancle
        edit</button>
    </form>
  </section>
  <?php     }}?>
  <!-- update course section end -->


  <!-- watch-video section start -->
  <section class="watch-video">
    <!-- video details box start -->
    <div class="row">
      <?php
      $select_course = $conn->prepare("SELECT * FROM content WHERE id = ? LIMIT 1");
      $select_course->execute([$course_id]);
      if($select_course->rowCount()>0){
        $fetch_course = $select_course->fetch(PDO::FETCH_OBJ);
        $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
        $select_teacher->execute([$fetch_course->teacher_id]);
        $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
        ?>
      <div class="video-container">
        <video src="../images/uploaded_images/courses/videos/<?=$fetch_course->video?>"
          poster="../images/uploaded_images/courses/posts/<?=$fetch_course->thumb?>" controls class="video"></video>
        <div class="video-info">
          <h3><?=$fetch_course->title?></h3>
          <div class="flex flex-between">
            <p><i class="fas fa-calendar"></i>
              <?=$fetch_course->date?>
            </p>
            <p><i class="fas fa-heart"></i> <?=$no_of_likes?> likes</p>
          </div>
          <p><?=$fetch_course->description?> </p>
        </div>
      </div>
      <div class="video-owner">
        <div class="flex" style="gap:0.5rem">
          <?php if($fetch_course->playlist_id != ''){  ?>
          <a href="playlist.php?id=<?=$fetch_course->playlist_id?>" class="btn inline-btn">view playlist</a>
          <?php  }?>
          <a href="course.php?id=<?=$fetch_course->id?>&edit" class="btn inline-btn option-btn">edit video</a>
          <a href="course.php?id=<?=$fetch_course->id?>&delete" class="btn inline-btn delete-btn"
            onclick="return confirm('Sure you want to delete this video?')">delete video</a>
        </div>
      </div>
      <?php
      }else{
        echo "<p class='error'>There is no course found!</p>";
      }
      ?>
    </div>
  </section>
  <!-- watch-video section end -->

  <!-- user comments section start -->
  <section class="comments user-comments">
    <h1 class="heading">user comments</h1>
    <div class="comment-container">
      <?php
        $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
        $select_comments->execute([$course_id]);
        if($select_comments->rowCount()>0){
        while($fetch_comment = $select_comments->fetch(PDO::FETCH_OBJ)){
          $select_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
          $select_user->execute([$fetch_comment->user_id]);
          $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
          ?>
      <div class="comment-box">
        <div class="flex">
          <?php if($fetch_user->image != '' ){?>
          <img src="../images/uploaded_images/users/<?=$fetch_user->image?>" alt="user-image" />
          <?php }else{?>
          <p class="alt-img"><?= $fetch_user->name[0]?></p>
          <?php }?>
          <div>
            <h3><?=$fetch_user->name?></h3>
            <p><?=$fetch_comment->date?></p>
          </div>
        </div>
        <div class="comment-text"><?=$fetch_comment->comment?></div>
      </div>
      <?php
        }          
      }else{
        echo "<p class='error'>There is no comments for this video!</p>";
      }
      ?>

    </div>
  </section>
  <!-- comments section end -->

  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="../js/script.js"></script>
  <?php include '../components/alert.php';?>
</body>

</html>