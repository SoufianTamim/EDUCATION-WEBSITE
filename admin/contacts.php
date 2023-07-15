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
  <title>admin | messages</title>

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
    <h1 class="heading">your messages</h1>
    <div class="comment-container">
      <?php
        $select_contact = $conn->prepare("SELECT * FROM contact ");
        $select_contact->execute([]);
        if($select_contact->rowCount() > 0){
          while($fetch_contact = $select_contact->fetch(PDO::FETCH_OBJ)){
                ?>
      <div class="comment-box">
        <div class="flex">
          <p class="alt-img"><?= $fetch_contact->name[0]?></p>
          <div>
            <h3><?=$fetch_contact->name?></h3>
            <div class="flex">
              <p><?=$fetch_contact->email?></p> -
              <p>0<?=$fetch_contact->number?></p>
            </div>
          </div>
        </div>
        <div class="comment-text"><?=$fetch_contact->message?></div>
      </div>
      <?php    
          }    
    }else{
      echo "<p class='error'>There is no messages for you</p>";
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