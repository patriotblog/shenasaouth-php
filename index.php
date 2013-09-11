<?php
	session_start();
	include('ShenasaOAuth.php');
	$client_id = '';
	$app_secret= '';
?>

<?php
	
	if(isset($_GET['logout'])){
		setcookie ('user_email', '', time()-10, '/');
		header('Location: index.php');
		//do other works to logout user;
	}
	
	$oAuth = new ShenasaOAuth($client_id, $app_secret);
	
	$email = $oAuth->getUserEmail();
	if($email){
		setcookie ('user_email', $email, time()+3600, '/');
		header('Location: index.php');
		//do other works to login user or register;
	}
	
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
	</head>
	<body>
		<?php if(isset($_COOKIE['user_email'])) :?>
			<a href="?logout"><?php echo $_COOKIE['user_email'];?> (logout)</a>
			<pre><?php print_r($oAuth->getUserInfo()); ?></pre>
		<?php else:?>
			<a href="<?php echo $oAuth->generateLoginUri();?>">login</a>
		<?php endif;?>
	</body>
</html>