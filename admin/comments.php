<?php
include '../components/connection.php';
if($admin_id == ''){
  header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin | comments</title>

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

  <section class="comments user-comments">
    <h1 class="heading">user comments</h1>
    <div class="comment-container">
      <?php
        $select_courses = $conn->prepare("SELECT * FROM content WHERE teacher_id = ?");
        $select_courses->execute([$admin_id]);
        if($select_courses->rowCount() > 0){
          while($fetch_course = $select_courses->fetch(PDO::FETCH_OBJ)){
            $select_comments = $conn->prepare("SELECT * FROM comments WHERE content_id = ?");
            $select_comments->execute([$fetch_course->id]);
            if($select_comments->rowCount()>0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_OBJ)){
              $select_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
              $select_user->execute([$fetch_comment->user_id]);
              $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
                ?>
      <div class="comment-box">
        <div class="flex">
          <p><?=$fetch_course->title?></p> - <p><a href="course.php?id=<?=$fetch_comment->content_id?>">view course</a>
          </p>
        </div>
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
      }
    }
    }else{
      echo "<p class='error'>There is no comments for you</p>";
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