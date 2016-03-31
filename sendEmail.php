<?php
/*
  Checks the database to determine if we need to send an email.
  If so, send an email with the link to the login page.


  To periodically check, add the following to a cron job:*/
//        * * */5 * * /usr/local/bin/php -q /www/scripts/myscript.php

  require_once('DbAccessor.php');

  $dbWorker = new DbAccessor();

  // get users
  $users = ['mpl934@mocs.utc.edu'];

  // send email to users
  for ($i=0; $i < count($users); $i++) {
    $to = $users[$i];
    $subject = "Doodle Experiment";
    $message = "Thank you for participating in the Doodle Experiment! \r\n"
                  . "Please follow this link to login with your doodle and password: \r\n"
                  . "<a href='mydoodle.duckdns.org/login.php'>Login</a> \r\n \r\n";
    $headers = "From: do-not-reply@mydoodle.duckdns.org";
    $success = mail($to, $subject, $message, $headers);

    if ($success) {
      echo "Successfully sent email<br>";
    } else {
      echo "Failed to send email<br>";
    }
  }

  if (count($users) == 0) echo "No one needs to be emailed";
?>
