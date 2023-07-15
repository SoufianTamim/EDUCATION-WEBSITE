<?php
include 'components/connection.php';

//delete comment
if(isset($_POST['delete_comment'])){
  if($user_id != ''){
    $comment_id = $_POST['comment_id'];
    $content_id = $_POST['content_id'];
    $select_comment = $conn->prepare("SELECT * FROM comments WHERE id = ? AND content_id = ?");
    $select_comment->execute([$comment_id, $content_id]);
    if($select_comment->rowCount()>0){      
      $delete_comment = $conn->prepare("DELETE FROM comments WHERE id = ? AND content_id = ?");
      $delete_comment->execute([$comment_id, $content_id]);
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
    $content_id = $_POST['content_id_to_update'];
    $select_comment = $conn->prepare("SELECT * FROM comments WHERE id = ? AND content_id = ? LIMIT 1");
    $select_comment->execute([$comment_id, $content_id]);
    $fetch_comment = $select_comment->fetch(PDO::FETCH_OBJ);
    if($select_comment->rowCount()>0){  
      $comment_to_update = filter_var($_POST['comment_to_update'], FILTER_SANITIZE_STRING);
      if($fetch_comment->comment != $comment_to_update){
        $update_comment = $conn->prepare("UPDATE comments SET comment = ? WHERE id = ? AND content_id = ?");
        $update_comment->execute([$comment_to_update, $comment_id, $content_id]);
        $success_msg[] = "Your comment updated successfully!";
      }
    }else{
      $warning_msg[] = "Your comment not exist!";
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
  <title>comments</title>

  <!-- font awesome cdn link  -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

  <!-- custom css file link  -->
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <!-- header & sidebar section start -->
  <?php include 'components/user_header.php' ?>
  <!-- header & sidbar section end -->

  <!-- update box start -->
  <section>
    <?php 
        if(isset($_POST['edit_comment'])){
          $comment_id = $_POST['comment_id'];
          $content_id = $_POST['content_id'];
          $select_comment_to_update = $conn->prepare("SELECT * FROM comments WHERE id = ? AND content_id = ? LIMIT 1");
          $select_comment_to_update->execute([$comment_id, $content_id]);
          $fetch_comment_to_update = $select_comment_to_update->fetch(PDO::FETCH_OBJ);
        ?>
    <div class="comments" style="margin-bottom: 2rem;">
      <form action="" method="post" class="form">
        <h3>edit a comment</h3>
        <input type="hidden" name="comment_id_to_update" value="<?=$fetch_comment_to_update->id?>">
        <input type="hidden" name="content_id_to_update" value="<?=$fetch_comment_to_update->content_id?>">
        <textarea name="comment_to_update" rows="10" placeholder="<?=$fetch_comment_to_update->comment?>"
          class="input-box"><?=$fetch_comment_to_update->comment?></textarea>
        <button type="submit" name="update_comment" class="btn inline-btn">update comment</button>
      </form>
    </div>
    <?php }?>
  </section>
  <!-- update box end -->

  <!-- comments section start -->
  <section class="comments user-comments">
    <h1 class="heading">your comments</h1>
    <div class="comment-container">
      <?php
        $select_comments = $conn->prepare("SELECT * FROM comments WHERE user_id = ?");
        $select_comments->execute([$user_id]);
        if($select_comments->rowCount()>0){
        while($fetch_comment = $select_comments->fetch(PDO::FETCH_OBJ)){
          $select_course = $conn->prepare("SELECT * FROM content WHERE id = ? LIMIT 1");
          $select_course->execute([$fetch_comment->content_id]);
          $fetch_course = $select_course->fetch(PDO::FETCH_OBJ);
          ?>
      <div class="comment-box">
        <div class="flex">
          <p><?=$fetch_comment->date?></p> - <p><?=$fetch_course->title?></p> - <p><a
              href="watch_video.php?id=<?=$fetch_comment->content_id?>">view course</a></p>
        </div>
        <div class="comment-text"><?=$fetch_comment->comment?></div>
        <form action="" method="post" class="form">
          <input type="hidden" name="comment_id" value="<?=$fetch_comment->id?>">
          <input type="hidden" name="content_id" value="<?=$fetch_comment->content_id?>">
          <button type="submit" name="edit_comment" class="btn inline-btn option-btn">
            edit comment
          </button>
          <button type="submit" name="delete_comment" class="btn inline-btn delete-btn"
            onclick="return confirm('Sure you want to delete this comment!')">
            delete comment
          </button>
        </form>
      </div>
      <?php
        }          
      }else{
        echo "<p class='error'>There is no comments to show!</p>";
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