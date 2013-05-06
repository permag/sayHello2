<?php 

require('src/facebook.php');
require_once('fb.php');

if ($user) {
  $_SESSION['activeUserId'] = $user_profile['id'];
}

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <!-- <meta content='width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;' name='viewport' /> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
    <!-- <script src="//connect.facebook.net/en_US/all.js"></script> -->
    <title>sayHello</title>

  </head>
  <body>

    <!-- FB LOGIN -->
    <div id="fb-root"></div>

    <div class="container main hero-unit">

      <div class="myStuffCont pull-right">
      <?php if ($user) { ?>
        <h4>My stuff <span id="deleteAlsterSpan"><a href="#" id="deleteAlster"><p class="icon-trash" title="Toggle trash can"></p></a></span></h4>
        <div id="trash">Drag and drop here to delete</div>
        <div id="myStuff"></div>
        <div id="showMore"></div>
      <?php } ?>
      </div>

      <?php if ($user) {
          echo '<img src="https://graph.facebook.com/'.$user.'/picture" class="profilePic">
                <span class="profileName">'.$user_profile['first_name'] . ' ' . $user_profile['last_name'].'</span>
                <button id="logoutLink" class="btn btn-mini">Logout</button>
                <h1>:::Get silly!</h1>
                <a href="main.php" id="createButton" class="btn btn-large btn-primary">Start creating!</a>';
        } ?>

      <?php if (!$user): ?>
        <h1>sayHello</h1>
        <strong><em>Login using your Facebook account: </em></strong>
        <button id="loginLink" class="btn">Login</button>
      <?php endif ?>
    </div>

    <!-- JavaScript files -->
    <script src="./content/js/fb_login.js"></script>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <?php if ($user): ?>
    <?php endif ?>
  </body>
</html>
