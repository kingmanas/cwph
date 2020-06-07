<?php

include_once('users/user.php');
include_once('database.php');
include_once('email_client.php');

function username_available($username) {
  global $db;
  $q = "select id from user where username='$username';";
  $res = mysqli_query($db, $q);
  if($res->num_rows === 0) { return true;}
  return false;
}

function matchhash($username, $passhash) {
  global $db;
  $q = "select * from user where username='$username' and passhash='$passhash';";
  $res = mysqli_query($db, $q);
  if($res->num_rows === 1) { return true;}
  var_dump($res);
  return false;
}

function adduser($fullname, $email, $username, $passhash) {
    global $db;
    $q = "insert into user(full_name, email, username, passhash, ip) values ('$fullname', '$email', '$username', '$passhash','".$_SERVER['REMOTE_ADDR']."');";
    mysqli_query($db, $q) or die("Problem inserting user to the database".mysqli_connect_error());
    emailVerification($username);
}

function is_queued_for_validation($username) {
  global $db;
  $q = "select * from valid_queue where username='$username';";
  $res = mysqli_query($db, $q);
  if($res->num_rows > 0) return true;
  return false;
}

?>