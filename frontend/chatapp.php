<?php

  session_start();

  include_once('../utility.php');
  include_once('../database.php');


  if (isset($_COOKIE['token'])) {
    list($username, $passhash) = explode(',', $_COOKIE['token']);
    if (!matchhash($username, $passhash)) {
      setcookie('token', '', time() - 3600);
      $_SESSION['badcookie'] = 1;
      header('Location: login.php');
      exit();
    }
    else {
      $_SESSION['username'] = $username;
    }
  }

  if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    die;
  }

  $username = $_SESSION['username'];
  $user = mysqli_query($db, "select * from user where username like '$username';");
  $user = $user -> fetch_assoc();
  // var_dump($user);
  $my_current_ip = exec("ifconfig | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*' | grep -v '127.0.0.1'");
  $get_link = "https://localhost/learn_php/post.php";
  $link = "https://$my_current_ip/";

?>


<html>

<head>
  <title>CWPH Webapp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat"> -->
  <link rel='stylesheet' href='./css/style1.css'>

  <script>

    // function mobile_view() {
    //   var viewportWidth = $(window).width();
    //   var viewportHeigh = $(window).height();

    //   var dsc;
    //   if (viewportWidth < 900 && $('.container').find('.discussions')) {
    //     dsc = $('.discussions').detach();
    //   }

    //   if (viewport)

    // }

    function search_query() {
      let s = $('.search').find('input').val().toLowerCase();
      $('.discussion').each(function () {
        if ($(this).hasClass('search')) return;
        let x = $(this).attr('data-username');
        if (x.toLowerCase().indexOf(s) > -1) $(this).show();
        else $(this).hide();
      });
    }

    let sender = "<?php echo $_SESSION['username'] ?>";

    function load_messages() {
      var fullname = $('.message-active').find('.name').html();
      $('.header-chat').find('.name').html(fullname);
      var username = $('.message-active').attr('data-id');
      $('.messages-chat').html('');
      $.ajax({
        type: 'POST',
        url: '<?php echo $get_link; ?>',
        data: {
          lmess: 1,
          user: username
        },
        success: function (data) {
          // console.log(JSON.parse(data));
          data = JSON.parse(data);
          console.log(data);
          let s;
          data.forEach(x => {
            if (x.sender === username) s = "<div class=\"message\"><div class=\"photo\"style=\"background-image: url(https://avatars.dicebear.com/api/bottts/" + $('.message-active').attr('data-username') + ".svg);\"><div class=\"online\"></div></div><p class=\"text\">" + x.message_string + "</p><p class=\"time\">" + x.message_timestamp + "</p></div>";
            else s = "<div class=\"message response\"><p class=\"time\">" + x.message_timestamp + "</p><p class=\"text\">" + x.message_string + "</p><div class=\"photo\"style=\"background-image: url(https://avatars.dicebear.com/api/bottts/" + sender + ".svg);\"><div class=\"online\"></div></div></div>";
            $('.messages-chat').append(s);
          });
        }
      });
    }

    function sendMessage() {
      let message = $('.footer-chat .write-message').val();
      let receiver = $('.message-active').attr('data-id');

      if (message.length > 0) {
        $.ajax({
          type: 'POST',
          url: '<?php echo $get_link; ?>',
          data: {
            send: 1,
            username: receiver,
            message: message
          },
          success: function (data) {
            data = JSON.parse(data);
            console.log(data);
            let s;
            let x = data;
            if (x.sender === receiver) s = "<div class=\"message\"><div class=\"photo\"style=\"background-image: url(https://avatars.dicebear.com/api/bottts/" + $('.message-active').attr('data-username') + ".svg);\"><div class=\"online\"></div></div><p class=\"text\">" + x.message_string + "</p><p class=\"time\">" + x.message_timestamp + "</p></div>";
            else s = "<div class=\"message response\"><p class=\"time\">" + x.message_timestamp + "</p><p class=\"text\">" + x.message_string + "</p><div class=\"photo\"style=\"background-image: url(https://avatars.dicebear.com/api/bottts/" + sender + ".svg);\"><div class=\"online\"></div></div></div>";
            $('.messages-chat').prepend(s);
          }
        })
      }
    }

    function online_status() {
      return 1;
    }

    function last_seen() {
      return 1;
    }

    $(document).ready(() => {
      // populate_chat_list();
      // load_recent_chat();
      // callback for online_status();
      // callback for message read status
      // add usernames inside ids of divs

      $(".discussions").on('click', '.discussion', function () {
        if ($(this).hasClass('message-active') || $(this).hasClass('search')) return;
        $('.message-active').removeClass('message-active');
        $(this).addClass('message-active');
        load_messages();
      });

      $('.discussions').on({
        mouseenter: function () {
          if ($(this).hasClass('search')) return;
          $(this).css("background-color", "#a0b9ff");
        },
        mouseleave: function () {
          if ($(this).hasClass('search')) return;
          $(this).css("background-color", "#fafafa");
        }
      }, '.discussion');

      // function populate_chat_list() {
      $.ajax({
        type: 'POST',
        url: '<?php echo $get_link; ?>',
        data: {
          pop_chat: 1
        },
        success: function (data) {
          console.log(JSON.parse(data));
          data = JSON.parse(data);
          data.forEach(x => {
            $('.discussions').append("<div data-username = \"" + x.username + "\" data-id=\"" + x.id + "\" class=\"discussion\"><div class=\"photo\" style = \"background-image: url(https://avatars.dicebear.com/api/bottts/" + x.username + ".svg);\"><div class=\"online\"></div></div><div class=\"desc-contact\"><p class=\"name\">" + x.full_name + "</p></div><div class=\"timer\">" + last_seen(x.username) + "</div></div>");
          });
          $('.discussions').find('.discussion')[1].click();
        }
      });


      $('.write-message').keypress(function (e) {
        if (e.which == 13) sendMessage();
      });

    });
  </script>

