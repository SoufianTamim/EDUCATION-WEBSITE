<?php
include 'components/connection.php';
if(isset($_GET['id'])){
  $course_id = $_GET['id'];
}else{
  $warning_msg[] = "Something went wrong!";
  $course_id = '';
  header('location:home.php');
}

// add comment
if(isset($_POST['add_comment'])){
  if($user_id != ''){
    $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
    $select_comment = $conn->prepare("SELECT * FROM comments WHERE content_id = ? AND comment = ?");
    $select_comment->execute([$course_id, $comment]);
    if($select_comment->rowCount()>0){
      $warning_msg[] = "Your comment already added!";
    }else{
      $comment_id = create_unique_id();
      $insert_comment = $conn->prepare("INSERT INTO comments (id, content_id, user_id, comment) VALUES (?, ?, ?, ?)");
      $insert_comment->execute([$comment_id, $course_id, $user_id, $comment]);
      $success_msg[] = "Your comment added successfully!";
    }
  }else{
    $warning_msg[] = 'Please login first!';
  }
}

//delete comment
if(isset($_POST['delete_comment'])){
  if($user_id != ''){
    $comment_id = $_POST['comment_id'];
    $select_comment = $conn->prepare("SELECT * FROM comments WHERE id = ? AND content_id = ?");
    $select_comment->execute([$comment_id, $course_id]);
    if($select_comment->rowCount()>0){      
      $delete_comment = $conn->prepare("DELETE FROM comments WHERE id = ? AND content_id = ?");
      $delete_comment->execute([$comment_id, $course_id]);
      $success_msg[] = "Your comment deleted successfully!";
    }else{
      $warning_msg[] = "Your comment not exist!";
    }
  }else{
    $warning_msg[] = 'Please login first!';
  }
}

//update comment
if(isset($_POST['update_comment'])){
  if($user_id != ''){
    $comment_id = $_POST['comment_id_to_update'];
    $select_comment = $conn->prepare("SELECT * FROM comments WHERE id = ? AND content_id = ? LIMIT 1");
    $select_comment->execute([$comment_id, $course_id]);
    if($select_comment->rowCount()>0){  
      $comment_to_update = filter_var($_POST['comment_to_update'], FILTER_SANITIZE_STRING);
      $update_comment = $conn->prepare("UPDATE comments SET comment = ? WHERE id = ? AND content_id = ?");
      $update_comment->execute([$comment_to_update, $comment_id, $course_id]);
      $success_msg[] = "Your comment updated successfully!";
    }else{
      $warning_msg[] = "Your comment not exist!";
    }
  }else{
    $warning_msg[] = 'Please login first!';
  }
}

