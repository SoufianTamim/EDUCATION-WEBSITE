<!-- header section start -->
<header class="header">
  <section class="flex">
    <a href="home.php" class="logo">Educa</a>
    <form action="courses.php" method="post" class="search-form">
      <input type="text" name="search_value" placeholder="search courses..." required maxlength="50" />
      <button type="submit" class="fas fa-search" name="search_btn"></button>
    </form>
    <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="search-btn" class="fas fa-search"></div>
      <div id="user-btn" class="fas fa-user"></div>
      <div id="toggle-btn" class="fas fa-sun"></div>
    </div>
    <div class="profile">
      <?php if($user_id != ''){
        $select_user = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $select_user->execute([$user_id]);
        $fetch_user = $select_user->fetch(PDO::FETCH_OBJ);
        if($select_user->rowCount()>0){
          ?>
      <?php if($fetch_user->image != '' ){?>
      <img src="images/uploaded_images/users/<?=$fetch_user->image?>" class="image" alt="user-image" />
      <?php }else{?>
      <p class="alt-img"><?= $fetch_user->name[0]?></p>
      <?php }?>
      <h3 class="name"><?=$fetch_user->name?></h3>
      <p class="role">student</p>
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
    <?php if($user_id != ''){?>
    <?php if($fetch_user->image != '' ){?>
    <img src="images/uploaded_images/users/<?=$fetch_user->image?>" class="image" alt="user-image" />
    <?php }else{?>
    <p class="alt-img"><?= $fetch_user->name[0]?></p>
    <?php }?>
    <h3 class="name"><?=$fetch_user->name?></h3>
    <p class="role">studen</p>
    <a href="profile.php" class="btn">view profile</a>
    <?php }else{?>
    <p class="error">Please login first</p>
    <a href="login.php" class="btn option-btn">login</a>
    <a href="register.php" class="btn option-btn">register</a>
    <?php }?>
  </div>
  <nav class="navbar">
    <a href="home.php"><i class="fas fa-home"></i><span>home</span></a>
    <a href="about.php"><i class="fas fa-question"></i><span>about</span></a>
    <a href="playlists.php"><i class="fas fa-bars-staggered"></i><span>playlists</span></a>
    <a href="courses.php"><i class="fas fa-graduation-cap"></i><span>courses</span></a>
    <a href="teachers.php"><i class="fas fa-chalkboard-user"></i><span>teachers</span></a>
    <a href="contact.php"><i class="fas fa-headset"></i><span>contact us</span></a>
  </nav>
</div>
<!-- sidebar section end -->