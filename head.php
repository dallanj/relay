<?php
include('inc/db.php');
include('inc/authenticator.php'); // check if user has login cookies

require_once __DIR__ . '/config.php';
$recaptchaConfig = app_config()['recaptcha'];
?>

<!-- <!DOCTYPE html> -->
<html lang="en">
	<head>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	    <link rel="stylesheet" href="./inc/styleLogin.css">


	    <link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
		<meta charset="UTF-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">

    	<?php if ($recaptchaConfig['enabled'] && $recaptchaConfig['site_key']): ?>
    	<!-- Google recaptcha v3 -->
    	<script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($recaptchaConfig['site_key']); ?>"></script>
	    <script>
	        grecaptcha.ready(function () {
	            grecaptcha.execute('<?php echo htmlspecialchars($recaptchaConfig['site_key']); ?>', { action: 'contact' }).then(function (token) {
	                var recaptchaResponse = document.getElementById('recaptchaResponse');
	                recaptchaResponse.value = token;
	            });
	        });
	    </script>
	    <?php endif; ?>

	</head>
