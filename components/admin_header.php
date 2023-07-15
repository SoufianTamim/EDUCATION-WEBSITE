<!-- header section start -->
<header class="header">
  <section class="flex">
    <a href="home.php" class="logo">Admin</a>
    <form action="" method="post" class="search-form">
      <input type="text" name="search_value" placeholder="search here..." required maxlength="50" />
      <button type="submit" class="fas fa-search" name="search_btn"></button>
    </form>
    <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="search-btn" class="fas fa-search"></div>
      <div id="user-btn" class="fas fa-user"></div>
      <div id="toggle-btn" class="fas fa-sun"></div>
    </div>
    <div class="profile">
      <?php if($admin_id != ''){
        $select_teacher = $conn->prepare("SELECT * FROM teachers WHERE id = ? LIMIT 1");
        $select_teacher->execute([$admin_id]);
        $fetch_teacher = $select_teacher->fetch(PDO::FETCH_OBJ);
        if($select_teacher->rowCount()>0){
          ?>
      <?php if($fetch_teacher->image != '' ){?>
      <img src="../images/uploaded_images/teachers/<?=$fetch_teacher->image?>" class="image" alt="teacher-image" />
      <?php }else{?>
      <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
      <?php }?>
      <h3 class="name"><?=$fetch_teacher->name?></h3>
      <p class="role"><?=$fetch_teacher->profession?></p>
      <a href="profile.php" class="btn">view profile</a>
      <?php
          }else{ 
            $warning_msg[] = 'Something went wrong!';
          }
        }else{?>
      <p class="error">Please login first</p>
      <?php }?>
      <div class="flex-btn">
        <a href="login.php" class="btn option-btn">login</a>
        <a href="register.php" class="btn option-btn">register</a>
      </div>
    </div>
  </section>
</header>
<!-- header section end -->

<!-- sidebar section start -->
<div class="sidebar">
  <div id="close-btn">
    <i class="fas fa-times"></i>
  </div>
  <div class="profile">
    <?php if($admin_id != ''){?>
    <?php if($fetch_teacher->image != '' ){?>
    <img src="../images/uploaded_images/teachers/<?=$fetch_teacher->image?>" class="image" alt="teacher-image" />
    <?php }else{?>
    <p class="alt-img"><?= $fetch_teacher->name[0]?></p>
    <?php }?>
    <h3 class="name"><?=$fetch_teacher->name?></h3>
    <p class="role"><?=$fetch_teacher->profession?></p>
    <a href="profile.php" class="btn">view profile</a>
    <?php }else{?>
    <p class="error">Please login first</p>
    <a href="login.php" class="btn option-btn">login</a>
    <a href="register.php" class="btn option-btn">register</a>
    <?php }?>
  </div>
  <nav class="navbar">
    <a href="index.php"><i class="fas fa-home"></i><span>dashboard</span></a>
    <a href="playlists.php"><i class="fas fa-bars-staggered"></i><span>playlists</span></a>
    <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
    <a href="comments.php"><i class="fas fa-comment"></i><span>comments</span></a>
    <a href="../components/admin_logout.php?logout" onclick="return confirm('Sure you want to logout?')"><i
        class="fas fa-right-from-bracket"></i><span>logout</span></a>
  </nav>
</div>
<!-- sidebar section end -->