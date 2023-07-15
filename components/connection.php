<?php
$db_name = "mysql:host=localhost;dbname=educa";
$user_name = "root";
$user_password = "";
$conn = new PDO($db_name,$user_name,$user_password);

//set the user_id
if(isset($_COOKIE['user_id'])){
  $user_id = $_COOKIE['user_id'];
}else{
  $user_id = '';
}
//set the admin_id
if(isset($_COOKIE['admin_id'])){
  $admin_id = $_COOKIE['admin_id'];
}else{
  $admin_id = '';
}

//generate random id
function create_unique_id() {
  $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $rand = array();
  $length = strlen($str) - 1;
  for ($i = 0; $i < 20; $i++) {
      $n = mt_rand(0, $length);
      $rand[] = $str[$n];
  }
  return implode($rand);
}