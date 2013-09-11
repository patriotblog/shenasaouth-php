<?php
	session_start();
	include('ShenasaOAuth.php');
	$client_id = '396307863090.apps.atlasid.local';
	$app_secret= '$2a$24$a7CexNfN.yVZR1pMJN/Bsc';
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

<?php if(isset($_COOKIE['user_email'])) :?>
	<a href="?logout"><?php echo $_COOKIE['user_email'];?> (logout)</a>
	<pre><?php print_r($oAuth->getUserInfo()); ?></pre>
<?php else:?>
	<a href="<?php echo $oAuth->generateLoginUri();?>">login</a>
<?php endif;?>