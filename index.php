<?php 

require_once("config.php");
 
	if(isset($_POST['post'])) {
		// print_r($_POST);
		$url = "https://www.google.com/recaptcha/api/siteverify";
		$data = [
			'secret' => $RECAPTURE_SECRET_KEY,
			'response' => $_POST['token'],
		    'remoteip' => $_SERVER['REMOTE_ADDR']
		];

		$options = array(
		    'http' => array(
		      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		      'method'  => 'POST',
		      'content' => http_build_query($data)
		    )
		  );

		$context  = stream_context_create($options);
  		$response = file_get_contents($url, false, $context);

		$res = json_decode($response, true);
		if($res['success'] == true) {

			// Perform you logic here for ex:- save you data to database
  			echo '<!-- Capture Wroked -->';
		}
	}

$post = strip_tags($_GET['post']);
if ($post == "submit") {
	//Code used for the submited information to be processed.
	$fname = strip_tags($_POST['fname']);
	$uemail = strip_tags($_POST['uemail']);
	$msg = strip_tags($_POST['msg']);
	// Sending the email using this code
$to = $YourEmailAddress;
$subject = $YourEmailSubject;

$message = '
<html>
<head>
<meta charset="utf-8">
<title>Contact Me</title>
</head>

<body>
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tbody>
    <tr>
      <td>'.$fname.'</td>
    </tr>
    <tr>
      <td>'.$uemail.'</td>
    </tr>
    <tr>
      <td>'.$msg.'</td>
    </tr>
  </tbody>
</table>
</body>
</html>
';

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <' .$YourEmailAddress. '>' . "\r\n";

mail($to,$subject,$message,$headers);
// above is the end of the code for sending the email
require_once("thankyou-template.php");
} else {
	?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Contact Me Form - With Google reCAPTURE</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="ContactMe.css">
<script src="https://www.google.com/recaptcha/api.js?render=<?php echo $RECAPTURE_SITE_KEY; ?>"></script>
</head>

<body>
<div class="center">
<!--	<form  role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?post=submit"> -->
	<form role="form" method="post">
		
	<div class="input-container">
	<span class="fa fa-user icon"></span>
	<input class="input-field" type="text" maxlength="120" size="30" id="fname" name="fname" placeholder="Your Name" size="32">
	</div>
		
	<div class="input-container">	
		<span class="fa fa-envelope icon"></span>
	<input class="input-field" type="email" maxlength="200" size="30" id="uemail" name="uemail" placeholder="Your Email">
	</div>
	<div class="input-container">
	<span class="fa fa-comment msg-icon"></span>
	<textarea class="input-field" cols="33" rows="10" maxlength="1000" id="msg" name="msg" placeholder="Your Message that you would like to send me"></textarea>
	</div>
		
	<input class="btn" type="submit" value="Post" name="post">
	<input type="hidden" id="token" value="token">
	</form>
	</div>
</body>
</html>
  <script>
  grecaptcha.ready(function() {
      grecaptcha.execute('<?php echo $RECAPTURE_SITE_KEY; ?>', {action: 'homepage'}).then(function(token) {
         console.log(token);
         document.getElementById("token").value = token;
      });
  });
  </script>
<?PHP } ?>