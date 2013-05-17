<?php
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '408995115866134',
  'secret' => 'b52f4b432e2b915c4c376e0e783706c3',
));


// Get User ID
$user = $facebook->getUser();

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
?>