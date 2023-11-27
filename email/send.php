<?php
$email = "proiectZVA@gmail.com";
$to = 'email';
$subject = "Un nou contact s-a inregistrat";
$headers = "From: $email\n";
$message = "A visitor to your site has sent the following email address
to be added to your mailing list.\n";
mail($to,$subject,$message,$headers);
if (isset($_POST['send_message_btn'])) {
    $name = $_POST['name'];
    $email = trim($_POST['email']);
    $usersubject = $_POST['subject'];
    $msg = $_POST['msg'];
// $usersubject = "Thank You";
    $userheaders = "From: poriectZVA@gmail.com\n";
//$userheaders .= "Content-type: X-Mailer: php\r\n";
    $userheaders.= "MIME-Version: 1.0" . "\n";
    $userheaders .= "Content-type:text/html;charset=UTF-8" . "\n";
    $usermessage = "Thank you for subscribing to our mailing list.";
    $usermessage = "
<DOCTYPE html>
<html lang='en'>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Name</th>
<th>Email</th>
<th>Subiect</th>
</tr>
<tr>
<td>$name</td>
<td>$email</td>
<td>$subject</td>
</tr>
</table>
<p>$msg</p>
</body>
</html>
";
   if(mail($email,$usersubject,$usermessage,$userheaders)){
       echo 'mail was sent!';}
   else{
       echo 'mail was not sent!';}
}