</head>

<body>
  <div class="container">
    <nav class="menu">
      <ul class="items">
        <a href="" title="Home">
          <li class="item">
            <i class="fa fa-home" aria-hidden="true"></i>
          </li>
        </a>
        <a href="" title="Add User">
          <li class="item">
            <i class="fa fa-user-plus" aria-hidden="true"></i>
          </li>
        </a>
        <a href="" title="Ban User">
          <li class="item">
            <i class="fa fa-ban" aria-hidden="true"></i>
          </li>
        </a>
        <a href="logout.php" title="LogOut">
          <li class="item">
            <a href="logout.php" title="LogOut"><i class="fa fa-sign-out" aria-hidden="true"></i>
          </li>
        </a>
      </ul>
    </nav>

    <section class="discussions">
      <div class="discussion search">
        <div class="searchbar">
          <i class="fa fa-search" aria-hidden="true"></i>
          <input type="text" placeholder="Search..." onkeyup="search_query()"></input>
        </div>
      </div>
    </section>

    <section class="chat">
      <div class="header-chat">
        <i class="icon fa fa-user-o" aria-hidden="true"></i>
        <p class="name">Megan Leib</p>
        <i class="icon clickable fa fa-ellipsis-h right" aria-hidden="true"></i>
      </div>
      <div class="messages-chat">
        <div class="message">
          <div class="photo"
            style="background-image: url(https://image.noelshack.com/fichiers/2017/38/2/1505775062-1505606859-portrait-1961529-960-720.jpg);">
            <div class="online"></div>
          </div>
          <p class="text"> Hi, how are you ? </p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text"> What are you doing tonight ? Want to go take a drink ?</p>
          <p class="time"> 14h58</p>
        </div>
        <div class="message text-only">
          <p class="text response"> Hey Megan ! It's been a while 😃</p>
        </div>
        <div class="message text-only">
          <div class="response">
            <p class="text"> When can we meet ?</p>
          </div>
        </div>
        <p class="response-time time"> 15h04</p>
        <div class="message">
          <div class="photo"
            style="background-image: url(https://image.noelshack.com/fichiers/2017/38/2/1505775062-1505606859-portrait-1961529-960-720.jpg);">
            <div class="online"></div>
          </div>
          <p class="text"> 9 pm at the bar if possible 😳</p>
        </div>
        <p class="time"> 15h09</p>
      </div>
      <div class="footer-chat">
        <input type="text" class="write-message" placeholder="Type your message here"></input>
        <i class="icon send fa fa-paper-plane-o clickable" aria-hidden="true" onclick="sendMessage()"></i>
      </div>
    </section>
  </div>
</body>


<script>
  $(document).ready(() => {
    $(".discussion .search").on("keyup")
  });
</script>


</html>