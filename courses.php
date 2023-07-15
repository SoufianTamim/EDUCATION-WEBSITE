<?php
include 'components/connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>courses</title>

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
    <h1 class="heading">our courses</h1>
    <div class="box-container">
      <?php
      if(isset($_POST['search_btn']) or isset($_POST['search_value'])){
        $search_value = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);
        $select_courses = $conn->prepare("SELECT * FROM content WHERE status = ?  AND title LIKE '%{$search_value}%' ORDER BY date DESC");
        $select_courses->execute(["active"]);
      }else{
        $select_courses = $conn->prepare("SELECT * FROM content WHERE status = ?");
        $select_courses->execute(["active"]);
      }
        if($select_courses->rowCount() > 0){
          while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){
            //select teacher of the course
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
        <a href="watch_video.php?id=<?=$fetch_course->id?>" class="btn inline-btn">watch video</a>
        <?php  if($fetch_course->playlist_id != ''){?>
        <a href="playlist.php?id=<?=$fetch_course->playlist_id?>" class="btn inline-btn option-btn">view playlist</a>
        <?php }?>
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