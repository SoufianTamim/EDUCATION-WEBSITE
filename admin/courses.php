<?php
include '../components/connection.php';

//add new course
if(isset($_POST['add_course'])){
  $course_id = create_unique_id();
  $course_title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $course_description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
  $course_status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
  $course_playlist_id = filter_var($_POST['playlist_id'], FILTER_SANITIZE_STRING);

  $course_thumb = $_FILES['thumb']['name'];
  $course_thumb_size = $_FILES['thumb']['size'];
  $course_thumb_tmp_name = $_FILES['thumb']['tmp_name'];
  $ext = pathinfo($course_thumb, PATHINFO_EXTENSION);
  $rename = create_unique_id().'.'.$ext;
  $thumb_folder = '../images/uploaded_images/courses/posts/'.$rename;

  $course_video = $_FILES['video']['name'];
  $course_video_size = $_FILES['video']['size'];
  $course_video_tmp_name = $_FILES['video']['tmp_name'];
  $video_ext = pathinfo($course_video, PATHINFO_EXTENSION);
  $video_rename = create_unique_id().'.'.$video_ext;
  $video_folder = '../images/uploaded_images/courses/videos/'.$video_rename;

  $select_course = $conn->prepare("SELECT * FROM content WHERE title = ? AND description = ? AND playlist_id = ? AND status = ? LIMIT 1");
  $select_course->execute([$course_title, $course_description, $course_playlist_id, $course_status]);
  if($select_course->rowCount() > 0){
    $warning_msg[] = 'course already exist!';
  }else if($course_thumb_size > 2000000){
    $warning_msg[] = "Thumb size is too large!";
  }else{
    if(empty($course_thumb)){
      $rename = "";
    }else if(empty($course_video)){
      $video_rename = "";
    }else{
      move_uploaded_file($course_thumb_tmp_name, $thumb_folder);
      move_uploaded_file($course_video_tmp_name, $video_folder);
    }
    $insert_course = $conn->prepare("INSERT INTO content (id,teacher_id,playlist_id,title,description,video,thumb,status) VALUES (?,?,?,?,?,?,?,?)");
    $insert_course->execute([$course_id, $admin_id, $course_playlist_id, $course_title, $course_description, $video_rename, $rename, $course_status]);
    $success_msg[] = 'course added successfully!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin | courses</title>

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

  <!-- courses section start -->
  <section class="add-item group-form">
    <h1 class="heading">add course</h1>
    <form method="post" class="form" enctype="multipart/form-data">
      <div class="form-flex">
        <div class="input-group">
          <label for="thumb">Course thumb<span>*</span></label>
          <input type="file" name="thumb" id="thumb" class="input-box" placeholder="enter course thumb" />
          <label for="title">Course title<span>*</span></label>
          <input type="text" name="title" id="title" class="input-box" placeholder="enter course title" required />
          <label for="status">Course status<span>*</span></label>
          <select name="status" id="status" class="input-box" required>
            <option value="active">active</option>
            <option value="deactive">deactive</option>
          </select>
          <?php  
          $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE teacher_id = ?");
          $select_playlists->execute([$admin_id]);
          if($select_playlists->rowCount() > 0){
            ?>
          <label for="playlist_id">Course playlist<span>*</span></label>
          <select name="playlist_id" id="playlist_id" class="input-box">
            <option value=""></option>
            <?php while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_OBJ)){?>
            <option value="<?=$fetch_playlist->id?>"><?=$fetch_playlist->title?></option>
            <?php
            }
            ?>
          </select>
          <?php
          }
          ?>
        </div>
        <div class="input-group">
          <label for="video">Course video<span>*</span></label>
          <input type="file" name="video" id="video" class="input-box" placeholder="enter course video" required />
          <label for="description">Course description<span>*</span></label>
          <textarea name="description" id="description" rows="10" class="input-box"
            placeholder="enter course description" required></textarea>
        </div>
      </div>
      <button type="submit" name="add_course" class="btn inline-btn">add new course</button>
    </form>
  </section>
  <section class="courses">
    <h1 class="heading">your courses</h1>
    <div class="box-container">
      <?php
        $select_courses = $conn->prepare("SELECT * FROM content WHERE teacher_id = ?");
        $select_courses->execute([$admin_id]);
        if($select_courses->rowCount() > 0){
          while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){
        ?>
      <div class="box">
        <p class="flex-between">
          <span class="status <?php if($fetch_course->status == "active")echo 'active';?>"
            style="<?php if($fetch_course->status == "active"){echo 'color:green !important';}else{echo 'color: red !important';}?>"><i
              style="<?php if($fetch_course->status == "active"){echo 'background:green !important';}else{echo 'background: red !important';}?>"
              class="fas fa-star"></i>
            <?=$fetch_course->status?></span>
          <span class="date"><i class="fas fa-calendar"></i> <?=$fetch_course->date?></span>
        </p>
        <div class="box-thumb">
          <img src="../images/uploaded_images/courses/posts/<?=$fetch_course->thumb?>" alt="course-thumb" />
        </div>
        <h3><?=$fetch_course->title?></h3>
        <div class="flex-btn">
          <a href="course.php?id=<?=$fetch_course->id?>&edit" class="btn option-btn">edit video</a>
          <a href="course.php?id=<?=$fetch_course->id?>&delete" class="btn delete-btn"
            onclick="return confirm('Sure you want to delete this video?')">delete video</a>
        </div>
        <a href="course.php?id=<?=$fetch_course->id?>" class="btn">view video</a>
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
  <script src="../js/script.js"></script>
  <?php include '../components/alert.php';?>
</body>

</html>