<?php

include_once('users/user.php');
include_once('database.php');
include_once('verify_email.php');

function username_available($username) {
  global $db;
  $q = "select id from user where username='$username';";
  $res = mysqli_query($db, $q);
  if($res->num_rows === 0) { return true;}
  return false;
}

function email_available($email) {
  global $db;
  $q = "select id from user where email='$email';";
  $res = mysqli_query($db, $q);
  if($res->num_rows === 0) { return true;}
  return false;
}

function matchhash($username, $passhash) {
  global $db;
  $q = "select * from user where username='$username' and passhash='$passhash';";
  $res = mysqli_query($db, $q);
  if($res->num_rows === 1) { return true;}
  return false;
}

function adduser($fullname, $email, $username, $passhash) {
    global $db;
    $q = "insert into user(full_name, email, username, passhash, ip) values ('$fullname', '$email', '$username', '$passhash','".$_SERVER['REMOTE_ADDR']."');";
    mysqli_query($db, $q) or die("Problem inserting user to the database".mysqli_connect_error());
    // emailVerification($username);
    return;
}

function isVerified($username) {
  global $db;
  $q = "select verified from user where username like '$username';";
  $res = mysqli_query($db, $q);
  if($res->fetch_assoc()['verified'] === '1') return true;
  return false; 
}

function is_queued_for_validation($username) {
  global $db;
  $q = "select * from valid_queue where username='$username';";
  $res = mysqli_query($db, $q);
  if($res->num_rows > 0) return true;
  return false;
}

?>