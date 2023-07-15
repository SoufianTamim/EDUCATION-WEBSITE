<?php
include 'connection.php';
  if(isset($_GET['logout'])){
    setcookie('admin_id', '', time() - 1, '/');
    header('location:../admin/login.php');
  }
?>