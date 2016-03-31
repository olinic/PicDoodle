<?php
/*
  Checks the database to determine if we need to send an email.
  If so, send an email with the link to the login page.

  I used the following link to setup a SMTP server: http://askubuntu.com/questions/47609/how-to-have-my-php-send-mail

  To periodically check, add the following to a cron job:*/
//        * * */5 * * /usr/local/bin/php -q /www/scripts/myscript.php

  require_once('DbAccessor.php');

  $dbWorker = new DbAccessor();

  // get users
  $users = ['mpl934@mocs.utc.edu'];

  // send email to users
  for ($i=0; $i < count($users); $i++) {
    $to = $users[$i];
    $subject = "Doodle Project";
    $message = "<html>
                  <body>
                    <p>
                      Hello, <br><br>
                      This is a follow up on the Doodle Project.<br>
                      Please click on the link to login with your doodle and password:<br>
                      <a href='mydoodle.duckdns.org/login.php'>Go to Login</a>
                      <br>
                      <br>
                      <br>
                      Thank you for participating in the Doodle Experiment!<br>
                    </p>
                  </body>
                  </html>";
    $headers .= 'From: mpl934@mocs.utc.edu' . "\r\n"
                  . 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $success = mail($to, $subject, $message, $headers);

    if ($success) {
      echo "Successfully sent email<br>";
    } else {
      echo "Failed to send email<br>";
    }
  }

  if (count($users) == 0) echo "No one needs to be emailed";
?>
