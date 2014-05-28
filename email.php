<?php
$to      = 'milo.rupp@pscs.org';
$subject = 'Independent Study Report #4527';
$message = 'Anthony has been signed out to Independent Study from Tuesday May 6 To Friday May 9.';
$headers = 'From: Attendance@pscs.org' . "\r\n" .
    'Reply-To: code@pscs.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $message, $headers);
?>