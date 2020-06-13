<?php

session_start();

if(isset($_SESSION['badcookie'])) {
  echo "<script>alert('Cookies were tampered. Please login again.');</script>";
  unset($_SESSION['badcookie']);
}

if(isset($_SESSION['validatepass'])) {
  echo "<script>alert('Check your email for the validation link');</script>";
  unset($_SESSION['validatepass']);
}

if(isset($_SESSION['navailusername'])) {
  echo "<script>alert('Username not available');</script>";
  unset($_SESSION['navailusername']);
}

if(isset($_SESSION['navailemail'])) {
  echo "<script>alert('Email not available');</script>";
  unset($_SESSION['navailemail']);
}

if(isset($_SESSION['loginerror'])) {
  echo "<script>alert('Incorrect username or password. Password must be at least 8 characters. Username must be between 4 and 20 characters');</script>";
  unset($_SESSION['loginerror']);
}

if(isset($_SESSION['registererror'])) {
  echo "<script>alert('Invalid Registration');</script>";
  unset($_SESSION['registererror']);
}

if(isset($_COOKIE['token'])) {
  header('Location:chatapp.php');
  die;
}

include_once('../utility.php');
$secret = "ma!@#@%^ds%adf$^^&ds%$%$^&FaffhnHuhfdjnv";

if(isset($_POST['login'])){
  if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $passhash = md5($_POST['password'].$secret);

    if(strlen($username) < 4 || strlen($username) > 20 || !matchhash($username, $passhash)) {
      $_SESSION['loginerror'] = 1;
      header('Location:login.php');
      die;
    }

    if(is_queued_for_validation($username) || !isVerified($username)) {
      $_SESSION['validatepass']  = 1;
      header('Location:login.php');
      die;
    }

    if(isset($_POST['remember'])) {
      setcookie('token', $username.",".$passhash, time() + 86400*30);
    }

    $_SESSION['username'] = $username;

    header('Location:chatapp.php');
    exit();
  }
}
else if(isset($_POST['register']) && isset($_POST['full_name']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
  $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
  $passhash = md5($_POST['password'].$secret);
  $fullname = filter_var($_POST['full_name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  // var_dump($email, $username, $passhash, $fullname);

  if(strlen($_POST['password']) < 8 || !$email || !$username || !$passhash || !$fullname || strlen($username) < 4 || strlen($username) > 20 || strlen($email) > 60 || strlen($fullname) > 50) {
      $_SESSION['registererror'] = 1;
      header('Location:login.php');
      die;
  }

  if(!username_available($username)) {
    $_SESSION['navailusername'] = 1;
    header('Location:login.php');
    die;
  }

  if(!email_available($email)) {
    $_SESSION['navailemail'] = 1;
    header('Location:login.php');
    die;
  }

  adduser($fullname, $email, $username, $passhash);

  $_SESSION['validatepass'] = 1;

  header('Location:login.php');
  die();
}

?>


<html>
<link rel="stylesheet" href="./css/login.css">

<body>
  <section class="user">
    <div class="user_options-container">
      <div class="user_options-text">
        <div class="user_options-unregistered">
          <h2 class="user_unregistered-title">Don't have an account?</h2>
          <p class="user_unregistered-text">A few bad chapters does not mean your story is over.</p>
          <button class="user_unregistered-signup" id="signup-button">Sign up</button>
        </div>

        <div class="user_options-registered">
          <h2 class="user_registered-title">Have an account?</h2>
          <p class="user_registered-text">I am proud of you my child that you made it till here, now leave it on
            me.<br>Everything's gonna be fine.</p>
          <button class="user_registered-login" id="login-button">Login</button>
        </div>
      </div>

      <div class="user_options-forms" id="user_options-forms">
        <div class="user_forms-login">
          <h2 class="forms_title">Login</h2>
          <form class="forms_form" action="" method="POST">
            <div class="forms_fieldset">
              <div class="forms_field">
                <input autocomplete = "on" type="text" name = "username" placeholder="username" class="forms_field-input" required autofocus />
              </div>
              <div class="forms_field">
                <input autocomplete = "on" type="password" name = "password" placeholder="password" class="forms_field-input" required />
              </div>
              <div class="forms_field">
              <input type="checkbox" name="remember" id="rememberMe">
              <label for="rememberMe"><font style = "text-size: 2.5em; color: #fff; ">Remember Me</font></label>
              </div>
            </div>
            <div class="forms_buttons">
              <button type="button" class="forms_buttons-forgot" onclick="forgot_pass()">Forgot password?</button>
              <input type="submit" name = "login" value="Login" class="forms_buttons-action">
            </div>
          </form>
        </div>
        <div class="user_forms-signup">
          <h2 class="forms_title">Sign Up</h2>
          <form class="forms_form" action="" method="POST">
            <div class="forms_fieldset">
              <div class="forms_field">
                <input name="full_name" type="text" placeholder="Full Name" class="forms_field-input" required />
              </div>
              <div class="forms_field">
                <input name="username" type="username" placeholder="Username" class="forms_field-input" required />
              </div>
              <div class=" forms_field">
                <input name="email" type="email" placeholder="Email" class="forms_field-input" required />
              </div>
              <div class="forms_field">
                <input name="password" type="password" placeholder="Password" class="forms_field-input" required />
              </div>
            </div>
            <div class="forms_buttons">
              <input type="submit" name = "register" value="Sign Up" class="forms_buttons-action">
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- animations and stuff -->
  <script type="text/javascript">
    //event - listeners
    const signupButton = document.getElementById('signup-button'),
      loginButton = document.getElementById('login-button'),
      userForms = document.getElementById('user_options-forms')
    if (signupButton) {
      signupButton.addEventListener('click', () => {
        userForms.classList.remove('bounceRight')
        userForms.classList.add('bounceLeft')
      }, false)
    }
    if (loginButton) {
      loginButton.addEventListener('click', () => {
        userForms.classList.remove('bounceLeft')
        userForms.classList.add('bounceRight')
      }, false)
    }
  </script>

</body>
</html>