//like course
if(isset($_POST['like'])){
  if($user_id != ''){
    $select_like = $conn->prepare("SELECT * FROM likes WHERE  user_id = ? AND content_id = ? LIMIT 1");
    $select_like->execute([$user_id, $course_id]);
    if($select_like->rowCount()>0){
      $delete_like = $conn->prepare("DELETE FROM likes WHERE user_id = ? AND content_id = ?");
      $delete_like->execute([$user_id, $course_id]);
    }else{
      $insert_like = $conn->prepare("INSERT INTO likes(user_id, content_id) VALUES (?, ?)");
      $insert_like->execute([$user_id, $course_id]);
    }
  }else{
    $warning_msg[] = 'Please loign first!';
  }
}
//select no of likes for course
$select_likes = $conn->prepare("SELECT * FROM likes WHERE content_id = ?");
$select_likes->execute([$course_id]);
$no_of_likes = $select_likes->rowCount();
//select if user like or not course
if($user_id != ''){
  $select_user_like = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND content_id = ? LIMIT 1");
  $select_user_like->execute([$user_id, $course_id]);
  $user_like = $select_user_like->rowCount();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>watch</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- watch-video section start -->
  <section class="watch-video">
    <!-- update box start -->
    <?php 
      if(isset($_POST['edit_comment'])){
        $comment_id = $_POST['comment_id'];
        $select_comment_to_update = $conn->prepare("SELECT * FROM comments WHERE id = ? AND content_id = ? LIMIT 1");
        $select_comment_to_update->execute([$comment_id, $course_id]);
        $fetch_comment_to_update = $select_comment_to_update->fetch(PDO::FETCH_OBJ);
      ?>
    <div class="comments" style="margin-bottom: 2rem;">
      <form action="" method="post" class="form">
        <h3>edit a comment</h3>
        <input type="hidden" name="comment_id_to_update" value="<?=$fetch_comment_to_update->id?>">
        <textarea name="comment_to_update" rows="10" placeholder="<?=$fetch_comment_to_update->comment?>"
          class="input-box"><?=$fetch_comment_to_update->comment?></textarea>
        <button type="submit" name="update_comment" class="btn inline-btn">update comment</button>
      </form>
    </div>
    <?php }?>
    <!-- update box end -->

    <!-- video details box start -->
    <div class="row">
      <?php
      $select_course = $conn->prepare("SELECT * FROM content WHERE id = ? AND status = ? LIMIT 1");
      $select_course->execute([$course_id, "active"]);
      if($select_course->rowCount()>0){
        $fetch_course = $select_course->fetch(PDO::FETCH_OBJ);
        $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
        $select_teacher->execute([$fetch_course->teacher_id]);
        $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
        ?>
      <div class="video-container">
        <video src="images/uploaded_images/courses/videos/<?=$fetch_course->video?>"
          poster="images/uploaded_images/courses/posts/<?=$fetch_course->thumb?>" controls class="video"></video>
        <div class="video-info">
          <h3><?=$fetch_course->title?></h3>
          <div class="flex">
            <p><i class="fas fa-calendar"></i>
              <?=$fetch_course->date?>
            </p>
            <p><i class="fas fa-heart"></i> <?=$no_of_likes?> likes</p>
          </div>
        </div>
      </div>
      <div class="video-owner">
        <a href="teacher_profile.php?id=<?=$fetch_teacher->id?>" class="flex">
          <?php if($fetch_teacher->image != '' ){?>
          <img src="images/uploaded_images/teachers/<?=$fetch_teacher->image?>" alt="teacher-image" />
          <?php }else{?>
          <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
          <?php }?>
          <div>
            <h3><?=$fetch_teacher->name?></h3>
            <p><?=$fetch_teacher->profession?></p>
          </div>
        </a>
        <div class="flex justify-between">
          <?php if($fetch_course->playlist_id != ''){?>
          <a href="playlist.php?id=<?=$fetch_course->playlist_id?>" class="btn inline-btn">view playlist</a>
          <?php }?>
          <form action="" method="post">
            <button type="submit" name="like"
              class="btn inline-btn like-btn <?php if($user_id != '' && $user_like > 0){echo 'active';}else{echo '';}?>">
              <i class="fas fa-heart"></i> <?php if($user_id != '' && $user_like > 0){echo 'liked';}else{echo 'like';}?>
            </button>
          </form>
        </div>
        <p><?=$fetch_course->description?> </p>
      </div>
      <?php
      }else{
        echo "<p class='error'>There is no course found!</p>";
      }
      ?>
    </div>
  </section>
  <!-- watch-video section end -->

  <!-- comments section start -->
  <section class="comments">
    <?php
      $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
      $select_comments->execute([$course_id]);
      $no_of_comments = $select_comments->rowCount();
    ?>
    <h1 class="heading"><?=$no_of_comments?> comments</h1>
    <?php
      if($user_id != ''){?>
    <form action="" method="post" class="form">
      <h3>add a comment</h3>
      <textarea name="comment" rows="10" placeholder="enter your comment" class="input-box"></textarea>
      <button type="submit" name="add_comment" class="btn inline-btn">add comment</button>
    </form>
    <?php  }else{
        echo "<p class='error'>Please login to add a comment!</p>";
      } ?>
  </section>
  <!-- comments section end -->

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
      <div class="comment-box <?php if($fetch_comment->user_id == $user_id){echo 'in-front';} ?>">
        <div class="flex">
          <?php if($fetch_user->image != '' ){?>
          <img src="images/uploaded_images/users/<?=$fetch_user->image?>" alt="user-image" />
          <?php }else{?>
          <p class="alt-img"><?= $fetch_user->name[0]?></p>
          <?php }?>
          <div>
            <h3><?=$fetch_user->name?></h3>
            <p><?=$fetch_comment->date?></p>
          </div>
        </div>
        <div class="comment-text"><?=$fetch_comment->comment?></div>
        <?php if($fetch_comment->user_id == $user_id){?>
        <form action="" method="post" class="form">
          <input type="hidden" name="comment_id" value="<?=$fetch_comment->id?>">
          <button type="submit" name="edit_comment" class="btn inline-btn option-btn">
            edit comment
          </button>
          <button type="submit" name="delete_comment" class="btn inline-btn delete-btn"
            onclick="return confirm('Sure you want to delete this comment!')">
            delete comment
          </button>
        </form>
        <?php }?>
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
  <script src="js/script.js"></script>
  <?php include 'components/alert.php';?>
</body>

</html>