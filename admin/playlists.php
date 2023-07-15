<?php
include '../components/connection.php';
if($admin_id == ''){
  header('location: login.php');
}
//add new playlist
if(isset($_POST['add_playlist'])){
  $playlist_id = create_unique_id();
  $playlist_title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
  $playlist_description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
  $playlist_status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

  $playlist_thumb = $_FILES['thumb']['name'];
  $playlist_thumb_size = $_FILES['thumb']['size'];
  $playlist_thumb_tmp_name = $_FILES['thumb']['tmp_name'];
  $ext = pathinfo($playlist_thumb, PATHINFO_EXTENSION);
  $rename = create_unique_id().'.'.$ext;
  $thumb_folder = '../images/uploaded_images/courses/thumbs/'.$rename;

  $select_playlist = $conn->prepare("SELECT * FROM playlist WHERE title = ? AND description = ? AND status = ? LIMIT 1");
  $select_playlist->execute([$playlist_title, $playlist_description, $playlist_status]);
  if($select_playlist->rowCount() > 0){
    $warning_msg[] = 'Playlist already exist!';
  }else if($playlist_thumb_size > 2000000){
    $warning_msg[] = "Thumb size is too large!";
  }else{
    if(empty($playlist_thumb)){
      $rename = "";
    }else{
      move_uploaded_file($playlist_thumb_tmp_name, $thumb_folder);
    }
    $insert_playlist = $conn->prepare("INSERT INTO playlist (id,teacher_id,title,description,thumb,status) VALUES (?,?,?,?,?,?)");
    $insert_playlist->execute([$playlist_id, $admin_id, $playlist_title, $playlist_description, $rename, $playlist_status]);
    $success_msg[] = 'Playlist added successfully!';
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>admin | playlists</title>

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
    <h1 class="heading">add playlist</h1>
    <form method="post" class="form" enctype="multipart/form-data">
      <div class="form-flex">
        <div class="input-group">
          <label for="title">Playlist title<span>*</span></label>
          <input type="text" name="title" id="title" class="input-box" placeholder="enter playlist title" required />
          <label for="status">Playlist status<span>*</span></label>
          <select name="status" id="status" class="input-box" required>
            <option value="active">active</option>
            <option value="deactive">deactive</option>
          </select>
          <label for="thumb">Playlist thumb<span>*</span></label>
          <input type="file" name="thumb" id="thumb" class="input-box" placeholder="enter playlist thumb" />
        </div>
        <div class="input-group">
          <label for="description">Playlist description<span>*</span></label>
          <textarea name="description" id="description" rows="10" class="input-box"
            placeholder="enter playlist description" required></textarea>
        </div>
      </div>
      <button type="submit" name="add_playlist" class="btn inline-btn">add new playlist</button>
    </form>
  </section>
  <section class="courses admin-playlist">
    <h1 class="heading">your playlists</h1>
    <div class="box-container">
      <?php
      if(isset($_POST['search_btn']) or isset($_POST['search_value'])){
        $search_value = filter_var($_POST['search_value'], FILTER_SANITIZE_STRING);
        $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE teacher_id = ? title LIKE '%{$search_value}%' ORDER BY date DESC");
        $select_playlists->execute([$admin_id]);
      }else{
        $select_playlists = $conn->prepare("SELECT * FROM playlist WHERE teacher_id = ? ORDER BY date DESC");
        $select_playlists->execute([$admin_id]);
      }
      if($select_playlists->rowCount()>0){
        while($fetch_playlist = $select_playlists->fetch(PDO::FETCH_OBJ)){
          //select courses of the playlist
          $select_courses = $conn->prepare("SELECT * FROM content WHERE playlist_id = ? AND teacher_id = ?");
          $select_courses->execute([$fetch_playlist->id, $admin_id]);
          $no_of_courses = $select_courses->rowCount(); 
          ?>
      <div class="box">
        <p class="flex-between">
          <span class="status <?php if($fetch_playlist->status == "active")echo 'active';?>"
            style="<?php if($fetch_playlist->status == "active"){echo 'color:green !important';}else{echo 'color: red !important';}?>"><i
              style="<?php if($fetch_playlist->status == "active"){echo 'background:green !important';}else{echo 'background: red !important';}?>"
              class="fas fa-star"></i>
            <?=$fetch_playlist->status?></span>
          <span class="date"><i class="fas fa-calendar"></i> <?=$fetch_playlist->date?></span>
        </p>
        <div class="box-thumb">
          <img src="../images/uploaded_images/courses/thumbs/<?=$fetch_playlist->thumb?>" alt="course-thumb" />
          <span><?=$no_of_courses?> videos</span>
        </div>
        <h3><?=$fetch_playlist->title?></h3>
        <p><?=$fetch_playlist->description?></p>
        <form action="" method="POST" class="flex-btn">
          <input type="hidden" name="playlist_id" value="<?=$fetch_playlist->id?>">
          <a href="playlist.php?id=<?=$fetch_playlist->id?>&edit" class="btn option-btn inline-btn">edit</a>
          <a href="playlist.php?id=<?=$fetch_playlist->id?>&delete" class="btn delete-btn inline-btn"
            onclick="return confirm('Sure you want to delete this playlist and its courses?')">delete</a>
        </form>
        <a href="playlist.php?id=<?=$fetch_playlist->id?>" class="btn ">view playlist</a>
      </div>
      <?php }
      }else{
        echo "<p class='error'>No playlists added yet!</p>";
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