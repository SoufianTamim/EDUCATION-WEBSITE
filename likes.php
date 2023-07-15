<?php
include 'components/connection.php';

// like
if(isset($_POST['remove_like'])){
  if($user_id != ''){
    $content_id = $_POST['course_id'];
    $select_like = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND content_id = ? LIMIT 1");
    $select_like->execute([$user_id, $content_id]);
    if($select_like->rowCount()>0){      
      $delete_like = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND content_id = ?");
      $delete_like->execute([$user_id, $content_id]);
      $success_msg[] = "Removed from likes successfully!";
    }else{
      $warning_msg[] = "Video not exist!";
    }
  }else{
    $warning_msg[] = 'Please login first!';
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>likes</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- likes section start -->
  <section class="courses">
    <h1 class="heading">liked videos</h1>
    <div class="box-container">
      <?php
        $select_likes = $conn->prepare("SELECT * FROM likes WHERE user_id = ?");
        $select_likes->execute([$user_id]);
      if($select_likes->rowCount()>0){
        while($fetch_like = $select_likes->fetch(PDO::FETCH_OBJ)){
          //select courses that like
          $select_course = $conn->prepare("SELECT * FROM content WHERE id = ? LIMIT 1");
          $select_course->execute([$fetch_like->content_id]);
          $fetch_course = $select_course->fetch(PDO::FETCH_OBJ);
          //select teacher of the courses
          $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
          $select_teacher->execute([$fetch_course->teacher_id]);
          $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
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
            <p><?=$fetch_course->date?></p>
          </div>
        </a>
        <div class="box-thumb">
          <img src="images/uploaded_images/courses/posts/<?=$fetch_course->thumb?>" alt="course-thumb" />
        </div>
        <h3><?=$fetch_course->title?></h3>
        <form action="" method="post">
          <a href="watch_video.php?id=<?=$fetch_course->id?>" class="btn">view video</a>
          <input type="hidden" name="course_id" value="<?=$fetch_like->content_id?>">
          <button type="submit" name="remove_like" class="btn delete-btn"
            onclick="return confirm('Sure you want to remove from likes?')">remove from likes</button>
        </form>
      </div>
      <?php }
      }else{
        echo "<p class='error'>No courses liked yet!</p>";
      }
      ?>
    </div>
  </section>
  <!-- likes section end -->


  <!-- sweetalert cdn link -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
  <!-- custome js file link -->
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>